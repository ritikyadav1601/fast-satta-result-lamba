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

export const dynamic = 'force-dynamic';
const months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
const showResult = value => value == null ? '--' : String(value === 100 ? '00' : value).padStart(2, '0');

const dateTitle = () => {
  const now = new Date(), yesterday = new Date(now.getTime() - 86400000);
  const format = value => new Intl.DateTimeFormat('en-GB', {day:'2-digit',month:'long',year:'numeric',timeZone:'Asia/Kolkata'}).format(value).replace(/ (\d{4})$/, ', $1');
  return `Satta King Fast Result – ${format(now)} & ${format(yesterday)}`;
};

import {loadChart} from '@/lib/chart-data';

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
    const qs=new URLSearchParams();if(/^\d{4}$/.test(String(query?.year||'')))qs.set('year',query.year);if(/^(?:[1-9]|1[0-2])$/.test(String(query?.month||'')))qs.set('month',query.month);const requested=qs.toString()?`?${qs}`:'';
    permanentRedirect(`/chart/${encodeURIComponent(chartSlugAlias[slug])}${requested}`);
  }
  const [chart,finder]=await Promise.all([loadChart(slug),getChartFinderOptions()]);
  if(!chart)notFound();
  const years=[...new Set(chart.rows.map(([date])=>Number(date.slice(0,4))).filter(Boolean))].sort((a,b)=>b-a);
  const requested=Number(query?.year),year=years.includes(requested)?requested:years[0];
  const results=new Map(chart.rows.filter(([date])=>Number(date.slice(0,4))===year));
  const href=`/chart/${slug}`;
  const name=displayName(chart,slug);
  const monthNum=/^(?:[1-9]|1[0-2])$/.test(String(query?.month||''))?Number(query.month):0;
  if(monthNum){
    const monthName=new Date(Date.UTC(2000,monthNum-1,1)).toLocaleString('en-US',{month:'long',timeZone:'UTC'});
    const daysInMonth=new Date(Date.UTC(year,monthNum,0)).getUTCDate();
    const mm=String(monthNum).padStart(2,'0');
    return <SiteChrome active="chart"><div className="chart-date-band"><p>{dateTitle()}</p></div><main className="year-chart-page"><section className="year-chart-header"><h1>{chart.name} {monthName.toUpperCase()} {year} CHART</h1><a className="month-chart-full-link" href={`${href}?year=${year}`}>View full {year} chart</a></section><ChartFinder {...finder} game={slug} year={year} month={monthNum} heading={false}/>{years.length?<div className="month-table-wrap"><table className="year-result-table month-result-table"><caption className="sr-only">{name} satta result chart {monthName} {year}</caption><thead><tr><th>Date</th><th>{name}</th></tr></thead><tbody>{Array.from({length:daysInMonth},(_,index)=>index+1).map(day=>{const dd=String(day).padStart(2,'0');return <tr key={day}><th scope="row">{dd}-{mm}-{year}</th><td>{showResult(results.get(`${year}-${mm}-${dd}`))}</td></tr>})}</tbody></table></div>:<div className="empty-state">No results are available for this game.</div>}<section className="chart-seo-text"><h2>{name} Satta Chart {monthName} {year}</h2><p>This page shows the {name} satta result record for {monthName} {year}, day by day. “--” means no result was published for that date. Open the <a href={`${href}?year=${year}`}>full {year} {name} chart</a> or use the chart picker above to check another month, year or game.</p></section></main></SiteChrome>;
  }
  return <SiteChrome active="chart"><div className="chart-date-band"><p>{dateTitle()}</p></div><main className="year-chart-page"><section className="year-chart-header"><h1>{chart.name} YEARLY CHART</h1>{years.length?<ChartYearSelect href={href} years={years} value={year}/>:null}</section><ChartFinder {...finder} game={slug} year={year} heading={false}/>{years.length?<div className="year-table-scroll"><table className="year-result-table"><caption className="sr-only">{name} satta result chart {year}</caption><thead><tr><th aria-label="Day"></th>{months.map(month=><th key={month}>{month}</th>)}</tr></thead><tbody>{Array.from({length:31},(_,index)=>index+1).map(day=><tr key={day}><th scope="row">{day}</th>{months.map((month,monthIndex)=>{const date=`${year}-${String(monthIndex+1).padStart(2,'0')}-${String(day).padStart(2,'0')}`;return <td key={month}>{showResult(results.get(date))}</td>})}</tr>)}</tbody></table></div>:<div className="empty-state">No results are available for this game.</div>}<section className="chart-seo-text"><h2>{name} Satta Chart {year}</h2><p>This page shows the complete {name} satta result record for {year}, arranged day by day for every month. Each cell is the declared {name} result for that date; “--” means no result was published. Use the year selector or the chart picker above to open older {name} charts, and see the <a href="/chart">full Satta King chart list</a> for other games.</p></section></main></SiteChrome>;
}
