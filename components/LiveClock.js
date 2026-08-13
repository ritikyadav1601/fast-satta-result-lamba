'use client';
import {useEffect,useState} from 'react';
export default function LiveClock(){const [value,setValue]=useState('');useEffect(()=>{const update=()=>setValue(new Date().toLocaleString('en-US',{year:'numeric',month:'long',day:'numeric',hour:'numeric',minute:'numeric',second:'numeric',hour12:true}));update();const id=setInterval(update,1000);return()=>clearInterval(id)},[]);return <div id="clockbox">{value}</div>}
