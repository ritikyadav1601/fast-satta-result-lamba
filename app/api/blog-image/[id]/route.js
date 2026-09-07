import { database } from '@/lib/mongodb';

export const dynamic = 'force-dynamic';

export async function GET(_request, { params }) {
  const { id } = await params;
  const values = [id];
  if (/^\d+$/.test(id)) values.push(Number(id));
  const db = await database();
  if (!db) return new Response('Image unavailable', { status: 503 });
  const blog = await db.collection('blogs').findOne({ id: { $in: values } }, { projection: { featuredImage: 1, published: 1, isPublished: 1 } });
  if (!blog || blog.published === false || blog.isPublished === 0) return new Response('Image not found', { status: 404 });
  const match = String(blog.featuredImage || '').match(/^data:(image\/(?:png|jpeg|webp|gif));base64,([\s\S]+)$/i);
  if (!match) return new Response('Image not found', { status: 404 });
  const bytes = Buffer.from(match[2], 'base64');
  return new Response(bytes, { headers: { 'Content-Type': match[1].toLowerCase(), 'Content-Length': String(bytes.length), 'Cache-Control': 'public, max-age=3600, stale-while-revalidate=86400', 'X-Content-Type-Options': 'nosniff' } });
}
