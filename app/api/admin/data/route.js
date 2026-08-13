import { NextResponse } from 'next/server';
import { cookies } from 'next/headers';
import { adminPrimaryData, saveAdminResult, saveFirstKhaiwal, saveKhaiwalChart } from '@/lib/admin-primary';
import {isAdminSession} from '@/lib/admin-auth';

async function allowed() { return isAdminSession((await cookies()).get('fsk_admin')?.value); }
const unavailable = error => NextResponse.json({ error: error.message || 'Primary database is unavailable.' }, { status: 503 });

export async function GET(request) {
  if (!await allowed()) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  try { return NextResponse.json(await adminPrimaryData(new URL(request.url).searchParams.get('date'))); } catch (error) { return unavailable(error); }
}

export async function POST(request) {
  if (!await allowed()) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  const { collection, item } = await request.json();
  try {
    if (collection === 'results') return NextResponse.json(await saveAdminResult(item));
    if (collection === 'khaiwal1') return NextResponse.json(await saveFirstKhaiwal(item));
    if (collection === 'otherCharts') return NextResponse.json(await saveKhaiwalChart(item));
    return NextResponse.json({ error: 'This admin panel only manages approved results and Khaiwal charts.' }, { status: 403 });
  } catch (error) { return unavailable(error); }
}
