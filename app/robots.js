const siteUrl='https://www.fast-satta-result.com';

export default function robots(){return {rules:[{userAgent:'*',allow:'/',disallow:['/admin/','/result-admin/','/api/']}],sitemap:`${siteUrl}/sitemap.xml`,host:siteUrl}}
