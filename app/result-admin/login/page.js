'use client';
import {useState} from 'react';
import {useRouter} from 'next/navigation';

export default function ResultAdminLogin(){
  const [error,setError]=useState('');
  const [loading,setLoading]=useState(false);
  const router=useRouter();
  async function submit(event){
    event.preventDefault();
    const formElement=event.currentTarget;
    setError('');setLoading(true);
    const response=await fetch('/api/result-admin/login',{method:'POST',headers:{'content-type':'application/json'},body:JSON.stringify(Object.fromEntries(new FormData(formElement)))});
    const body=await response.json().catch(()=>({}));
    if(response.ok){formElement.reset();router.replace('/result-admin/dashboard');return}
    setLoading(false);setError(body.error||'Invalid username or password');
  }
  return <main className="login-page"><form className="login-card" onSubmit={submit} autoComplete="on"><h1>Results Admin Login</h1><p>Prem Nagar and Jammu City only</p><label htmlFor="result-admin-username">Username</label><input id="result-admin-username" name="username" autoComplete="username" required/><label htmlFor="result-admin-password">Password</label><input id="result-admin-password" name="password" type="password" autoComplete="current-password" required/>{error&&<p role="alert" style={{color:'crimson'}}>{error}</p>}<button className="primary-button" type="submit" disabled={loading}>{loading?'Signing in…':'Login'}</button></form></main>;
}
