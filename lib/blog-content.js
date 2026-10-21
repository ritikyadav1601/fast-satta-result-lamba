export const isPublishedBlog = blog => blog?.published !== false && blog?.isPublished !== 0;

export function blogImage(value, id) {
  const image = String(value || '').trim();
  if (!image) return null;
  if (image.startsWith('data:image/') && id != null) return `/api/blog-image/${encodeURIComponent(id)}`;
  if (/^https?:\/\//i.test(image) || image.startsWith('/')) return image;
  return `/${image}`;
}

// The page already renders the post title as <h1>; an <h1> inside the body would be a second one.
export const blogBodyHtml = value => safeBlogHtml(value).replace(/<(\/?)h1\b/gi, '<$1h2');

export function safeBlogHtml(value) {
  return String(value || '')
    .replace(/<(script|style|iframe|object|embed|form)[^>]*>[\s\S]*?<\/\1\s*>/gi, '')
    .replace(/<(script|style|iframe|object|embed|form)\b[^>]*\/?\s*>/gi, '')
    .replace(/\s+on\w+\s*=\s*("[^"]*"|'[^']*'|[^\s>]+)/gi, '')
    .replace(/\s+(href|src)\s*=\s*(["'])\s*javascript:[\s\S]*?\2/gi, ' $1="#"');
}
