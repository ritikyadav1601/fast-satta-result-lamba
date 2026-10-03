// Dynamic date placeholders for the admin "Homepage SEO" fields.
// Any field can contain these tags; they are replaced with the current
// India (Asia/Kolkata) date every time the page is rendered.
//   {date}      -> 03 October 2026
//   {yesterday} -> 02 October 2026
//   {day}       -> 03
//   {month}     -> October
//   {year}      -> 2026
const TZ = 'Asia/Kolkata';
const fmt = (d, opts) => new Intl.DateTimeFormat('en-GB', { timeZone: TZ, ...opts }).format(d);

export const SEO_DATE_TAGS = ['{date}', '{yesterday}', '{day}', '{month}', '{year}'];

export function seoDateValues(now = new Date()) {
  const yesterday = new Date(now.getTime() - 86400000);
  return {
    date: fmt(now, { day: '2-digit', month: 'long', year: 'numeric' }),
    yesterday: fmt(yesterday, { day: '2-digit', month: 'long', year: 'numeric' }),
    day: fmt(now, { day: '2-digit' }),
    month: fmt(now, { month: 'long' }),
    year: fmt(now, { year: 'numeric' }),
  };
}

export function hasSeoDateTag(text) {
  return /\{(date|yesterday|day|month|year)\}/i.test(String(text || ''));
}

export function fillSeoDates(text, now = new Date()) {
  if (!text) return text;
  const values = seoDateValues(now);
  return String(text).replace(/\{(date|yesterday|day|month|year)\}/gi, (_, key) => values[key.toLowerCase()]);
}
