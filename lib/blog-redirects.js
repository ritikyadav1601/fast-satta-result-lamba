// Old blog URLs that permanently redirect to a newer post. Used by next.config.mjs (redirects)
// and by the blog list/sitemap so redirected URLs are never linked or submitted to Google.
export const blogRedirects = [
  {source:'/blog/satta-king-weekly-result-chart-2025-full-game-records-predictions',destination:'/blog/satta-king-weekly-result-chart-2025-top-bazar-analysis',permanent:true},
  {source:'/blog/top-satta-result-sites-2025-gali-faridabad-desawar',destination:'/blog/latest-satta-result-2025-gali-faridabad-desawar',permanent:true},
  {source:'/blog/fast-satta-result-gali-disawar-today-2025-live-chart',destination:'/blog/fast-satta-result-today-gali-desawar-faridabad-live',permanent:true},
];
export const redirectedBlogSlugs = new Set(blogRedirects.map(item => item.source.replace('/blog/', '')));
