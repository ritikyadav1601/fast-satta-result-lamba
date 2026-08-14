'use client';

import { useEffect, useMemo, useState } from 'react';
import { useRouter } from 'next/navigation';

const indiaDate = () => new Intl.DateTimeFormat('en-CA', { timeZone: 'Asia/Kolkata' }).format(new Date());
const sections = {
  results: { label: 'Game Results', fields: ['gameId', 'date', 'result'] },
  khaiwalCharts: { label: 'Khaiwal Chart', fields: [] },
};

export default function AdminApp({ initialSection = 'results' }) {
  const router = useRouter();
  const [data, setData] = useState(null);
  const [section, setSection] = useState(sections[initialSection] ? initialSection : 'results');
  const [date, setDate] = useState(indiaDate);
  const [editing, setEditing] = useState(null);
  const [message, setMessage] = useState('');
  async function load(selectedDate = date) {
    setData(null);
    const response = await fetch(`/api/admin/data?date=${selectedDate}`);
    const body = await response.json();
    if (!response.ok) throw new Error(body.error || 'Could not load the primary database.');
    setData(body);
  }
  useEffect(() => { load().catch(error => setMessage(error.message)); }, []);
  const definition = sections[section];
  const rows = useMemo(() => data?.[section] || [], [data, section]);

  async function changeDate(event) { const value = event.target.value; setDate(value); setEditing(null); try { await load(value); } catch (error) { setMessage(error.message); } }
  async function save(event) {
    event.preventDefault();
    const item = { ...(editing || {}), ...Object.fromEntries(new FormData(event.currentTarget)) };
    const response = await fetch('/api/admin/data', { method: 'POST', headers: { 'content-type': 'application/json' }, body: JSON.stringify({ collection: section, item }) });
    const saved = await response.json();
    if (!response.ok) { setMessage(saved.error || 'Could not save.'); return; }
    if (section === 'results' && saved.date !== date) { await load(date); } else setData(current => ({ ...current, [section]: current[section].some(row => String(row.id) === String(saved.id)) ? current[section].map(row => String(row.id) === String(saved.id) ? saved : row) : [saved, ...current[section]] }));
    setEditing(null); event.currentTarget.reset(); setMessage('Saved to MONGO_URI.');
  }
  async function saveKhaiwal(event) {
    event.preventDefault();
    const form=event.currentTarget;
    const item=Object.fromEntries(new FormData(form));
    const response=await fetch('/api/admin/data',{method:'POST',headers:{'content-type':'application/json'},body:JSON.stringify({collection:'khaiwal1',item})});
    const saved=await response.json();
    if(!response.ok){setMessage(saved.error||'Could not save.');return;}
    setData(current=>({...current,settings:{...current.settings,...saved}}));
    setMessage('Khaiwal chart saved.');
  }
  async function logout() { await fetch('/api/admin/login', { method: 'DELETE' }); router.push('/admin/login'); }
  if (!data) return <div className="admin-main">{message || 'Loading selected date…'}</div>;
  if(section==='khaiwalCharts')return <div className="admin-shell"><header className="admin-top"><strong>Fast Satta Result Admin — MONGO_URI only</strong><button className="primary-button" onClick={logout}>Logout</button></header><div className="admin-grid"><aside className="admin-nav">{Object.entries(sections).map(([key,item])=><button key={key} className={section===key?'active':''} onClick={()=>{setSection(key);setEditing(null);setMessage('')}}>{item.label}</button>)}</aside><main className="admin-main"><h1>Khaiwal Chart</h1><p>Change the homepage Khaiwal name and WhatsApp number.</p><div className="khaiwal-admin-grid"><div className="admin-card"><h2>Khaiwal Chart</h2><form className="admin-form" onSubmit={saveKhaiwal}><input name="khaiwalName" placeholder="Khaiwal name" defaultValue={data.settings?.khaiwal_name||''} required/><input name="whatsappNumbers" type="tel" placeholder="WhatsApp number with country code" defaultValue={data.settings?.whatsapp_number||''} required/><button type="submit">Save Khaiwal Chart</button></form></div></div>{message&&<div className="admin-card"><strong>{message}</strong></div>}</main></div></div>;
  return <div className="admin-shell"><header className="admin-top"><strong>Fast Satta Result Admin — MONGO_URI only</strong><button className="primary-button" onClick={logout}>Logout</button></header><div className="admin-grid"><aside className="admin-nav">{Object.entries(sections).map(([key, item]) => <button key={key} className={section === key ? 'active' : ''} onClick={() => { setSection(key); setEditing(key === 'otherCharts' ? data.otherCharts?.[0] || null : null); }}>{item.label}</button>)}</aside><main className="admin-main">{section === 'results' && <div className="admin-card"><label className="admin-date-filter">Show results for <input type="date" value={date} onChange={changeDate} /></label><p>Only games updated on this date are listed below. Select another date to view or update that date’s results.</p></div>}<div className="admin-card"><h1>{definition.label}</h1>{section === 'results' && <p>Only Jaipur Matka, Sadar Bazar, Gwalior, Surat Bazar, Faridkot, Prem Nagar, and Jammu City can be updated here.</p>}{section === 'otherCharts'&&<p>Change the name and WhatsApp number shown in the second Khaiwal chart on the homepage.</p>}<form className="admin-form" onSubmit={save} key={editing?.id || `${section}-${date}`}>{definition.fields.map(field => field === 'gameId' ? <select key={field} name={field} defaultValue={editing?.[field] || ''} required><option value="" disabled>Select game</option>{data.games.map(game => <option key={game.id} value={game.id}>{game.name}</option>)}</select> : <input key={field} name={field} placeholder={field === 'khaiwalName' ? 'Khaiwal name' : field === 'whatsappNumbers' ? 'WhatsApp number with country code' : field} type={field === 'date' ? 'date' : field === 'whatsappNumbers' ? 'tel' : 'text'} defaultValue={editing?.[field] || (field === 'date' ? date : '')} required />)}<button type="submit">{section==='otherCharts'?'Save Changes':editing?'Update':'Save'} {definition.label}</button></form>{message && <small>{message}</small>}</div><div className="admin-card"><table className="data-table"><thead><tr>{definition.fields.map(field => <th key={field}>{field === 'khaiwalName'?'Khaiwal name':field === 'whatsappNumbers' ? 'WhatsApp number' : field}</th>)}<th>Action</th></tr></thead><tbody>{rows.map(row => <tr key={row.id}>{definition.fields.map(field => <td key={field}>{field === 'gameId' ? data.games.find(game => Number(game.id) === Number(row[field]))?.name || row[field] : String(row[field] ?? '')}</td>)}<td><button onClick={() => setEditing(row)}>Edit</button></td></tr>)}</tbody></table>{!rows.length && <p>{section === 'results' ? 'No game results have been updated for this date.' : 'Enter the second Khaiwal name and WhatsApp number above.'}</p>}</div></main></div></div>;
}
