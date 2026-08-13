'use client';

import { useRouter } from 'next/navigation';

export default function ChartYearSelect({ href, years, value }) {
  const router = useRouter();
  return <label className="year-picker">
    <span>Select Year:</span>
    <select aria-label="Select Year" value={value} onChange={event => router.push(`${href}?year=${event.target.value}`)}>
      {years.map(year => <option value={year} key={year}>{year}</option>)}
    </select>
  </label>;
}
