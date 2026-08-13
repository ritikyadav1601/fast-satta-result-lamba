import Link from 'next/link';
import { today } from '@/lib/store';
import { gameSlug } from '@/lib/game-slug';

const dateKey=value=>new Intl.DateTimeFormat('en-CA',{timeZone:'Asia/Kolkata'}).format(value);
const showResult=value=>value==null||value===''?'--':String(value).padStart(2,'0');
const showTime=value=>{if(!value)return '--';const [hour,minute]=value.split(':').map(Number);return new Intl.DateTimeFormat('en-US',{hour:'2-digit',minute:'2-digit',hour12:true,timeZone:'UTC'}).format(new Date(Date.UTC(2000,0,1,hour,minute)))};

export default function ExtraGamesResults({games,results}) {
  if(!games.length)return null;
  const currentDate=today(),yesterday=dateKey(new Date(Date.now()-86400000));
  const values=new Map(results.map(row=>[`${row.gameId}:${row.date||row.resultDate}`,row.result]));
  return <section className="tablebox1 extra-results-section" style={{margin:'0 0 5px'}}><div className="table-responsive"><table className="table table-bordered"><thead className="forblack"><tr><th>सट्टा का नाम</th><th>कल आया था</th><th>आज का रिज़ल्ट</th></tr></thead><tbody>{games.map(game=>{const href=`/chart/${gameSlug(game.name)}`;return <tr key={`${game.source||'extra'}-${game.id}`}><td className="foryellow"><Link className="gamenameeach" href={href}><strong>{game.name}</strong></Link><br/><strong>{showTime(game.resultTime)}</strong></td><td className="yesterday-number"><strong>{showResult(values.get(`${game.id}:${yesterday}`))}</strong></td><td className="today-number"><strong>{showResult(values.get(`${game.id}:${currentDate}`))}</strong></td></tr>})}</tbody></table></div></section>;
}
