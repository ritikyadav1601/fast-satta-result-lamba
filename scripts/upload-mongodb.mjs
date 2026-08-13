import fs from 'node:fs';
import { MongoClient } from 'mongodb';

function envValue(name) {
  if (process.env[name]) return process.env[name];
  for (const file of ['.env.local', '.env', '.env.example']) {
    if (!fs.existsSync(file)) continue;
    const line = fs.readFileSync(file, 'utf8').split(/\r?\n/).find(value => value.startsWith(`${name}=`));
    if (line) return line.slice(name.length + 1).trim();
  }
}

const uri = envValue('MONGO_URI');
const databaseName = envValue('MONGO_DB') || 'fast_satta_result';
if (!uri) throw new Error('MONGO_URI is not configured');

const data = JSON.parse(fs.readFileSync('data/site.json', 'utf8'));
const client = new MongoClient(uri);
await client.connect();
const db = client.db(databaseName);

for (const [name, value] of Object.entries(data)) {
  const collection = db.collection(name);
  await collection.deleteMany({});
  const documents = Array.isArray(value) ? value : [{ _id: 'settings', ...value }];
  if (documents.length) await collection.insertMany(documents);
  console.log(`${name}: ${await collection.countDocuments()} uploaded`);
}

await db.collection('results').createIndex({ gameId: 1, date: 1 }, { unique: true });
await db.collection('games').createIndex({ id: 1 }, { unique: true });

const sourceKeys = new Set(data.results.map(row => `${row.gameId}:${row.date}`));
const mongoResults = await db.collection('results').find({}, { projection: { gameId: 1, date: 1 } }).toArray();
const mongoKeys = new Set(mongoResults.map(row => `${row.gameId}:${row.date}`));
const missing = [...sourceKeys].filter(key => !mongoKeys.has(key));
const extra = [...mongoKeys].filter(key => !sourceKeys.has(key));

console.log(`database: ${databaseName}`);
console.log(`results verified: ${mongoKeys.size}/${sourceKeys.size}`);
console.log(`missing: ${missing.length}, extra: ${extra.length}`);
await client.close();
if (missing.length || extra.length) process.exitCode = 1;
