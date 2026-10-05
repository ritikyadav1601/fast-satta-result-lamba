import { notFound, permanentRedirect } from 'next/navigation';
import { unstable_cache } from 'next/cache';
import SiteChrome from '@/components/SiteChrome';
import ChartYearSelect from '@/components/ChartYearSelect';
import { getMainGameBySlug } from '@/lib/main-games';
import { getExtraGameBySlug } from '@/lib/extra-games';
import {getGames,getGameResults} from '@/lib/store';
import {gameSlug,chartSlugAlias,legacySlugsFor} from '@/lib/game-slug';
import {ADMIN_GAME_IDS} from '@/lib/admin-primary';
import ChartFinder from '@/components/ChartFinder';
import {getChartFinderOptions} from '@/lib/chart-finder';

// Charts must combine every source: MONGO_URI (legacy games + admin-entered results), MAIN and EXTRA databases.
// Each game has ONE public URL: misspelled legacy slugs (gaziabad, disawar, shree-ganesh, …) 301 to the
// correct spelling, and the legacy game is found from the correct spelling via legacySlugsFor().
const mergeByDate=(...lists)=>{const merged=new Map();for(const list of lists)for(const row of list)if(row.result!=null&&String(row.result).trim()!=='')merged.set(row.date,row);return [...merged.values()]};

export const dynamic = 'force-dynamic';
const months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
const showResult = value => value == null ? '--' : String(value === 100 ? '00' : value).padStart(2, '0');

const dateTitle = () => {
  const now = new Date(), yesterday = new Date(now.getTime() - 86400000);
  const format = value => new Intl.DateTimeFormat('en-GB', {day:'2-digit',month:'long',year:'numeric',timeZone:'Asia/Kolkata'}).format(value).replace(/ (\d{4})$/, ', $1');
  return `Satta King Fast Result – ${format(now)} & ${format(yesterday)}`;
};

const findLegacyGame=(games,slug)=>{const slugs=legacySlugsFor(slug);return games.find(item=>item.status!==false&&slugs.some(s=>item.slug===s||gameSlug(item.englishName||item.english_name||item.name)===s))};

// Loading every result from three databases took 3-10 s per chart page, which slowed Googlebot
// ("Discovered - currently not indexed"). Cache the merged chart for 60 s per game.
const loadChart=unstable_cache(async slug=>{
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
},['chart-by-slug-v2'],{revalidate:60});

const yearNow=()=>Number(new Intl.DateTimeFormat('en',{year:'numeric',timeZone:'Asia/Kolkata'}).format(new Date()));
const ascii=v=>/^[\x00-\x7F]*$/.test(String(v||''))&&String(v||'').trim();
const displayName=(chart,slug)=>((chart&&(ascii(chart.englishName)||ascii(chart.name)))||slug.replace(/-/g,' ')).replace(/\b[a-z]/g,c=>c.toUpperCase());

export async function generateMetadata({params}){
  const slug=decodeURIComponent((await params).game_name);
  if(chartSlugAlias[slug])return {};
  const chart=await loadChart(slug).catch(()=>null);
  if(!chart)return {title:'Chart not found | Fast Satta Result',robots:{index:false}};
  const name=displayName(chart,slug),year=yearNow();
  const title=`${name} Satta Chart ${year} – Daily Result Record | Fast Satta Result`;
  const description=`${name} satta result chart ${year}: full day-by-day record of ${name} results by month, with previous years and today's latest update.`;
  return {title,description,alternates:{canonical:`/chart/${encodeURIComponent(slug)}`},openGraph:{title,description,type:'website',url:`/chart/${encodeURIComponent(slug)}`}};
}

export default async function NamedGameChart({params,searchParams}) {
  const {game_name:rawSlug}=await params,query=await searchParams;
  const slug=decodeURIComponent(rawSlug);
  if(chartSlugAlias[slug]){
    const requested=/^\d{4}$/.test(String(query?.year||''))?`?year=${query.year}`:'';
    permanentRedirect(`/chart/${encodeURIComponent(chartSlugAlias[slug])}${requested}`);
  }
  const [chart,finder]=await Promise.all([loadChart(slug),getChartFinderOptions()]);
  if(!chart)notFound();
  const years=[...new Set(chart.rows.map(([date])=>Number(date.slice(0,4))).filter(Boolean))].sort((a,b)=>b-a);
  const requested=Number(query?.year),year=years.includes(requested)?requested:years[0];
  const results=new Map(chart.rows.filter(([date])=>Number(date.slice(0,4))===year));
  const href=`/chart/${slug}`;
  const name=displayName(chart,slug);
  return <SiteChrome active="chart"><div className="chart-date-band"><p>{dateTitle()}</p></div><main className="year-chart-page"><section className="year-chart-header"><h1>{chart.name} YEARLY CHART</h1>{years.length?<ChartYearSelect href={href} years={years} value={year}/>:null}</section><ChartFinder {...finder} game={slug} year={year} heading={false}/>{years.length?<div className="year-table-scroll"><table className="year-result-table"><caption className="sr-only">{name} satta result chart {year}</caption><thead><tr><th aria-label="Day"></th>{months.map(month=><th key={month}>{month}</th>)}</tr></thead><tbody>{Array.from({length:31},(_,index)=>index+1).map(day=><tr key={day}><th scope="row">{day}</th>{months.map((month,monthIndex)=>{const date=`${year}-${String(monthIndex+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;return <td key={month}>{showResult(results.get(date))}</td>})}</tr>)}</tbody></table></div>:<div className="empty-state">No results are available for this game.</div>}<section className="chart-seo-text"><h2>{name} Satta Chart {year}</h2><p>This page shows the complete {name} satta result record for {year}, arranged day by day for every month. Each cell is the declared {name} result for that date; “--” means no result was published. Use the year selector or the chart picker above to open older {name} charts, and see the <a href="/chart">full Satta King chart list</a> for other games.</p></section></main></SiteChrome>;
}
