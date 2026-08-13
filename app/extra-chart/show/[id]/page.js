import {notFound,redirect} from 'next/navigation';
import {getExtraGame} from '@/lib/extra-games';
import {getMainGame} from '@/lib/main-games';
import {gameSlug} from '@/lib/game-slug';
export const dynamic='force-dynamic';
export default async function ExtraGameChart({params,searchParams}){const {id}=await params,{source}=await searchParams;const data=source==='main'?await getMainGame(id):await getExtraGame(id);if(!data)notFound();redirect(`/chart/${gameSlug(data.game.name)}`)}
