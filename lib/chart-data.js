import { unstable_cache } from 'next/cache';
import { getMainGameBySlug } from '@/lib/main-games';
import { getExtraGameBySlug } from '@/lib/extra-games';
import { getGames, getGameResults } from '@/lib/store';
import { gameSlug, legacySlugsFor } from '@/lib/game-slug';
import { ADMIN_GAME_IDS } from '@/lib/admin-primary';

// Shared by /chart/[game] and the homepage month switcher (/api/home-month).
// Combines every source: MONGO_URI (legacy games + admin-entered results), MAIN and EXTRA databases.
const mergeByDate=(...lists)=>{const merged=new Map();for(const list of lists)for(const row of list)if(row.result!=null&&String(row.result).trim()!=='')merged.set(row.date,row);return [...merged.values()]};

const findLegacyGame=(games,slug)=>{const slugs=legacySlugsFor(slug);return games.find(item=>item.status!==false&&slugs.some(s=>item.slug===s||gameSlug(item.englishName||item.english_name||item.name)===s))};

// Loading every result from three databases took 3-10 s per chart page, which slowed Googlebot
// ("Discovered - currently not indexed"). Cache the merged chart for 60 s per game.
export const loadChart=unstable_cache(async slug=>{
  const legacyGame=findLegacyGame(await getGames(),slug);
  const legacyRows=legacyGame?await getGameResults(legacyGame.id):[];
  let external=null;
  for(const candidate of legacySlugsFor(slug)){
    external=await getMainGameBySlug(candidate).catch(()=>null)||await getExtraGameBySlug(candidate).catch(()=>null);
    if(external)break;
  }
  let data=null;
  if(external&&legacyGame){
    // Games managed in the admin panel keep MONGO_URI as the source of truth; otherwise the newer MAIN/EXTRA data wins.
    const adminGame=ADMIN_GAME_IDS.includes(Number(legacyGame.id));
    data={name:legacySlugsFor(slug).length>1?legacyGame.name:external.game.name,results:adminGame?mergeByDate(external.results,legacyRows):mergeByDate(legacyRows,external.results)};
  }else if(external)data={name:external.game.name,results:external.results};
  else if(legacyGame)data={name:legacyGame.name,results:legacyRows};
  if(!data)return null;
  return {name:data.name,englishName:legacyGame?.englishName||legacyGame?.english_name||'',rows:data.results.filter(row=>row.result!=null&&String(row.result).trim()!=='').map(row=>[String(row.date),row.result])};
},['chart-by-slug-v3'],{revalidate:60});
