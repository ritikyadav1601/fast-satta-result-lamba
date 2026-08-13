import { NextResponse } from 'next/server';
import { databaseHealth } from '@/lib/mongodb';

export const dynamic = 'force-dynamic';

export async function GET() {
  try {
    return NextResponse.json({ status: 'ok', mongodb: await databaseHealth() });
  } catch (error) {
    return NextResponse.json({
      status: 'error',
      mongodb: { configured: Boolean(process.env.MONGO_URI), connected: false },
      message: error.message,
    }, { status: 503 });
  }
}
