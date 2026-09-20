import fs from 'node:fs';
import path from 'node:path';
import { cache } from 'react';
import { database } from './mongodb';

const file = path.join(process.cwd(), 'data', 'site.json');

async function loadData() {
  try {
    const db = await database();
    if (db) {
      const names = ['games','extraGames','results','faqs','questions','blogs','otherCharts','users'];
      const [settings, ...collections] = await Promise.all([
        db.collection('settings').findOne({ _id: 'settings' }),
        ...names.map(name => name === 'blogs'
          // Skip the multi-MB base64 blog images (served by /api/blog-image/[id]); loading them timed out and forced the stale fallback.
          ? db.collection('blogs').aggregate([
              { $addFields: { featuredImage: { $cond: [{ $regexMatch: { input: { $ifNull: ['$featuredImage', ''] }, regex: '^data:image/' } }, 'data:image/stored', '$featuredImage'] } } },
              { $project: { _id: 0 } },
            ]).toArray()
          : db.collection(name).find({}, { projection: { _id: 0 } }).toArray()),
      ]);
      return { settings: settings ? Object.fromEntries(Object.entries(settings).filter(([key]) => key !== '_id')) : {}, ...Object.fromEntries(names.map((name, index) => [name, collections[index]])) };
    }
  } catch (error) {
    console.error('MONGO_URI FAILED; using stale data/site.json:', error.message, error.cause?.message || '');
  }
  try { return JSON.parse(fs.readFileSync(file, 'utf8')); }
  catch { return { settings: {}, games: [], extraGames: [], results: [], faqs: [], questions: [], blogs: [], otherCharts: [], users: [] }; }
}

// A page and its shared chrome often need the same data. React's request cache
// prevents those server components from issuing the same database query twice.
export const getData = cache(loadData);

export const getHomepageData = cache(async function getHomepageData() {
  const currentDate = today();
  const monthStart = `${currentDate.slice(0, 7)}-01`;
  try {
    const db = await database();
    if (db) {
      const [settings, games, results, blogs] = await Promise.all([
        db.collection('settings').findOne({ _id: 'settings' }),
        db.collection('games').find({}, { projection: { _id: 0 } }).toArray(),
        db.collection('results').find(
          { date: { $gte: monthStart, $lte: currentDate } },
          { projection: { _id: 0, id: 1, gameId: 1, date: 1, result: 1 } },
        ).toArray(),
        // Blog images are stored as base64 inside the documents (up to ~2.7MB each).
        // Fetching them here made this query time out and silently dropped ALL results,
        // so only send light fields; data: images are served by /api/blog-image/[id].
        db.collection('blogs').aggregate([
          { $match: { $or: [{ published: true }, { isPublished: 1 }] } },
          { $sort: { createdAt: -1 } },
          { $limit: 6 },
          { $project: {
            _id: 0, id: 1, slug: 1, title: 1, shortDescription: 1, published: 1, isPublished: 1, createdAt: 1,
            featuredImage: { $cond: [{ $regexMatch: { input: { $ifNull: ['$featuredImage', ''] }, regex: '^data:image/' } }, 'data:image/stored', '$featuredImage'] },
          } },
        ]).toArray().catch(error => { console.error('Homepage blogs query failed:', error.message); return []; }),
      ]);
      return {
        settings: settings ? Object.fromEntries(Object.entries(settings).filter(([key]) => key !== '_id')) : {},
        games,
        results,
        blogs,
      };
    }
  } catch (error) {
    console.error('MONGO_URI FAILED; homepage using stale data/site.json:', error.message, error.cause?.message || '');
  }

  try {
    const data = JSON.parse(fs.readFileSync(file, 'utf8'));
    return {
      settings: data.settings || {},
      games: data.games || [],
      results: (data.results || []).filter(row => row.date >= monthStart && row.date <= currentDate),
      blogs: (data.blogs || []).filter(row => row.published !== false && row.isPublished !== 0).slice(-6).reverse(),
    };
  } catch {
    return { settings: {}, games: [], results: [], blogs: [] };
  }
});

export async function saveData(data) {
  const db = await database();
  if (db) {
    const names = ['games','extraGames','results','faqs','questions','blogs','otherCharts','users'];
    await db.collection('settings').replaceOne({ _id: 'settings' }, { _id: 'settings', ...data.settings }, { upsert: true });
    for (const name of names) {
      await db.collection(name).deleteMany({});
      if (data[name]?.length) await db.collection(name).insertMany(data[name]);
    }
    return data;
  }
  fs.writeFileSync(file, JSON.stringify(data, null, 2));
  return data;
}

export function today() { return new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Kolkata' }).format(new Date()); }
export function displayDate(value = new Date()) { return new Intl.DateTimeFormat('en-GB', { day:'2-digit', month:'long', year:'numeric', timeZone:'Asia/Kolkata' }).format(value); }
