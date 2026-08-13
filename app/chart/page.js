import Link from 'next/link';
import SiteChrome from '@/components/SiteChrome';
import {getData} from '@/lib/store';
import {gameSlug} from '@/lib/game-slug';

export const dynamic = 'force-dynamic';

const dateTitle = () => {
  const now = new Date();
  const yesterday = new Date(now.getTime() - 86400000);
  const format = value => new Intl.DateTimeFormat('en-GB', { day:'2-digit', month:'long', year:'numeric', timeZone:'Asia/Kolkata' }).format(value).replace(/ (\d{4})$/, ', $1');
  return `Satta King Fast Result – ${format(now)} & ${format(yesterday)}`;
};

export default async function Chart() {
  const data = await getData();
  const games = data.games.filter(game => game.status !== false);
  const years = [...new Set(data.results.map(row => Number(String(row.date).slice(0, 4))).filter(Boolean))].sort((a, b) => b - a);
  const resultKeys = new Set(data.results.map(row => `${row.gameId}:${String(row.date).slice(0, 4)}`));

  return <SiteChrome active="chart">
    <div className="chart-date-band"><h1>{dateTitle()}</h1></div>
    <main className="chart-page">
      <section className="chart-intro">
        <h1>Satta King Chart {years[0]} – Satta Result Chart &amp; Old Charts</h1>
      </section>
      <section className="chart-gradient-heading"><h2>Gali Chart, Desawar History</h2></section>
      <section className="chart-gradient-heading"><h2>SATTA KING CHART</h2></section>
      <section className="chart-gradient-heading"><h3>{years[0]} Record, Daily Result List</h3></section>
      {games.length && years.length ? <div className="chart-table-scroll">
        <table className="chart-index-table">
          <thead><tr><th>Game</th>{years.map(year => <th key={year}>{year}</th>)}</tr></thead>
          <tbody>{games.map(game => <tr key={game.id}>
            <th scope="row">{game.name}</th>
            {years.map(year => <td key={year}>{resultKeys.has(`${game.id}:${year}`) ? <Link href={`/chart/${game.slug||gameSlug(game.englishName||game.english_name||game.name)}?year=${year}`}>{year}</Link> : '--'}</td>)}
          </tr>)}</tbody>
        </table>
      </div> : <div className="empty-state">No chart data is available yet.</div>}
    </main>
  </SiteChrome>;
}
