import Link from 'next/link';
import LiveClock from './LiveClock';
import { displayDate, today } from '@/lib/store';
import { gameSlug } from '@/lib/game-slug';

const resultFor=(results,game,date)=>{const value=results.find(r=>String(r.gameId)===String(game.id)&&r.date===date)?.result;return value===100?'00':value??'--'};
const dateKey=value=>new Intl.DateTimeFormat('en-CA',{timeZone:'Asia/Kolkata'}).format(value);
const timeLabel=value=>value||new Date().toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit',hour12:true,timeZone:'Asia/Kolkata'});
const gameTime=value=>{if(!value)return '--';const match=String(value).match(/^(\d{1,2}):(\d{2})/);if(!match)return value;const hour=Number(match[1]),minute=match[2];return `${String(hour%12||12).padStart(2,'0')}:${minute} ${hour<12?'AM':'PM'}`};
const minutes=value=>{const [hour='0',minute='0']=String(value||'00:00').split(':');return Number(hour)*60+Number(minute)};
const byResultTime=(a,b)=>minutes(a.resultTime||a.result_time||a.time)-minutes(b.resultTime||b.result_time||b.time);
const orderedGames=games=>[...games].sort(byResultTime);
const isDesawar=game=>/desa[wv]ar/i.test(game.englishName||game.english_name||game.name);
const isSecondaryUpper=game=>/^(prem nagar|jaamu city)$/i.test(game.englishName||game.english_name||'');
const chartHref=game=>`/chart/${gameSlug(game.englishName||game.english_name||game.name)}`;
const monthlyChartOrder=['delhi bazar','shree ganesh','faridabad','ghaziabad','gali','desawar'];
const monthlyChartName=game=>String(game.englishName||game.english_name||game.name||'').trim().toLowerCase().replace(/[^a-z]+/g,' ').trim().replace('delhi bazaar','delhi bazar').replace('shri ganesh','shree ganesh').replace('gaziabad','ghaziabad').replace(/desa[wv]ar/,'desawar');
const hasResult=(results,game,date)=>results.some(row=>String(row.gameId)===String(game.id)&&row.date===date&&row.result!=null&&row.result!=='');
const circleGames=(games,results,date)=>{const eligible=orderedGames(games.filter(game=>!isDesawar(game)));const declared=eligible.filter(game=>hasResult(results,game,date));const lastDeclared=declared.at(-1);const start=lastDeclared?eligible.findIndex(game=>String(game.id)===String(lastDeclared.id))+1:0;let waiting=eligible.slice(start).filter(game=>!hasResult(results,game,date)).slice(0,2);if(waiting.length<2)waiting=[...waiting,...eligible.slice(0,start).filter(game=>!hasResult(results,game,date)&&!waiting.some(item=>String(item.id)===String(game.id))).slice(0,2-waiting.length)];return lastDeclared?[lastDeclared,...waiting]:waiting};
const khaiwalGames=[
  ['जयपुर मटका','12:45 PM'],
  ['सदर बाजार','1:25 PM'],
  ['ग्वालियर','2:25 PM'],
  ['दिल्ली बाजार','3:00 PM'],
  ['श्री गणेश','4:20 PM'],
  ['सूरत बाजार','5:25 PM'],
  ['फरीदाबाद','5:50 PM'],
  ['फरीदकोट','7:30 PM'],
  ['गाज़ियाबाद','8:50 PM'],
  ['गली','11:15 PM'],
  ['दिसावर','1:30 AM'],
];
const KhaiwalGameTimings=()=>khaiwalGames.map(([name,time])=><p key={name}><strong>⏰ {name} ------------ {time}</strong></p>);

