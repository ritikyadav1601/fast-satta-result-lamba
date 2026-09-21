import Link from 'next/link';
import SiteChrome from '@/components/SiteChrome';
import {getChartIndex} from '@/lib/store';
import {gameSlug} from '@/lib/game-slug';

export const revalidate = 300;

const dateTitle = () => {
  const now = new Date();
  const yesterday = new Date(now.getTime() - 86400000);
  const format = value => new Intl.DateTimeFormat('en-GB', { day:'2-digit', month:'long', year:'numeric', timeZone:'Asia/Kolkata' }).format(value).replace(/ (\d{4})$/, ', $1');
  return `Satta King Fast Result – ${format(now)} & ${format(yesterday)}`;
};

export const metadata = { title: 'Satta King Chart 2026 – All Game Result Charts | Fast Satta Result', description: 'Satta King chart for every game: Gali, Desawar, Faridabad, Ghaziabad, Delhi Bazar, Shri Ganesh and more. Open a game to see its full yearly result record.', alternates: { canonical: '/chart' } };

export default async function Chart() {
  const index = await getChartIndex();
  const games = index.games.filter(game => game.status !== false);
  const resultKeys = new Set(index.keys);
  const years = [...new Set(index.keys.map(key => Number(key.split(':')[1])).filter(Boolean))].sort((a, b) => b - a);

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
