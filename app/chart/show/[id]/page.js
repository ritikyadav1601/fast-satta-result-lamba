import {notFound,redirect} from 'next/navigation';
import {getMainGame} from '@/lib/main-games';
import {getData} from '@/lib/store';
import {gameSlug} from '@/lib/game-slug';

export const dynamic='force-dynamic';

export default async function LegacyGameChart({params,searchParams}){
  const {id}=await params,query=await searchParams;
  const data=query?.source==='legacy'
    ? await (async()=>{const legacy=await getData(),game=legacy.games.find(item=>String(item.id)===String(id));return game?{game}:null})()
    : await getMainGame(id);
  if(!data)notFound();
  const year=query?.year?`?year=${query.year}`:'';
  redirect(`/chart/${gameSlug(data.game.englishName||data.game.english_name||data.game.name)}${year}`);
}
