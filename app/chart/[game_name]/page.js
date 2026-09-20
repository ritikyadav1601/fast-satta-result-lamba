import { notFound } from 'next/navigation';
import SiteChrome from '@/components/SiteChrome';
import ChartYearSelect from '@/components/ChartYearSelect';
import { getMainGameBySlug } from '@/lib/main-games';
import { getExtraGameBySlug } from '@/lib/extra-games';
import {getData} from '@/lib/store';
import {gameSlug} from '@/lib/game-slug';
import {ADMIN_GAME_IDS} from '@/lib/admin-primary';

// Charts must combine every source: MONGO_URI (legacy games + admin-entered results), MAIN and EXTRA databases.
// Legacy slugs differ from the MAIN/EXTRA database names for a few games.
const slugAlias={'shree-ganesh':'shri-ganesh','gaziabad':'ghaziabad','disawar':'desawar','surt-bazar':'surat-bazar','jaamu-city':'jammu-city','gwallior':'gwalior','फरीदकोट':'faridkot'};
const mergeByDate=(...lists)=>{const merged=new Map();for(const list of lists)for(const row of list)if(row.result!=null&&String(row.result).trim()!=='')merged.set(row.date,row);return [...merged.values()]};

export const dynamic = 'force-dynamic';
const months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
const showResult = value => value == null ? '--' : String(value === 100 ? '00' : value).padStart(2, '0');

const dateTitle = () => {
  const now = new Date(), yesterday = new Date(now.getTime() - 86400000);
  const format = value => new Intl.DateTimeFormat('en-GB', {day:'2-digit',month:'long',year:'numeric',timeZone:'Asia/Kolkata'}).format(value).replace(/ (\d{4})$/, ', $1');
  return `Satta King Fast Result – ${format(now)} & ${format(yesterday)}`;
};

export default async function NamedGameChart({params,searchParams}) {
  const {game_name:rawSlug}=await params,query=await searchParams;
  const slug=decodeURIComponent(rawSlug);
  const legacy=await getData();
  const legacyGame=legacy.games.find(item=>item.status!==false&&(item.slug===slug||gameSlug(item.englishName||item.english_name||item.name)===slug));
  const legacyRows=legacyGame?legacy.results.filter(row=>String(row.gameId)===String(legacyGame.id)):[];
  let external=null;
  for(const candidate of [slug,slugAlias[slug]].filter(Boolean)){
    external=await getMainGameBySlug(candidate).catch(()=>null)||await getExtraGameBySlug(candidate).catch(()=>null);
    if(external)break;
  }
  let data=null;
  if(external&&legacyGame){
    // Games managed in the admin panel keep MONGO_URI as the source of truth; otherwise the newer MAIN/EXTRA data wins.
    const adminGame=ADMIN_GAME_IDS.includes(Number(legacyGame.id));
    data={game:slugAlias[slug]?{...external.game,name:legacyGame.name}:external.game,results:adminGame?mergeByDate(external.results,legacyRows):mergeByDate(legacyRows,external.results)};
  }else if(external)data=external;
  else if(legacyGame)data={game:legacyGame,results:legacyRows};
  if(!data)notFound();
  const years=[...new Set(data.results.map(row=>Number(String(row.date).slice(0,4))).filter(Boolean))].sort((a,b)=>b-a);
  const requested=Number(query?.year),year=years.includes(requested)?requested:years[0];
  const results=new Map(data.results.filter(row=>Number(String(row.date).slice(0,4))===year).map(row=>[row.date,row.result]));
  const href=`/chart/${slug}`;
  return <SiteChrome active="chart"><div className="chart-date-band"><h1>{dateTitle()}</h1></div><main className="year-chart-page"><section className="year-chart-header"><h1>{data.game.name} YEARLY CHART</h1>{years.length?<ChartYearSelect href={href} years={years} value={year}/>:null}</section>{years.length?<div className="year-table-scroll"><table className="year-result-table"><thead><tr><th aria-label="Day"></th>{months.map(month=><th key={month}>{month}</th>)}</tr></thead><tbody>{Array.from({length:31},(_,index)=>index+1).map(day=><tr key={day}><th scope="row">{day}</th>{months.map((month,monthIndex)=>{const date=`${year}-${String(monthIndex+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;return <td key={month}>{showResult(results.get(date))}</td>})}</tr>)}</tbody></table></div>:<div className="empty-state">No results are available for this game.</div>}</main></SiteChrome>;
}
