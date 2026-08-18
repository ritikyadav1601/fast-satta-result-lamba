import {NextResponse} from 'next/server';
import {timingSafeEqual} from 'node:crypto';
import {restrictedAdminSessionToken} from '@/lib/admin-auth';

const equal=(provided,expected)=>{const left=Buffer.from(String(provided||'')),right=Buffer.from(String(expected||''));return left.length===right.length&&timingSafeEqual(left,right)};

export async function POST(request){
  const configuredUsername=process.env.RESTRICTED_ADMIN_USERNAME;
  const configuredPassword=process.env.RESTRICTED_ADMIN_PASSWORD;
  const token=await restrictedAdminSessionToken();
  if(!configuredUsername||!configuredPassword||!token)return NextResponse.json({error:'Restricted admin login is not configured.'},{status:503});
  const {username,password}=await request.json();
  if(!equal(username,configuredUsername)||!equal(password,configuredPassword))return NextResponse.json({error:'Invalid credentials'},{status:401});
  const response=NextResponse.json({success:true});
  response.cookies.set('fsk_result_admin',token,{httpOnly:true,secure:process.env.NODE_ENV==='production',sameSite:'strict',maxAge:28800,path:'/'});
  return response;
}

export async function DELETE(){
  const response=NextResponse.json({success:true});
  response.cookies.set('fsk_result_admin','',{httpOnly:true,secure:process.env.NODE_ENV==='production',sameSite:'strict',maxAge:0,path:'/'});
  return response;
}
