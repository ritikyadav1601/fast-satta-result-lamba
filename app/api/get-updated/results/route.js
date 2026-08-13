import {NextResponse} from 'next/server'; import {getData,today} from '@/lib/store';
export const dynamic='force-dynamic';
export async function GET(){const d=await getData();return NextResponse.json({success:true,data:d.results.filter(r=>r.date===today())})}
