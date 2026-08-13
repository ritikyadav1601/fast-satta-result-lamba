import SiteChrome from '@/components/SiteChrome'; import {getData} from '@/lib/store';
export const dynamic='force-dynamic';
export default async function Disclaimer(){const {settings}=await getData();return <SiteChrome><main className="content-page"><h1>Disclaimer</h1><p>{settings.disclaimer}</p><p>This website publishes information only. It does not guarantee outcomes or encourage unlawful activity.</p></main></SiteChrome>}
