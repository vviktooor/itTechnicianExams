// Load each exam's database, serve the solution with PHP and save a screenshot. Author: WR
// Needs: mysql client with access as root over the socket, php with mysqli, Node with playwright.
// Usage: node tools/screenshots.mjs [exam-dir ...]
import { spawn, execFileSync } from "node:child_process";
import { readFileSync } from "node:fs";
import { createRequire } from "node:module";

const { chromium } = createRequire(import.meta.url)("playwright"); // works with a local or global install (NODE_PATH)

const exams = JSON.parse(readFileSync("exams/exams.json", "utf8"));
const wanted = process.argv.slice(2);
const port = 8091;
const browser = await chromium.launch();
let failed = 0;

for (const exam of exams.filter((e) => !wanted.length || wanted.includes(e.dir))) {
  const root = `exams/${exam.dir}`;
  execFileSync("mysql", ["-e", `DROP DATABASE IF EXISTS \`${exam.database}\`; CREATE DATABASE \`${exam.database}\` CHARACTER SET utf8mb4`]);
  execFileSync("mysql", [exam.database], { input: readFileSync(`${root}/${exam.sql}`) });
  const server = spawn("php", ["-S", `127.0.0.1:${port}`, "-t", root], { stdio: "ignore" });
  await new Promise((r) => setTimeout(r, 400));
  const page = await browser.newPage({ viewport: { width: 1280, height: 800 } });
  const response = await page.goto(`http://127.0.0.1:${port}/${encodeURI(exam.entry)}`, { waitUntil: "networkidle" });
  const body = await page.textContent("body");
  const broken = !response.ok() || /Fatal error|Warning:|Uncaught/.test(body);
  await page.screenshot({ path: `docs/screenshots/${exam.dir}.png` });
  console.log(`${broken ? "FAIL" : "PASS"} ${exam.dir} (HTTP ${response.status()})`);
  failed += broken;
  await page.close();
  server.kill();
}
await browser.close();
process.exit(failed ? 1 : 0);
