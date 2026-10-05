import {notFound,permanentRedirect} from 'next/navigation';
import {getMainGame} from '@/lib/main-games';
import {getGames} from '@/lib/store';
import {chartSlugForGame} from '@/lib/game-slug';

export const dynamic='force-dynamic';

export default async function LegacyGameChart({params,searchParams}){
  const {id}=await params,query=await searchParams;
  const data=query?.source==='legacy'
    ? await (async()=>{const game=(await getGames()).find(item=>String(item.id)===String(id));return game?{game}:null})()
    : (await getMainGame(id).catch(()=>null))||await (async()=>{const game=(await getGames()).find(item=>String(item.id)===String(id));return game?{game}:null})();
  if(!data)notFound();
  const year=query?.year?`?year=${query.year}`:'';
  permanentRedirect(`/chart/${encodeURIComponent(chartSlugForGame(data.game))}${year}`);
}
