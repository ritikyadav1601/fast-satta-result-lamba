import {blogRedirects} from './lib/blog-redirects.js';
/** @type {import('next').NextConfig} */
const securityHeaders=[
  {key:'X-Content-Type-Options',value:'nosniff'},
  {key:'X-Frame-Options',value:'SAMEORIGIN'},
  {key:'Referrer-Policy',value:'strict-origin-when-cross-origin'},
  {key:'Permissions-Policy',value:'camera=(), microphone=(), geolocation=()'},
];
const nextConfig = { output:'standalone',outputFileTracingRoot:process.cwd(),poweredByHeader:false,compress:true,experimental:{inlineCss:true},async headers(){return [{source:'/:path*',headers:securityHeaders}]},async redirects(){return blogRedirects} };
export default nextConfig;
