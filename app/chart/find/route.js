import { NextResponse } from 'next/server';
import { canonicalChartSlug } from '@/lib/game-slug';

// Target of the ChartFinder form: /chart/find?game=gali&year=2026 -> /chart/gali?year=2026
export function GET(request) {
  const url = new URL(request.url);
  const game = String(url.searchParams.get('game') || '').trim().toLowerCase();
  const year = String(url.searchParams.get('year') || '').trim();
  if (!/^[\p{L}\p{N}-]{1,80}$/u.test(game)) return NextResponse.redirect(new URL('/chart', url), 303);
  const target = new URL(`/chart/${encodeURIComponent(canonicalChartSlug(game))}`, url);
  if (/^\d{4}$/.test(year)) target.searchParams.set('year', year);
  return NextResponse.redirect(target, 303);
}
