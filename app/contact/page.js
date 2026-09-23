import SiteChrome from '@/components/SiteChrome'; import {getSettings} from '@/lib/store';
export const dynamic='force-dynamic';
export const metadata={title:'Contact Us | Fast Satta Result',description:'Get in touch with Fast Satta Result for website questions or corrections.',alternates:{canonical:'/contact'}};
export default async function Contact(){const s=await getSettings();return <SiteChrome active="contact"><main className="content-page"><h1>Contact Us</h1><p>For website questions or corrections, contact the site administrator.</p>{s.owner_name&&<p><strong>{s.owner_name}</strong><br/>{s.owner_number}</p>}</main></SiteChrome>}
