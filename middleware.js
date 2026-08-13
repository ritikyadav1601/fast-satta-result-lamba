import {NextResponse} from 'next/server';
export function middleware(req){if(req.nextUrl.pathname==='/admin/login')return NextResponse.next();if(req.cookies.get('fsk_admin')?.value!=='authenticated')return NextResponse.redirect(new URL('/admin/login',req.url));return NextResponse.next()}
export const config={matcher:['/admin/:path*']};
