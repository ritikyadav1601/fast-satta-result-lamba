import {NextResponse} from 'next/server';
import {cookies} from 'next/headers';
import {isRestrictedAdminSession} from '@/lib/admin-auth';
import {RESTRICTED_ADMIN_GAME_IDS,restrictedAdminData,saveRestrictedAdminResult} from '@/lib/admin-primary';

async function allowed(){return isRestrictedAdminSession((await cookies()).get('fsk_result_admin')?.value)}
const unavailable=error=>NextResponse.json({error:error.message||'Primary database is unavailable.'},{status:503});

export async function GET(request){
  if(!await allowed())return NextResponse.json({error:'Unauthorized'},{status:401});
  try{return NextResponse.json(await restrictedAdminData(new URL(request.url).searchParams.get('date')))}catch(error){return unavailable(error)}
}

export async function POST(request){
  if(!await allowed())return NextResponse.json({error:'Unauthorized'},{status:401});
  const {item}=await request.json();
  if(!RESTRICTED_ADMIN_GAME_IDS.includes(Number(item?.gameId)))return NextResponse.json({error:'Only Prem Nagar and Jammu City results can be updated.'},{status:403});
  try{return NextResponse.json(await saveRestrictedAdminResult(item))}catch(error){return unavailable(error)}
}
