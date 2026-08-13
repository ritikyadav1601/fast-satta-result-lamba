import Link from 'next/link';
import { getData } from '@/lib/store';

export function Header({ active = 'home', settings }) {
  return <section className="topboxnew"><div className="container-fluid"><div className="col-md-16 nopadding">
    <nav className="newnav"><ul>
      {[['home','/','Home'],['chart','/chart','Chart'],['contact','/contact','Contact'],['login','/admin/login','Login']].map(([key,href,label]) => <li key={key}><Link className={active===key?'active':''} href={href}>{label}</Link></li>)}
    </ul><div className="clearfix" /></nav>
    <div className="text_slide"><marquee style={{color:'#fff'}}><p style={{fontSize:16,textAlign:'center'}}>{active==='home' ? settings.home_page_float_text : settings.secondary_page_float_text}</p></marquee></div>
  </div></div></section>;
}

export function Footer({ settings }) {
  return <footer><section className="somelinks"><Link className="yellow-link mx-4" href="/privacy">Privacy Policy</Link><Link className="yellow-link" href="/terms-and-conditions">Terms &amp; Conditions</Link><Link className="yellow-link mx-4" href="/blogs">Blogs</Link></section>
    <section className="somelinks2"><div className="container"><div className="row"><div className="col-md-12 text-center"><strong>©️ 2024 {settings.website_name} All Rights Reserved</strong></div></div></div></section>
    <section className="somelinks"><div className="container"><div className="row"><div className="col-md-12 text-center"><ul><li style={{color:'#ffd800',padding:0,fontWeight:700}}>!! DISCLAIMER - {settings.disclaimer}</li></ul></div></div></div></section></footer>;
}

export default async function SiteChrome({ children, active }) { const {settings}=await getData(); return <><Header active={active} settings={settings}/>{children}<Footer settings={settings}/></>; }
