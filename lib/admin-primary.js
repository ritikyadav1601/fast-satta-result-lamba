import { database } from './mongodb';

export const ADMIN_GAME_IDS = [24, 1, 3, 25, 31, 34, 30];
export const RESTRICTED_ADMIN_GAME_IDS = [34, 30];

export async function adminPrimaryData(date) {
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const resultFilter = { gameId: { $in: ADMIN_GAME_IDS } };
  if (/^\d{4}-\d{2}-\d{2}$/.test(date || '')) resultFilter.date = date;
  const [games, results, otherCharts, blogs, settings] = await Promise.all([
    db.collection('games').find({ id: { $in: ADMIN_GAME_IDS } }, { projection: { _id: 0 } }).toArray(),
    db.collection('results').find(resultFilter, { projection: { _id: 0 } }).sort({ gameId: 1 }).toArray(),
    db.collection('otherCharts').find({}, { projection: { _id: 0 } }).toArray(),
    db.collection('blogs').find({}, { projection: { _id: 0 } }).sort({ createdAt: -1 }).toArray(),
    db.collection('settings').findOne({_id:'settings'}),
  ]);
  return { games: games.sort((a, b) => ADMIN_GAME_IDS.indexOf(a.id) - ADMIN_GAME_IDS.indexOf(b.id)), results, otherCharts, blogs, settings:settings?Object.fromEntries(Object.entries(settings).filter(([key])=>key!=='_id')):{} };
}

const blogSlug = value => String(value || '').trim().toLowerCase()
  .normalize('NFKD').replace(/[\u0300-\u036f]/g, '')
  .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 160);

export async function saveAdminBlog(item) {
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const title = String(item.title || '').trim();
  const description = String(item.description || '').trim();
  const slug = blogSlug(item.slug || title);
  if (!title || !description || !slug) throw new Error('Title, slug, and blog content are required.');
  const featuredImage = String(item.featuredImage || '').trim();
  if (featuredImage.length > 6 * 1024 * 1024) throw new Error('Featured image is too large.');
  if (featuredImage.startsWith('data:') && !/^data:image\/(png|jpeg|webp|gif);base64,/i.test(featuredImage)) throw new Error('Unsupported featured image format.');
  const id = item.id || crypto.randomUUID();
  const existingSlug = await db.collection('blogs').findOne({ slug, id: { $ne: id } }, { projection: { _id: 1 } });
  if (existingSlug) throw new Error('This slug is already used by another blog.');
  const now = new Date().toISOString();
  const value = {
    id,
    title,
    slug,
    description,
    shortDescription: String(item.shortDescription || '').trim(),
    featuredImage: featuredImage || null,
    metaTitle: String(item.metaTitle || '').trim() || title,
    metaDescription: String(item.metaDescription || '').trim(),
    metaKeywords: String(item.metaKeywords || '').trim(),
    published: item.published === true || item.published === 'true' || item.published === 'on',
    isPublished: item.published === true || item.published === 'true' || item.published === 'on' ? 1 : 0,
    createdAt: item.createdAt || now,
    updatedAt: now,
  };
  await db.collection('blogs').updateOne({ id }, { $set: value, $unset: { category: '', tags: '', canonicalUrl: '' } }, { upsert: true });
  return value;
}

export async function deleteAdminBlog(id) {
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const values = [id];
  if (/^\d+$/.test(String(id))) values.push(Number(id));
  const result = await db.collection('blogs').deleteOne({ id: { $in: values } });
  if (!result.deletedCount) throw new Error('Blog not found.');
  return { deleted: true, id };
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

export async function restrictedAdminData(date) {
  const db = await database();
  if (!db) throw new Error('Primary MongoDB is not configured.');
  const resultFilter = { gameId: { $in: RESTRICTED_ADMIN_GAME_IDS } };
  if (/^\d{4}-\d{2}-\d{2}$/.test(date || '')) resultFilter.date = date;
  const [games, results] = await Promise.all([
    db.collection('games').find({ id: { $in: RESTRICTED_ADMIN_GAME_IDS } }, { projection: { _id: 0 } }).toArray(),
    db.collection('results').find(resultFilter, { projection: { _id: 0 } }).sort({ gameId: 1 }).toArray(),
  ]);
  return { games: games.sort((a, b) => RESTRICTED_ADMIN_GAME_IDS.indexOf(a.id) - RESTRICTED_ADMIN_GAME_IDS.indexOf(b.id)), results };
}

export async function saveRestrictedAdminResult(item) {
  const gameId = Number(item.gameId);
  if (!RESTRICTED_ADMIN_GAME_IDS.includes(gameId)) throw new Error('Only Prem Nagar and Jammu City results can be updated.');
  return saveAdminResult(item);
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
