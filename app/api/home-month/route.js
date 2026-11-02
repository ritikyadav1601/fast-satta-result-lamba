import { NextResponse } from 'next/server';
import { loadChart } from '@/lib/chart-data';

// Homepage "Last Month / Next Month" buttons:
// GET /api/home-month?month=2026-10&slugs=gali,desawar,faridabad
// -> { month: "2026-10", results: { gali: { "2026-10-01": "45", ... }, ... } }
export const dynamic = 'force-dynamic';

export async function GET(request) {
  const url = new URL(request.url);
  const month = String(url.searchParams.get('month') || '');
  if (!/^\d{4}-(0[1-9]|1[0-2])$/.test(month)) return NextResponse.json({ error: 'Invalid month' }, { status: 400 });
  const slugs = [...new Set(String(url.searchParams.get('slugs') || '').split(',').map(s => s.trim().toLowerCase()).filter(s => /^[\p{L}\p{N}-]{1,80}$/u.test(s)))].slice(0, 40);
  const entries = await Promise.all(slugs.map(async slug => {
    const chart = await loadChart(slug).catch(() => null);
    const rows = {};
    for (const [date, result] of chart?.rows || []) {
      if (date.startsWith(month)) rows[date.slice(0, 10)] = result === 100 || result === '100' ? '00' : String(result);
    }
    return [slug, rows];
  }));
  return NextResponse.json({ month, results: Object.fromEntries(entries) }, { headers: { 'Cache-Control': 'public, s-maxage=60, stale-while-revalidate=300' } });
}
