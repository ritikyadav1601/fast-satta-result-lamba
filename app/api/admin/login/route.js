import {NextResponse} from 'next/server';
export async function POST(request){const {username,password}=await request.json();const valid=username===(process.env.ADMIN_USERNAME||'admin')&&password===(process.env.ADMIN_PASSWORD||'admin123');if(!valid)return NextResponse.json({error:'Invalid credentials'},{status:401});const r=NextResponse.json({success:true});r.cookies.set('fsk_admin','authenticated',{httpOnly:true,sameSite:'lax',maxAge:86400,path:'/'});return r}
export async function DELETE(){const r=NextResponse.json({success:true});r.cookies.delete('fsk_admin');return r}
