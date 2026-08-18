const encoder=new TextEncoder();

const toHex=bytes=>Array.from(new Uint8Array(bytes),byte=>byte.toString(16).padStart(2,'0')).join('');

export async function adminSessionToken(){
  const secret=process.env.ADMIN_SESSION_SECRET;
  if(!secret||secret.length<24)return null;
  const key=await crypto.subtle.importKey('raw',encoder.encode(secret),{name:'HMAC',hash:'SHA-256'},false,['sign']);
  return toHex(await crypto.subtle.sign('HMAC',key,encoder.encode('fast-satta-result-admin')));
}

export async function isAdminSession(value){
  const expected=await adminSessionToken();
  if(!expected||!value||value.length!==expected.length)return false;
  let difference=0;
  for(let index=0;index<expected.length;index++)difference|=expected.charCodeAt(index)^value.charCodeAt(index);
  return difference===0;
}

export async function restrictedAdminSessionToken(){
  const secret=process.env.RESTRICTED_ADMIN_SESSION_SECRET;
  if(!secret||secret.length<24)return null;
  const key=await crypto.subtle.importKey('raw',encoder.encode(secret),{name:'HMAC',hash:'SHA-256'},false,['sign']);
  return toHex(await crypto.subtle.sign('HMAC',key,encoder.encode('fast-satta-result-restricted-admin')));
}

export async function isRestrictedAdminSession(value){
  const expected=await restrictedAdminSessionToken();
  if(!expected||!value||value.length!==expected.length)return false;
  let difference=0;
  for(let index=0;index<expected.length;index++)difference|=expected.charCodeAt(index)^value.charCodeAt(index);
  return difference===0;
}
