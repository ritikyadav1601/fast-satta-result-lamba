import fs from 'node:fs';
import { connectMongo } from '../lib/mongo-connect.js';

const env = Object.fromEntries(fs.readFileSync('.env', 'utf8').split(/\r?\n/).filter(l => l.includes('=') && !l.trim().startsWith('#')).map(l => { const i = l.indexOf('='); return [l.slice(0, i).trim(), l.slice(i + 1).trim().replace(/^["']|["']$/g, '')]; }));
const checks = [
  ['MONGO_URI', env.MONGO_URI, env.MONGO_DB || 'fast_satta_result', 'results', 'date'],
  ['MAIN_GAMES_MONGO_URI', env.MAIN_GAMES_MONGO_URI, undefined, 'gameresults', 'resultDate'],
  ['EXTRA_GAMES_MONGO_URI', env.EXTRA_GAMES_MONGO_URI, undefined, 'gameresults', 'resultDate'],
];
for (const [label, uri, dbName, coll, dateField] of checks) {
  if (!uri) { console.log(`${label}: NOT SET`); continue; }
  try {
    const client = await connectMongo(uri, { serverSelectionTimeoutMS: 8000 });
    const db = client.db(dbName);
    const games = await db.collection('games').countDocuments();
    const latest = await db.collection(coll).find({}).sort({ [dateField]: -1 }).limit(3).toArray();
    console.log(`${label}: CONNECTED db=${db.databaseName} games=${games} results=${await db.collection(coll).countDocuments()}`);
    console.log('  latest:', latest.map(r => `${r[dateField]}=${r.result}`).join(', '));
    await client.close();
  } catch (e) { console.log(`${label}: FAILED -> ${e.message}`); }
}
