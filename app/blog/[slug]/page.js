import { notFound } from 'next/navigation';
import Link from 'next/link';
import SiteChrome from '@/components/SiteChrome';
import { blogImage, isPublishedBlog, safeBlogHtml } from '@/lib/blog-content';
import { getBlogBySlug } from '@/lib/store';

export const revalidate = 300;

async function findBlog(slug) {
  const blog = await getBlogBySlug(slug);
  return blog && isPublishedBlog(blog) ? blog : null;
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
  const jsonLd = { '@context': 'https://schema.org', '@type': 'Article', headline: blog.title, description: blog.metaDescription || blog.shortDescription || undefined, image: image ? `https://www.fast-satta-result.com${image}` : undefined, datePublished: blog.createdAt || undefined, dateModified: blog.updatedAt || blog.createdAt || undefined, mainEntityOfPage: `https://www.fast-satta-result.com/blog/${blog.slug}`, author: { '@type': 'Organization', name: 'Fast Satta Result' }, publisher: { '@type': 'Organization', name: 'Fast Satta Result' } };
  return <SiteChrome><script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd).replace(/</g, '\\u003c') }} /><main className="blog-detail-page"><article><Link className="blog-back-link" href="/blogs">← All blogs</Link><header className="blog-detail-header"><h1>{blog.title}</h1>{blog.shortDescription && <p>{blog.shortDescription}</p>}</header>{image && <img className="blog-hero-image" src={image} alt={blog.title} />}<div className="blog-content" dangerouslySetInnerHTML={{ __html: safeBlogHtml(blog.description) }} /></article></main></SiteChrome>;
}
