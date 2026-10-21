import {getGames,getBlogList} from '@/lib/store';
import {chartSlugForGame,gameSlug,canonicalChartSlug} from '@/lib/game-slug';
import {redirectedBlogSlugs} from '@/lib/blog-redirects';
import {getMainGameList} from '@/lib/main-games';
import {getExtraGameList} from '@/lib/extra-games';

const siteUrl='https://www.fast-satta-result.com';
const page=(path,priority,changeFrequency='weekly')=>({url:`${siteUrl}${path}`,lastModified:new Date(),changeFrequency,priority});
// A slow or unreachable database only drops its games from this sitemap run; it never breaks the sitemap.
const optional=(request,fallback,ms=10000)=>Promise.race([Promise.resolve(request).catch(()=>fallback),new Promise(resolve=>setTimeout(()=>resolve(fallback),ms))]);

export const revalidate=3600;

export default async function sitemap(){
  const [games,mainGames,extraGames,blogs]=await Promise.all([optional(getGames(),[]),optional(getMainGameList(),[]),optional(getExtraGameList(),[]),optional(getBlogList(),[])]);
  const staticPages=[page('/',1,'daily'),page('/chart',.9,'daily'),page('/blogs',.7,'weekly'),page('/contact',.5,'monthly'),page('/disclaimer',.4,'yearly'),page('/privacy',.4,'yearly'),page('/terms-and-conditions',.4,'yearly')];
  // Every game linked from the homepage has a chart page: legacy (MONGO_URI) games plus all active
  // MAIN and EXTRA games. Same slug rules as the homepage links, so each game appears once.
  const slugs=[
    ...(games||[]).filter(game=>game.status!==false).map(chartSlugForGame),
    ...[...(mainGames||[]),...(extraGames||[])].map(game=>canonicalChartSlug(gameSlug(game.name))),
  ].filter(Boolean);
  const chartPages=[...new Set(slugs)].map(slug=>page(`/chart/${encodeURIComponent(slug)}`,.8,'daily'));
  const blogPages=(blogs||[]).filter(blog=>blog.published!==false&&blog.slug&&!redirectedBlogSlugs.has(blog.slug)).map(blog=>({...page(`/blog/${encodeURIComponent(blog.slug)}`,.6,'monthly'),lastModified:new Date(blog.updatedAt||blog.createdAt||Date.now())}));
  return [...staticPages,...chartPages,...blogPages];
}
