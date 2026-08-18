'use client';

import {useEffect,useState} from 'react';
import {useRouter} from 'next/navigation';

const indiaDate=()=>new Intl.DateTimeFormat('en-CA',{timeZone:'Asia/Kolkata'}).format(new Date());

export default function RestrictedAdminApp(){
  const router=useRouter();
  const [data,setData]=useState(null);
  const [date,setDate]=useState(indiaDate);
  const [editing,setEditing]=useState(null);
  const [message,setMessage]=useState('');
  async function load(selectedDate=date){
    const response=await fetch(`/api/result-admin/data?date=${selectedDate}`);
    const body=await response.json();
    if(response.status===401){router.replace('/result-admin/login');return}
    if(!response.ok)throw new Error(body.error||'Could not load results.');
    setData(body);
  }
  useEffect(()=>{load().catch(error=>setMessage(error.message))},[]);
  async function changeDate(event){const value=event.target.value;setDate(value);setEditing(null);setData(null);try{await load(value)}catch(error){setMessage(error.message)}}
  async function save(event){
    event.preventDefault();
    const form=event.currentTarget;
    const item={...(editing||{}),...Object.fromEntries(new FormData(form))};
    const response=await fetch('/api/result-admin/data',{method:'POST',headers:{'content-type':'application/json'},body:JSON.stringify({item})});
    const saved=await response.json();
    if(!response.ok){setMessage(saved.error||'Could not save.');return}
    setData(current=>({...current,results:current.results.some(row=>String(row.id)===String(saved.id)||Number(row.gameId)===Number(saved.gameId)&&row.date===saved.date)?current.results.map(row=>String(row.id)===String(saved.id)||Number(row.gameId)===Number(saved.gameId)&&row.date===saved.date?saved:row):[saved,...current.results]}));
    setEditing(null);form.reset();setMessage('Result saved.');
  }
  async function logout(){await fetch('/api/result-admin/login',{method:'DELETE'});router.push('/result-admin/login')}
  if(!data)return <div className="admin-main">{message||'Loading selected date…'}</div>;
  return <div className="admin-shell"><header className="admin-top"><strong>Prem Nagar &amp; Jammu City Results Admin</strong><button className="primary-button" onClick={logout}>Logout</button></header><main className="admin-main"><div className="admin-card"><label className="admin-date-filter">Show results for <input type="date" value={date} onChange={changeDate}/></label><p>This account can update only Prem Nagar and Jammu City.</p></div><div className="admin-card"><h1>Game Results</h1><form className="admin-form" onSubmit={save} key={editing?.id||date}><select name="gameId" defaultValue={editing?.gameId||''} required><option value="" disabled>Select game</option>{data.games.map(game=><option key={game.id} value={game.id}>{game.name}</option>)}</select><input name="date" type="date" defaultValue={editing?.date||date} required/><input name="result" placeholder="result" inputMode="numeric" pattern="[0-9]{1,3}" defaultValue={editing?.result||''} required/><button type="submit">{editing?'Update':'Save'} Result</button></form>{message&&<small>{message}</small>}</div><div className="admin-card"><table className="data-table"><thead><tr><th>Game</th><th>Date</th><th>Result</th><th>Action</th></tr></thead><tbody>{data.results.map(row=><tr key={row.id}><td>{data.games.find(game=>Number(game.id)===Number(row.gameId))?.name||row.gameId}</td><td>{row.date}</td><td>{row.result}</td><td><button onClick={()=>setEditing(row)}>Edit</button></td></tr>)}</tbody></table>{!data.results.length&&<p>No results have been updated for this date.</p>}</div></main></div>;
}