export function Hero({settings,games,results,otherCharts=[]}){
  const now=new Date(),yesterday=new Date(now.getTime()-86400000),disawar=games.find(isDesawar);
  const topGames=circleGames(games,results,today());
  const todayResult=disawar?resultFor(results,disawar,today()):null;
  const yesterdayResult=disawar?resultFor(results,disawar,dateKey(yesterday)):'--';
  return <>
    <section className="sattalogo"><div className="container"><div className="row"><div className="col-md-12 text-center"><div className="homepage-date-title">Satta King Fast Result – {displayDate(now)} &amp; {displayDate(yesterday)}</div></div></div></div></section>
    <section className="circlebox"><div className="container"><div className="row"><div className="col-md-12 text-center"><div className="liveresult"><LiveClock/><p className="hintext" style={{padding:0}}>हा भाई यही आती हे सबसे पहले खबर रूको और देखो</p></div></div></div></div></section>
    {topGames.length>0&&<section className="circlebox2">{topGames.map((game,index)=><div className={index===0&&hasResult(results,game,today())?'circle-game-declared':'circle-game-waiting'} key={game.id}><div className="sattaname"><p style={{margin:0}}>{game.name}</p></div><div className="sattaname"><p style={{margin:0,padding:0}}>{resultFor(results,game,today())}</p></div></div>)}</section>}
    <div className="wrapper-yellow"><section className="sattadividerr"><div className="container"><div className="col-md-12 text-center" style={{paddingBottom:15}}><h4 style={{fontSize:24,fontWeight:'normal',textTransform:'uppercase',margin:0,padding:0}}>Disawar</h4><p style={{fontSize:18,fontWeight:400}}>{timeLabel(disawar?.resultTime||disawar?.result_time)}</p><strong style={{fontSize:20,letterSpacing:2}}>{yesterdayResult} <img src="/assets/img/arrow.gif" alt="arrow icon" height="30" width="30" style={{marginLeft:5,marginRight:5}}/> {todayResult??<img src="/assets/img/wait.gif" alt="wait icon" width="30" height="30"/>}</strong></div></div></section></div>
    <ChannelCards/>
    <TimingCard settings={settings} games={games}/>
    {otherCharts.slice(0,1).map(chart=><SecondKhaiwalCard chart={chart} games={games} key={chart.id}/>)}
  </>;
}

function ChannelCards(){return <>
  <div className="column-ad card-body promo-gradient"><p>🙏🏿नमस्कार साथियो 🙏🏿</p><p>सुपरफास्ट रिजल्ट के लिए हमारे व्हाट्सप्प चैनल से जुड़े चैनल को follow करे&nbsp; &nbsp;</p><p><a href="https://whatsapp.com/channel/0029Vb9zF4R7NoZycbDiKC25"><img src="/assets/img/whatsapp.png" alt="Whatsapp to show game on this website"/></a></p></div>
  <div className="channel-panel"><div className="row"><div className="card-body channel-yellow"><p>अब टेलीग्राम के PLAYERS भी जल्दी रेिजल्ट पाने के लिए हमारे टेलीग्राम के चैनल को JOIN करे और SUPERFAST रेिजल्ट पाए</p><p><img src="/assets/img/tely.png" alt="Telegram" height="60" width="60"/></p></div></div></div>
  <div className="channel-panel"><div className="row"><div className="card-body channel-yellow"><p>FAST-SATTA-RESULT ( FAST-SATTA-RESULT ) UPDATES ALL SATTA GAMES ON REAL TIME EVERYDAY.</p></div></div></div>
  </>}

