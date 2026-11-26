// For each exam: load its database, screenshot the solution page, then run every query
// from answer/kwerendy.txt and save its result as answer/kwN.png. Author: WR
// Needs: mysql client with access as root over the socket, php with mysqli, Node with playwright.
// Usage: node tools/screenshots.mjs [exam-dir ...]
import { spawn, execFileSync } from "node:child_process";
import { readFileSync } from "node:fs";
import { createRequire } from "node:module";
import { parseQueries, resultHtml } from "./queries.mjs";

const { chromium } = createRequire(import.meta.url)("playwright"); // works with a local or global install (NODE_PATH)

const exams = JSON.parse(readFileSync("exams/exams.json", "utf8"));
const wanted = process.argv.slice(2);
const port = 8091;
const browser = await chromium.launch();
let failed = 0;

const escape = (s) => s.replace(/[&<>]/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;" })[c]);
const resultPage = (title, sql, tables) => `<!DOCTYPE html><html lang="pl"><head><meta charset="UTF-8"><style>
  body { font-family: sans-serif; margin: 16px; color: #222; background: #fff; }
  h1 { font-size: 16px; margin: 0 0 8px; }
  pre { background: #f4f4f4; border: 1px solid #ccc; padding: 8px; white-space: pre-wrap; }
  table { border-collapse: collapse; margin: 8px 0; font-size: 14px; }
  th, td { border: 1px solid #999; padding: 4px 8px; text-align: left; }
  th { background: #e8e8e8; }
</style></head><body><h1>${escape(title)}</h1><pre>${escape(sql)}</pre>${tables}</body></html>`;

for (const exam of exams.filter((e) => !wanted.length || wanted.includes(e.dir))) {
  const root = `exams/${exam.dir}`;
  execFileSync("mysql", ["-e", `DROP DATABASE IF EXISTS \`${exam.database}\`; CREATE DATABASE \`${exam.database}\` CHARACTER SET utf8mb4`]);
  execFileSync("mysql", [exam.database], { input: readFileSync(`${root}/${exam.sql}`) });

  // The exam root is served, so solution/ pages can link to ../answer and ../sheet.
  const server = spawn("php", ["-S", `127.0.0.1:${port}`, "-t", root], { stdio: "ignore" });
  await new Promise((r) => setTimeout(r, 400));
  const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
  const response = await page.goto(`http://127.0.0.1:${port}/${encodeURI(exam.entry)}`, { waitUntil: "networkidle" });
  const body = await page.textContent("body");
  const broken = !response.ok() || /Fatal error|Warning:|Notice:|Deprecated:|Uncaught/.test(body);
  await page.screenshot({ path: `docs/screenshots/${exam.dir}.png` });
  console.log(`${broken ? "FAIL" : "PASS"} ${exam.dir} page (HTTP ${response.status()})`);
  failed += broken;
  server.kill();

  // Queries run in order on the same database, as in the exam.
  await page.setViewportSize({ width: 900, height: 200 });
  const before = failed;
  for (const query of parseQueries(readFileSync(`${root}/${exam.queries}`, "utf8"))) {
    let tables;
    try {
      tables = resultHtml(exam.database, query.sql);
    } catch (error) {
      console.log(`FAIL ${exam.dir} query ${query.number}: ${String(error.stderr || error).trim()}`);
      failed += 1;
      continue;
    }
    await page.setContent(resultPage(query.comment, query.sql, tables));
    await page.screenshot({ path: `${root}/answer/kw${query.number}.png`, fullPage: true });
  }
  if (failed === before) console.log(`PASS ${exam.dir} queries`);
  await page.close();
}
await browser.close();
process.exit(failed ? 1 : 0);
