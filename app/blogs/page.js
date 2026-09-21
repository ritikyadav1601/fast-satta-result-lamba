import SiteChrome from '@/components/SiteChrome';
import BlogCards from '@/components/BlogCards';
import { getBlogList } from '@/lib/store';

export const revalidate = 300;
export const metadata = { title: 'Latest Blogs | Fast Satta Result', description: 'Read the latest updates, guides, and result information from Fast Satta Result.', alternates: { canonical: '/blogs' } };

export default async function Blogs() {
  const ordered = await getBlogList();
  return <SiteChrome active="blogs"><main className="content-page blogs-page"><header className="blog-page-header"><p>News &amp; guides</p><h1>Latest Blogs</h1><span>Helpful updates and information from Fast Satta Result.</span></header><BlogCards blogs={ordered} /></main></SiteChrome>;
}
