import {NextResponse} from 'next/server';
import {isAdminSession,isRestrictedAdminSession} from './lib/admin-auth';
export async function middleware(req){
  const restricted=req.nextUrl.pathname.startsWith('/result-admin');
  const login=restricted?'/result-admin/login':'/admin/login';
  if(req.nextUrl.pathname===login)return NextResponse.next();
  const allowed=restricted
    ?await isRestrictedAdminSession(req.cookies.get('fsk_result_admin')?.value)
    :await isAdminSession(req.cookies.get('fsk_admin')?.value);
  if(!allowed)return NextResponse.redirect(new URL(login,req.url));
  return NextResponse.next();
}
export const config={matcher:['/admin/:path*','/result-admin/:path*']};
