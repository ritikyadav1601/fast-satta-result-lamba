import {NextResponse} from 'next/server';
import {isAdminSession} from './lib/admin-auth';
export async function middleware(req){if(req.nextUrl.pathname==='/admin/login')return NextResponse.next();if(!await isAdminSession(req.cookies.get('fsk_admin')?.value))return NextResponse.redirect(new URL('/admin/login',req.url));return NextResponse.next()}
export const config={matcher:['/admin/:path*']};
