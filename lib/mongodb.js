import { MongoClient } from 'mongodb';

const uri = process.env.MONGO_URI;
const databaseName = process.env.MONGO_DB || 'fast_satta_result';
const state = globalThis.__fskMongo ??= { promise: null };

const options = {
  appName: 'fast-satta-result',
  serverSelectionTimeoutMS: 6000,
  connectTimeoutMS: 6000,
  socketTimeoutMS: 30000,
  maxPoolSize: 10,
  minPoolSize: 0,
  maxIdleTimeMS: 60000,
  retryReads: true,
  retryWrites: true,
};

const wait = milliseconds => new Promise(resolve => setTimeout(resolve, milliseconds));

async function connect() {
  const client = new MongoClient(uri, options);
  try {
    await client.connect();
    await client.db(databaseName).command({ ping: 1 });
    return client;
  } catch (error) {
    await client.close().catch(() => {});
    throw error;
  }
}

async function client() {
  if (!uri) return null;

  for (let attempt = 1; attempt <= 2; attempt++) {
    if (!state.promise) state.promise = connect();
    try {
      return await state.promise;
    } catch (error) {
      // A rejected singleton otherwise remains cached until the process restarts.
      state.promise = null;
      if (attempt === 2) {
        const wrapped = new Error('MongoDB is temporarily unavailable. Check the Atlas Network Access allowlist and try again.', { cause: error });
        wrapped.name = 'DatabaseConnectionError';
        throw wrapped;
      }
      await wait(attempt * 500);
    }
  }
}

export async function database() {
  const connection = await client();
  return connection?.db(databaseName) ?? null;
}

export async function databaseHealth() {
  const db = await database();
  if (!db) return { configured: false, connected: false };
  await db.command({ ping: 1 });
  return { configured: true, connected: true, database: databaseName };
}
