import { cache } from 'react';
import { getGames } from './store';
import { database } from './mongodb';
import { gameSlug, chartSlugForGame } from './game-slug';
import { getMainGameList } from './main-games';
import { getExtraGameList } from './extra-games';

// Options for the "Satta Record Chart" picker (game + year -> /chart/[game]?year=).
// Every source is optional: a slow or missing database only removes its games from the list.
const withTimeout = (request, fallback, ms = 8000) => Promise.race([
  Promise.resolve(request).catch(() => fallback),
  new Promise(resolve => setTimeout(() => resolve(fallback), ms)),
]);

const currentYear = () => Number(new Intl.DateTimeFormat('en', { year: 'numeric', timeZone: 'Asia/Kolkata' }).format(new Date()));

async function oldestLegacyYear() {
  const db = await database();
  if (!db) return null;
  const row = await db.collection('results').find({ date: { $type: 'string', $gte: '2000' } }, { projection: { _id: 0, date: 1 } }).sort({ date: 1 }).limit(1).next();
  return row ? Number(String(row.date).slice(0, 4)) || null : null;
}

export const getChartFinderOptions = cache(async function getChartFinderOptions() {
  const [legacy, main, extra, firstYear] = await Promise.all([
    withTimeout(getGames(), []),
    withTimeout(getMainGameList(), []),
    withTimeout(getExtraGameList(), []),
    withTimeout(oldestLegacyYear(), null),
  ]);
  const games = [], seen = new Set();
  const add = (name, slug) => { if (!name || !slug || seen.has(slug)) return; seen.add(slug); games.push({ name: String(name).trim(), slug }); };
  legacy.filter(game => game.status !== false).forEach(game => {
    // Canonical slug; the chart page merges the MAIN/EXTRA copy of this game in, so it is listed once.
    add(game.name, chartSlugForGame(game));
  });
  [...main, ...extra].forEach(game => add(game.name, gameSlug(game.name)));

  const now = currentYear();
  const start = Math.max(Math.min(firstYear || now, now), now - 20);
  const years = Array.from({ length: now - start + 1 }, (_, index) => now - index);
  return { games, years };
});
