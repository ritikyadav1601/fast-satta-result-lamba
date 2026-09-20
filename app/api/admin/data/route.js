import { NextResponse } from 'next/server';
import { revalidatePath } from 'next/cache';
import { cookies } from 'next/headers';
import { adminBlog, adminPrimaryData, deleteAdminBlog, deleteAdminResult, saveAdminBlog, saveAdminResult, saveFirstKhaiwal } from '@/lib/admin-primary';
import {isAdminSession} from '@/lib/admin-auth';

// Publish saved changes immediately instead of waiting for the homepage cache to expire.
const refreshSite = () => { for (const path of ['/', '/blogs', '/chart']) { try { revalidatePath(path); } catch {} } try { revalidatePath('/blog/[slug]', 'page'); } catch {} };
const done = async work => { const value = await work; refreshSite(); return NextResponse.json(value); };
async function allowed() { return isAdminSession((await cookies()).get('fsk_admin')?.value); }
const unavailable = error => NextResponse.json({ error: error.message || 'Primary database is unavailable.' }, { status: 503 });

export async function GET(request) {
  if (!await allowed()) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  const params = new URL(request.url).searchParams;
  try { if (params.get('blog')) return NextResponse.json(await adminBlog(params.get('blog'))); return NextResponse.json(await adminPrimaryData(params.get('date'))); } catch (error) { return unavailable(error); }
}

export async function POST(request) {
  if (!await allowed()) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  const { collection, item } = await request.json();
  try {
    if (collection === 'results') return done(saveAdminResult(item));
    if (collection === 'khaiwal1') return done(saveFirstKhaiwal(item));
    if (collection === 'blogs') return done(saveAdminBlog(item));
    return NextResponse.json({ error: 'This admin panel only manages approved results and Khaiwal settings.' }, { status: 403 });
  } catch (error) { return unavailable(error); }
}

export async function DELETE(request) {
  if (!await allowed()) return NextResponse.json({ error: 'Unauthorized' }, { status: 401 });
  const { collection, id, item } = await request.json();
  try {
    if (collection === 'results') return done(deleteAdminResult(item));
    if (collection === 'blogs' && id != null) return done(deleteAdminBlog(id));
    return NextResponse.json({ error: 'Only game results and blog posts can be deleted here.' }, { status: 403 });
  } catch (error) { return unavailable(error); }
}
