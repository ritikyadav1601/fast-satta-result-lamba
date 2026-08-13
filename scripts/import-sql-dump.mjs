import fs from 'node:fs';
import path from 'node:path';

const source = path.resolve(process.argv[2] || '../u261634547_fast_satta_res (2).sql');
const destination = path.resolve(process.argv[3] || 'data/site.json');
const sql = fs.readFileSync(source, 'utf8');

function unescapeMysql(value) {
  const escapes = { 0: '\0', b: '\b', n: '\n', r: '\r', t: '\t', Z: '\x1a' };
  return value.replace(/\\(.)/gs, (_, char) => escapes[char] ?? char);
}

function parseValue(text, state) {
  while (/\s/.test(text[state.i])) state.i++;
  if (text[state.i] === "'") {
    state.i++;
    let value = '';
    while (state.i < text.length) {
      const char = text[state.i++];
      if (char === '\\') {
        value += char + (text[state.i++] ?? '');
      } else if (char === "'") {
        if (text[state.i] === "'") { value += "'"; state.i++; }
        else break;
      } else value += char;
    }
    return unescapeMysql(value);
  }
  const start = state.i;
  while (state.i < text.length && !/[),]/.test(text[state.i])) state.i++;
  const raw = text.slice(start, state.i).trim();
  if (/^null$/i.test(raw)) return null;
  if (/^-?\d+(\.\d+)?$/.test(raw)) return Number(raw);
  return raw;
}

function parseRows(text) {
  const rows = [];
  const state = { i: 0 };
  while (state.i < text.length) {
    while (state.i < text.length && text[state.i] !== '(') state.i++;
    if (state.i >= text.length) break;
    state.i++;
    const row = [];
    while (state.i < text.length) {
      row.push(parseValue(text, state));
      while (/\s/.test(text[state.i])) state.i++;
      if (text[state.i] === ',') state.i++;
      else if (text[state.i] === ')') { state.i++; break; }
      else throw new Error(`Unexpected SQL near offset ${state.i}`);
    }
    rows.push(row);
  }
  return rows;
}

function readTables() {
  const tables = {};
  const insert = /INSERT INTO `([^`]+)` \(([^)]+)\) VALUES\s*/g;
  let match;
  while ((match = insert.exec(sql))) {
    let i = insert.lastIndex;
    let quoted = false;
    let escaped = false;
    for (; i < sql.length; i++) {
      const char = sql[i];
      if (escaped) escaped = false;
      else if (quoted && char === '\\') escaped = true;
      else if (char === "'") quoted = !quoted;
      else if (!quoted && char === ';') break;
    }
    const columns = [...match[2].matchAll(/`([^`]+)`/g)].map(item => item[1]);
    const records = parseRows(sql.slice(insert.lastIndex, i)).map(values =>
      Object.fromEntries(columns.map((column, index) => [column, values[index]]))
    );
    (tables[match[1]] ||= []).push(...records);
    insert.lastIndex = i + 1;
  }
  return tables;
}

const camel = key => key.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase());
const mapRecord = (record, changes = {}) => ({
  ...Object.fromEntries(Object.entries(record).map(([key, value]) => [camel(key), value])),
  ...changes,
});
const tables = readTables();

const resultsByGameAndDate = new Map();
for (const row of [...(tables.old_results || []), ...(tables.game_results || [])]) {
  const date = row.result_date || row.created_at?.slice(0, 10);
  if (!date) continue;
  const result = mapRecord(row, { gameId: row.game_id, date });
  delete result.gameIdId;
  delete result.resultDate;
  resultsByGameAndDate.set(`${row.game_id}:${date}`, result);
}

const settings = Object.fromEntries((tables.settings || []).map(row => [row.key, row.value]));
const users = (tables.users || []).map(({ password, ...row }) =>
  mapRecord(row, { hasImportedPassword: Boolean(password) })
);

const data = {
  settings,
  games: (tables.games || []).map(row => mapRecord(row, {
    englishName: row.english_name,
    resultTime: row.result_time,
    otherChartId: row.other_chart_id,
    status: Boolean(row.status),
  })),
  extraGames: [],
  results: [...resultsByGameAndDate.values()].sort((a, b) => a.date.localeCompare(b.date) || a.gameId - b.gameId),
  faqs: (tables.faqs || []).map(mapRecord),
  questions: (tables.question_answers || []).map(row => mapRecord(row, { status: Boolean(row.status) })),
  blogs: (tables.blogs || []).map(row => mapRecord(row, {
    featuredImage: row.featured_image,
    shortDescription: row.short_description,
    metaTitle: row.meta_title,
    metaDescription: row.meta_description,
    metaKeywords: row.meta_keywords,
    canonicalUrl: row.canonical_url,
    published: Boolean(row.is_published),
  })),
  otherCharts: (tables.other_charts || []).map(row => mapRecord(row, {
    khaiwalName: row.khaiwal_name,
    whatsappNumbers: row.whatsapp_numbers,
    chartContent: row.chart_content,
  })),
  users,
};

fs.mkdirSync(path.dirname(destination), { recursive: true });
fs.writeFileSync(destination, `${JSON.stringify(data, null, 2)}\n`);

console.log(`Imported ${source} -> ${destination}`);
for (const [key, value] of Object.entries(data)) {
  console.log(`${key}: ${Array.isArray(value) ? value.length : Object.keys(value).length}`);
}
