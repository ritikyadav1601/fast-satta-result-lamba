import fs from 'node:fs';
import path from 'node:path';
import { database } from './mongodb';

const file = path.join(process.cwd(), 'data', 'site.json');

export async function getData() {
  try {
    const db = await database();
    if (db) {
      const names = ['games','extraGames','results','faqs','questions','blogs','otherCharts','users'];
      const [settings, ...collections] = await Promise.all([
        db.collection('settings').findOne({ _id: 'settings' }),
        ...names.map(name => db.collection(name).find({}, { projection: { _id: 0 } }).toArray()),
      ]);
      return { settings: settings ? Object.fromEntries(Object.entries(settings).filter(([key]) => key !== '_id')) : {}, ...Object.fromEntries(names.map((name, index) => [name, collections[index]])) };
    }
  } catch (error) {
    console.error('Primary MongoDB unavailable; using local data fallback.', error.name);
  }
  try { return JSON.parse(fs.readFileSync(file, 'utf8')); }
  catch { return { settings: {}, games: [], extraGames: [], results: [], faqs: [], questions: [], blogs: [], otherCharts: [], users: [] }; }
}

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
