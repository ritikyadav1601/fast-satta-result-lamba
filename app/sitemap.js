import {getGames,getBlogList} from '@/lib/store';
import {gameSlug} from '@/lib/game-slug';

const siteUrl='https://www.fast-satta-result.com';
const page=(path,priority,changeFrequency='weekly')=>({url:`${siteUrl}${path}`,lastModified:new Date(),changeFrequency,priority});

export default async function sitemap(){
  const [games,blogs]=await Promise.all([getGames(),getBlogList()]);
  const staticPages=[page('/',1,'daily'),page('/chart',.9,'daily'),page('/blogs',.7,'weekly'),page('/contact',.5,'monthly'),page('/disclaimer',.4,'yearly'),page('/privacy',.4,'yearly'),page('/terms-and-conditions',.4,'yearly')];
  const chartPages=(games||[]).filter(game=>game.status!==false).map(game=>page(`/chart/${encodeURIComponent(game.slug||gameSlug(game.englishName||game.english_name||game.name))}`,.8,'daily'));
  const blogPages=(blogs||[]).filter(blog=>blog.published!==false&&blog.slug).map(blog=>({...page(`/blog/${encodeURIComponent(blog.slug)}`,.6,'monthly'),lastModified:new Date(blog.updatedAt||blog.createdAt||Date.now())}));
  return [...staticPages,...chartPages,...blogPages];
}
