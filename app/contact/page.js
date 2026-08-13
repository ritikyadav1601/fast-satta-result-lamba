import SiteChrome from '@/components/SiteChrome'; import {getData} from '@/lib/store';
export const dynamic='force-dynamic';
export default async function Contact(){const {settings:s}=await getData();return <SiteChrome active="contact"><main className="content-page"><h1>Contact Us</h1><p>For website questions or corrections, contact the site administrator.</p>{s.owner_name&&<p><strong>{s.owner_name}</strong><br/>{s.owner_number}</p>}</main></SiteChrome>}
