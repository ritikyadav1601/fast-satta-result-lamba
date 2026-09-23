/** @type {import('next').NextConfig} */
const securityHeaders=[
  {key:'X-Content-Type-Options',value:'nosniff'},
  {key:'X-Frame-Options',value:'SAMEORIGIN'},
  {key:'Referrer-Policy',value:'strict-origin-when-cross-origin'},
  {key:'Permissions-Policy',value:'camera=(), microphone=(), geolocation=()'},
];
const blogRedirects=[
  {source:'/blog/satta-king-weekly-result-chart-2025-full-game-records-predictions',destination:'/blog/satta-king-weekly-result-chart-2025-top-bazar-analysis',permanent:true},
  {source:'/blog/top-satta-result-sites-2025-gali-faridabad-desawar',destination:'/blog/latest-satta-result-2025-gali-faridabad-desawar',permanent:true},
  {source:'/blog/fast-satta-result-gali-disawar-today-2025-live-chart',destination:'/blog/fast-satta-result-today-gali-desawar-faridabad-live',permanent:true},
];
const nextConfig = { output:'standalone',outputFileTracingRoot:process.cwd(),poweredByHeader:false,compress:true,async headers(){return [{source:'/:path*',headers:securityHeaders}]},async redirects(){return blogRedirects} };
export default nextConfig;
