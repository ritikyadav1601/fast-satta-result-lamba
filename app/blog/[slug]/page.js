import {notFound} from 'next/navigation'; import SiteChrome from '@/components/SiteChrome'; import {getData} from '@/lib/store';
export const dynamic='force-dynamic';
export default async function Blog({params}){const {slug}=await params;const b=(await getData()).blogs.find(x=>x.slug===slug);if(!b)notFound();return <SiteChrome><article className="content-page"><h1>{b.title}</h1><p>{b.category}</p><div style={{whiteSpace:'pre-wrap'}}>{b.description}</div></article></SiteChrome>}
