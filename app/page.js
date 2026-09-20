import SiteChrome from '@/components/SiteChrome';
import { Hero, ResultsAndCharts } from '@/components/HomeSections';
import HomepageSeoContent from '@/components/HomepageSeoContent';
import { getHomepageData } from '@/lib/store';
import { getExtraGamesData } from '@/lib/extra-games';
import ExtraGamesResults from '@/components/ExtraGamesResults';
import {getSplitMainGames} from '@/lib/main-games';
import { HomepageBlogs } from '@/components/BlogCards';

// Results can change throughout the day, but rendering this page for every
// visitor opens three separate MongoDB connections. ISR keeps updates prompt
// while preventing those connections from slowing down every page view.
export const revalidate = 30;

const optionalData = (request, fallback, timeout = 20000) =>
  Promise.race([
    request,
    new Promise(resolve => setTimeout(() => resolve(fallback), timeout)),
  ]);
const dynamicDate=()=>new Intl.DateTimeFormat('en-GB',{day:'2-digit',month:'long',year:'numeric',timeZone:'Asia/Kolkata'}).format(new Date());
export function generateMetadata(){const date=dynamicDate();return {title:`Satta Result Today ${date} | Fast Satta Result Live`,description:`Get the latest Fast Satta Result Today (${date}) live. Access real-time Satta Result Live records, and track historical Satta King Chart data instantly.`,keywords:['Satta Result Today','Fast Satta Result','Satta Result Live','Satta King Result','Satta King Chart','Gali Result','Desawar Result','Faridabad Result','Ghaziabad Result'],authors:[{name:'Fast Satta Result'}],creator:'Fast Satta Result',publisher:'Fast Satta Result',robots:{index:true,follow:true,googleBot:{index:true,follow:true}},alternates:{canonical:'https://www.fast-satta-result.com/'}}}
const normalize=value=>String(value||'').trim().toLowerCase().replace(/[^a-z]+/g,' ').trim().replace('desawer','desawar');
// MONGO_URI results (saved from the admin panel) override MAIN_GAMES_MONGO_URI results for the same game and date.
const mergeResults=(base,override)=>{const merged=new Map(base.map(row=>[row.date,row]));override.forEach(row=>{if(row.result!==undefined&&row.result!==null&&String(row.result).trim()!=='')merged.set(row.date,row)});return [...merged.values()]};
export default async function Home() { const [data,extra,split]=await Promise.all([getHomepageData(),optionalData(getExtraGamesData(),{games:[],results:[]}),optionalData(getSplitMainGames(),{games:[],results:[],otherGames:[],otherResults:[]})]);const legacyUpper=data.games.filter(game=>game.status!==false&&normalize(game.englishName||game.english_name)!=='disawar');const newMainByName=new Map(split.games.map(game=>[normalize(game.name),game]));const results=[];const games=legacyUpper.map(legacy=>{const key=normalize(legacy.englishName||legacy.english_name),fresh=newMainByName.get(key.replace('shree ganesh','shri ganesh').replace('gaziabad','ghaziabad'));if(!fresh){results.push(...data.results.filter(row=>String(row.gameId)===String(legacy.id)));return {...legacy,source:'legacy'}}results.push(...mergeResults(split.results.filter(row=>String(row.gameId)===String(fresh.id)),data.results.filter(row=>String(row.gameId)===String(legacy.id)).map(row=>({...row,gameId:fresh.id}))));return {...fresh,name:legacy.name,englishName:key,resultTime:legacy.resultTime||legacy.result_time,time:legacy.time,source:'main'}});const desawar=split.games.find(game=>normalize(game.name)==='desawar')||data.games.find(game=>normalize(game.englishName||game.english_name)==='disawar');if(desawar){games.push({...desawar,source:desawar.source||'legacy'});const legacyDesawar=data.games.find(game=>normalize(game.englishName||game.english_name)==='disawar');if(desawar.source==='main'){results.push(...mergeResults(split.results.filter(row=>String(row.gameId)===String(desawar.id)),legacyDesawar?data.results.filter(row=>String(row.gameId)===String(legacyDesawar.id)).map(row=>({...row,gameId:desawar.id})):[]))}else{results.push(...data.results.filter(row=>String(row.gameId)===String(desawar.id)))}}const mainData={...data,games,results};const extraData={games:[...split.otherGames,...extra.games],results:[...split.otherResults,...extra.results]};return <SiteChrome active="home" settings={data.settings}><Hero {...mainData}/><ResultsAndCharts {...mainData}/><ExtraGamesResults {...extraData}/><HomepageSeoContent date={dynamicDate()}/><HomepageBlogs blogs={data.blogs}/></SiteChrome> }
