import { database } from './mongodb';

export const ADMIN_GAME_IDS = [24, 1, 3, 25, 31, 34, 30];

export async function adminPrimaryData(date) {
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const resultFilter = { gameId: { $in: ADMIN_GAME_IDS } };
  if (/^\d{4}-\d{2}-\d{2}$/.test(date || '')) resultFilter.date = date;
  const [games, results, otherCharts, settings] = await Promise.all([
    db.collection('games').find({ id: { $in: ADMIN_GAME_IDS } }, { projection: { _id: 0 } }).toArray(),
    db.collection('results').find(resultFilter, { projection: { _id: 0 } }).sort({ gameId: 1 }).toArray(),
    db.collection('otherCharts').find({}, { projection: { _id: 0 } }).toArray(),
    db.collection('settings').findOne({_id:'settings'}),
  ]);
  return { games: games.sort((a, b) => ADMIN_GAME_IDS.indexOf(a.id) - ADMIN_GAME_IDS.indexOf(b.id)), results, otherCharts, settings:settings?Object.fromEntries(Object.entries(settings).filter(([key])=>key!=='_id')):{} };
}

export async function saveFirstKhaiwal(item) {
  const db=await database();
  if(!db)throw new Error('Primary MongoDB is not configured.');
  const khaiwal_name=String(item.khaiwalName||'').trim(),whatsapp_number=String(item.whatsappNumbers||'').trim();
  if(!khaiwal_name||!whatsapp_number)throw new Error('Enter both the Khaiwal name and WhatsApp number.');
  await db.collection('settings').updateOne({_id:'settings'},{$set:{khaiwal_name,whatsapp_number}},{upsert:true});
  return {khaiwal_name,whatsapp_number};
}

export async function saveAdminResult(item) {
  const gameId = Number(item.gameId);
  const date = String(item.date || '');
  const result = String(item.result || '').trim();
  if (!ADMIN_GAME_IDS.includes(gameId) || !/^\d{4}-\d{2}-\d{2}$/.test(date) || !/^\d{1,3}$/.test(result)) throw new Error('Invalid game, date, or result.');
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const value = { gameId, date, result, id: item.id || crypto.randomUUID() };
  await db.collection('results').updateOne({ gameId, date }, { $set: value }, { upsert: true });
  return value;
}

export async function saveKhaiwalChart(item) {
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const value = {
    id: item.id || 5,
    khaiwalName: String(item.khaiwalName || '').trim(),
    whatsappNumbers: String(item.whatsappNumbers || '').trim(),
  };
  if (!value.khaiwalName || !value.whatsappNumbers) throw new Error('Enter both the Khaiwal name and WhatsApp number.');
  await db.collection('otherCharts').updateOne({ id: value.id }, { $set: value }, { upsert: true });
  return value;
}
