import Link from 'next/link';
import { blogImage, isPublishedBlog } from '@/lib/blog-content';

export default function BlogCards({ blogs = [], homepage = false }) {
  const posts = blogs.filter(isPublishedBlog);
  if (!posts.length) return homepage ? null : <div className="empty-state">No blog posts have been published.</div>;
  return <div className="blog-grid">{posts.map(blog => {
    const image = blogImage(blog.featuredImage, blog.id);
    return <article className="blog-card" key={blog.id || blog.slug}>
      {image && <Link className="blog-card-image" href={`/blog/${blog.slug}`} aria-label={`Read ${blog.title}`}><img src={image} alt="" loading="lazy" /></Link>}
      <div className="blog-card-body"><h2><Link href={`/blog/${blog.slug}`}>{blog.title}</Link></h2>{blog.shortDescription && <p>{blog.shortDescription}</p>}<Link className="blog-read-more" href={`/blog/${blog.slug}`}>Read article <span aria-hidden="true">→</span></Link></div>
    </article>;
  })}</div>;
}

export function HomepageBlogs({ blogs = [] }) {
  if (!blogs.some(isPublishedBlog)) return null;
  return <section className="home-blogs"><div className="home-blogs-inner"><div className="section-heading"><div><p className="section-eyebrow">Latest updates</p><h2>From Our Blog</h2></div><Link href="/blogs">View all blogs</Link></div><BlogCards blogs={blogs.slice(0, 3)} homepage /></div></section>;
}
