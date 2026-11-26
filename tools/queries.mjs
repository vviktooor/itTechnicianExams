// Read answer/kwerendy.txt files: parse numbered queries, check them, render their results. Author: WR
// Usage: node tools/queries.mjs check            -> every exam has query_count numbered queries (CI, no database)
//        imported by tools/screenshots.mjs       -> resultHtml() runs a query and returns its result as HTML
import { execFileSync } from "node:child_process";
import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";

const HEADER = /^--\s*(?:Zapytanie|Kwerenda)\s+(\d+)\s*:/i;

// Split a kwerendy.txt into [{ number, comment, sql }]; a query starts with "-- Zapytanie N:" and ends with ";".
export function parseQueries(text) {
  const queries = [];
  let current = null;
  for (const line of text.split(/\r?\n/)) {
    const header = line.match(HEADER);
    if (header) {
      current = { number: Number(header[1]), comment: [line.replace(/^--\s*/, "")], sql: [] };
      queries.push(current);
    } else if (line.startsWith("--") && current && !current.sql.length) {
      current.comment.push(line.replace(/^--\s*/, ""));
    } else if (line.trim() && current) {
      current.sql.push(line);
    }
  }
  return queries.map((q) => ({ number: q.number, comment: q.comment.join(" "), sql: q.sql.join("\n").trim() }));
}

export function checkExam(exam, root = "exams") {
  const queries = parseQueries(readFileSync(`${root}/${exam.dir}/${exam.queries}`, "utf8"));
  const problems = [];
  const numbers = queries.map((q) => q.number).join(",");
  const expected = Array.from({ length: exam.query_count }, (_, i) => i + 1).join(",");
  if (numbers !== expected) problems.push(`queries numbered [${numbers}], expected [${expected}]`);
  for (const q of queries) {
    const statements = q.sql.split(/;\s*(?:\n|$)/).filter((s) => s.trim());
    if (!q.sql.endsWith(";") || statements.length !== 1) problems.push(`query ${q.number} must be exactly one statement ending with ";"`);
  }
  return problems;
}

// A statement that returns no rows gets a follow-up query that shows its effect.
function followUp(sql) {
  const rules = [
    [/^INSERT\s+INTO\s+`?(\w+)/i, (t) => `SELECT * FROM \`${t}\``],
    [/^UPDATE\s+`?(\w+)/i, (t) => `SELECT * FROM \`${t}\``],
    [/^(?:ALTER|CREATE)\s+TABLE\s+(?:`?\w+`?\.)?`?(\w+)/i, (t) => `DESCRIBE \`${t}\``],
    [/^CREATE\s+VIEW\s+`?(\w+)/i, (t) => `SELECT * FROM \`${t}\``],
    [/^CREATE\s+USER\s+'([^']+)'@'([^']+)'/i, (user, host) => `SELECT User, Host FROM mysql.user WHERE User = '${user}' AND Host = '${host}'`],
  ];
  for (const [pattern, make] of rules) {
    const match = sql.match(pattern);
    if (match) return make(...match.slice(1));
  }
  return null;
}

// Run one query (and its follow-up) on a database with the mysql client; return HTML tables.
export function resultHtml(database, sql) {
  const user = sql.match(/^CREATE\s+USER\s+('[^']+'@'[^']+')/i);
  if (user) execFileSync("mysql", ["-e", `DROP USER IF EXISTS ${user[1]}`]); // keep local reruns repeatable
  const extra = followUp(sql);
  const script = extra ? `${sql}\nSELECT 'Query OK' AS wynik;\n${extra};` : sql;
  return execFileSync("mysql", ["-H", database], { input: script }).toString();
}

if (process.argv[1] === fileURLToPath(import.meta.url) && process.argv[2] === "check") {
  const exams = JSON.parse(readFileSync("exams/exams.json", "utf8"));
  let failed = 0;
  for (const exam of exams) {
    const problems = checkExam(exam);
    failed += problems.length > 0;
    console.log(problems.length ? `FAIL ${exam.dir}: ${problems.join("; ")}` : `PASS ${exam.dir} (${exam.query_count} queries)`);
  }
  process.exit(failed ? 1 : 0);
}
