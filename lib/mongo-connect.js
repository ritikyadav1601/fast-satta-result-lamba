import dns from 'node:dns';
import { MongoClient } from 'mongodb';

const fallbackDnsServers = (process.env.MONGO_DNS_SERVERS || '1.1.1.1,8.8.8.8')
  .split(',')
  .map(value => value.trim())
  .filter(Boolean);

function isRefusedSrvLookup(error) {
  for (let current = error; current; current = current.cause) {
    if (current.code === 'ECONNREFUSED' && /querySrv/i.test(current.message || '')) return true;
  }
  return false;
}

export async function connectMongo(uri, options) {
  let client = new MongoClient(uri, options);
  try {
    await client.connect();
    return client;
  } catch (error) {
    await client.close().catch(() => {});
    if (!isRefusedSrvLookup(error) || !fallbackDnsServers.length) throw error;

    // Some hosting/local DNS resolvers refuse Atlas SRV queries even though
    // ordinary DNS works. Retry only that failure through explicit resolvers.
    dns.setServers(fallbackDnsServers);
    client = new MongoClient(uri, options);
    try {
      await client.connect();
      return client;
    } catch (retryError) {
      await client.close().catch(() => {});
      throw retryError;
    }
  }
}
