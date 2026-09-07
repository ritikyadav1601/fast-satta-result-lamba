import { notFound } from 'next/navigation';
import Link from 'next/link';
import SiteChrome from '@/components/SiteChrome';
import { blogImage, isPublishedBlog, safeBlogHtml } from '@/lib/blog-content';
import { getData } from '@/lib/store';

export const dynamic = 'force-dynamic';

async function findBlog(slug) {
  return (await getData()).blogs.find(blog => blog.slug === slug && isPublishedBlog(blog));
}

export async function generateMetadata({ params }) {
  const { slug } = await params, blog = await findBlog(slug);
  if (!blog) return {};
  const image = blogImage(blog.featuredImage, blog.id);
  return {
    title: blog.metaTitle || blog.title,
    description: blog.metaDescription || blog.shortDescription,
    keywords: blog.metaKeywords ? String(blog.metaKeywords).split(',').map(value => value.trim()).filter(Boolean) : undefined,
    alternates: { canonical: `/blog/${blog.slug}` },
    openGraph: { type: 'article', title: blog.metaTitle || blog.title, description: blog.metaDescription || blog.shortDescription, images: image ? [image] : undefined },
  };
}

export default async function Blog({ params }) {
  const { slug } = await params, blog = await findBlog(slug);
  if (!blog) notFound();
  const image = blogImage(blog.featuredImage, blog.id);
  return <SiteChrome><main className="blog-detail-page"><article><Link className="blog-back-link" href="/blogs">← All blogs</Link><header className="blog-detail-header"><h1>{blog.title}</h1>{blog.shortDescription && <p>{blog.shortDescription}</p>}</header>{image && <img className="blog-hero-image" src={image} alt={blog.title} />}<div className="blog-content" dangerouslySetInnerHTML={{ __html: safeBlogHtml(blog.description) }} /></article></main></SiteChrome>;
}
