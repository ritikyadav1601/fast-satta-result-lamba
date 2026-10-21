import {blogRedirects} from './lib/blog-redirects.js';
/** @type {import('next').NextConfig} */
const securityHeaders=[
  {key:'X-Content-Type-Options',value:'nosniff'},
  {key:'X-Frame-Options',value:'SAMEORIGIN'},
  {key:'Referrer-Policy',value:'strict-origin-when-cross-origin'},
  {key:'Permissions-Policy',value:'camera=(), microphone=(), geolocation=()'},
];
// Google found /ghaziabad and /shri-ganesh (no /chart/ prefix) as 404s. Send the main games' bare slugs,
// and their old misspellings, to the one chart URL for that game.
const bareGameRedirects=Object.entries({
  'gali':'gali','desawar':'desawar','disawar':'desawar','faridabad':'faridabad','ghaziabad':'ghaziabad','gaziabad':'ghaziabad',
  'shri-ganesh':'shri-ganesh','shree-ganesh':'shri-ganesh','delhi-bazar':'delhi-bazar',
}).map(([from,to])=>({source:`/${from}`,destination:`/chart/${to}`,permanent:true}));
const nextConfig = { output:'standalone',outputFileTracingRoot:process.cwd(),poweredByHeader:false,compress:true,experimental:{inlineCss:true},async headers(){return [{source:'/:path*',headers:securityHeaders}]},async redirects(){return [...blogRedirects,...bareGameRedirects]} };
export default nextConfig;
