import {NextResponse} from 'next/server';
import {isAdminSession,isRestrictedAdminSession} from './lib/admin-auth';
export async function middleware(req){
  const restricted=req.nextUrl.pathname.startsWith('/result-admin');
  const login=restricted?'/result-admin/login':'/admin/login';
  const withNoindex=response=>{response.headers.set('X-Robots-Tag','noindex, nofollow');return response};
  if(req.nextUrl.pathname===login)return withNoindex(NextResponse.next());
  const allowed=restricted
    ?await isRestrictedAdminSession(req.cookies.get('fsk_result_admin')?.value)
    :await isAdminSession(req.cookies.get('fsk_admin')?.value);
  if(!allowed)return withNoindex(NextResponse.redirect(new URL(login,req.url)));
  return withNoindex(NextResponse.next());
}
export const config={matcher:['/admin/:path*','/result-admin/:path*']};