function TimingCard({settings}){const name=(settings.khaiwal_name||'').toUpperCase(),number=String(settings.whatsapp_number||'').trim(),whatsappNumber=number.replace(/\D/g,'');return <div className="column-ad"><div className="card-body promo-gradient"><p><strong>--सीधे सट्टा कंपनी का No 1 खाईवाल--</strong></p><p><strong>♕♕&nbsp; {name} &nbsp;BHAI&nbsp;KHAIWAL ♕♕</strong></p><KhaiwalGameTimings/><p><strong>💸 Payment Option 💸</strong><br/>PAYTM//BANK TRANSFER//PHONE PAY//GOOGLE PAY<br/>=====================================<br/>=====================================</p><p><strong>🤑 Rate list 💸</strong><br/><strong>जोड़ी रेट 10-------960</strong><br/><strong>हरूफ रेट 100-----960</strong></p><p>♕♕ &nbsp;<strong>{name} BHAI KHAIWAL &nbsp;</strong>♕♕</p>{number&&<p className="khaiwal-number"><strong>{number}</strong></p>}<p><strong>Game play करने के लिये नीचे लिंक पर क्लिक करे</strong></p>{whatsappNumber&&<p><a href={`https://wa.me/${whatsappNumber}`} aria-label={`Chat with ${name} on WhatsApp`}><img src="/assets/img/whatsapp.png" width="200" height="69" alt="WhatsApp"/></a></p>}</div></div>}

function SecondKhaiwalCard({chart}){const name=String(chart.khaiwalName||'').trim().toUpperCase(),number=String(chart.whatsappNumbers||'').trim(),whatsappNumber=number.replace(/\D/g,'');return <div className="column-ad second-khaiwal-card"><div className="card-body promo-gradient"><p><strong>--सीधे सट्टा कंपनी का No 1 खाईवाल--</strong></p><p><strong>♕♕&nbsp; {name} &nbsp;♕♕</strong></p><KhaiwalGameTimings/><p><strong>💸 Payment Option 💸</strong><br/>PAYTM // BANK TRANSFER // PHONE PAY // GOOGLE PAY<br/>=====================================</p><p><strong>🤑 Rate list 💸</strong><br/><strong>जोड़ी रेट 10-------960</strong><br/><strong>हरूफ रेट 100-----960</strong></p><p><strong>♕♕ &nbsp;{name}&nbsp; ♕♕</strong></p>{number&&<p className="khaiwal-number"><strong>{number}</strong></p>}<p><strong>Game play करने के लिये नीचे लिंक पर क्लिक करे</strong></p>{whatsappNumber&&<p><a href={`https://wa.me/${whatsappNumber}`} aria-label={`Chat with ${name} on WhatsApp`}><img src="/assets/img/whatsapp.png" width="200" height="69" alt="WhatsApp"/></a></p>}</div></div>}

export function ResultsAndCharts({settings,games,results}){const now=new Date();const sorted=orderedGames(games);const gamesByMonthlyName=new Map(games.map(game=>[monthlyChartName(game),game]));const chartGames=monthlyChartOrder.map(name=>gamesByMonthlyName.get(name)).filter(Boolean);const days=Array.from({length:now.getDate()},(_,i)=>({label:String(i+1).padStart(2,'0')+'-'+String(now.getMonth()+1).padStart(2,'0'),date:`${now.getFullYear()}-${String(now.getMonth()+1).padStart(2,'0')}-${String(i+1).padStart(2,'0')}`}));return <>
  <section className="octoberresultchart"><div className="container"><div className="row"><div className="col-md-12 text-center"><h3>Satta Chart, Faridabad Satta, Ghaziabad Result</h3></div></div></div></section>
  {games.length>0&&<>{[sorted.filter(g=>!isDesawar(g)&&!isSecondaryUpper(g)),sorted.filter(isSecondaryUpper)].map((group,index)=>group.length?<section className="tablebox1" style={{margin:'0 0 5px'}} key={index}><div className="table-responsive"><table className="table table-bordered"><thead className="forblack"><tr><th>सट्टा का नाम</th><th>कल आया था</th><th>आज का रिज़ल्ट</th></tr></thead><tbody>{group.map(g=><tr key={g.id}><td className="foryellow"><Link className="gamenameeach" href={chartHref(g)}><strong>{g.name}</strong></Link><br/><strong>{gameTime(g.resultTime||g.result_time||g.time)}</strong></td><td className="yesterday-number"><strong>{resultFor(results,g,dateKey(new Date(now.getTime()-86400000)))}</strong></td><td className="today-number"><strong>{resultFor(results,g,today())}</strong></td></tr>)}</tbody></table></div></section>:null)}</>}
  <div className="column-ad card-body promo-gradient"><p>🙏🏿नमस्कार साथियो 🙏🏿</p><p>किसी भी तरह की कोई शिकायत के लिए कंपनी के मैनेजर से संपर्क करे &nbsp; &nbsp;</p><p>----{(settings.owner_name||'').toUpperCase()} ----</p><p><a href={`https://wa.me/${settings.owner_number||''}`}><img src="/assets/img/whatsapp.png" alt="WhatsApp"/></a></p><p>NOTE: &nbsp; इस नंबर पर लीक गेम नही मिलता गेम लेने वाले भाई कॉल या मैसेज न करें।</p><p>किसी भी भाई को किसी भी तरह की कोई शिकायत या परेशानी हो तो हमसे telegram पर संपर्क करे</p><p><a href={`https://t.me/${settings.owner_number||''}`}><img src="/assets/img/tel.webp" alt="Telegram link"/></a></p></div>
  <section className="octoberresultchart"><div className="result-chart-title">Fast Satta Result – Live {now.getFullYear()} Updates</div></section><section className="octoberresultchart"><h2>Today Satta Result, Gali Satta King, Desawar Satta King, Fast Satta King</h2></section><section className="octoberresultchart"><h3>{now.getFullYear()} {now.toLocaleString('en-US',{month:'long'}).toUpperCase()} RESULT CHART</h3></section>
  <section className="newtable"><div className="table-responsive marginBottom"><table className="table table-bordered table-extra"><thead><tr><td className="table_chart_section_01 forfirtcolor date text-center"><strong className="fon">Date</strong></td>{chartGames.map(g=><td className="table_chart_section forfirtcolor text-center" key={g.id}>{g.name.toUpperCase()}</td>)}</tr></thead><tbody>{days.map(day=><tr key={day.date}><td className="forfirtcolor text-center"><span className="fon">{day.label}</span></td>{chartGames.map(g=><td key={g.id}><span className="table_chart_section_02">{resultFor(results,g,day.date)}</span></td>)}</tr>)}</tbody></table></div></section>
  </>}
