import Link from 'next/link';
import SiteChrome from '@/components/SiteChrome';
import {getChartIndex} from '@/lib/store';
import {chartSlugForGame} from '@/lib/game-slug';
import ChartFinder from '@/components/ChartFinder';
import {getChartFinderOptions} from '@/lib/chart-finder';

export const revalidate = 300;

const dateTitle = () => {
  const now = new Date();
  const yesterday = new Date(now.getTime() - 86400000);
  const format = value => new Intl.DateTimeFormat('en-GB', { day:'2-digit', month:'long', year:'numeric', timeZone:'Asia/Kolkata' }).format(value).replace(/ (\d{4})$/, ', $1');
  return `Satta King Fast Result – ${format(now)} & ${format(yesterday)}`;
};

export const metadata = { title: 'Satta King Chart 2026 – All Game Result Charts | Fast Satta Result', description: 'Satta King chart for every game: Gali, Desawar, Faridabad, Ghaziabad, Delhi Bazar, Shri Ganesh and more. Open a game to see its full yearly result record.', alternates: { canonical: '/chart' } };

export default async function Chart() {
  const [index, finder] = await Promise.all([getChartIndex(), getChartFinderOptions()]);
  const games = index.games.filter(game => game.status !== false);
  const resultKeys = new Set(index.keys);
  const years = [...new Set(index.keys.map(key => Number(key.split(':')[1])).filter(Boolean))].sort((a, b) => b - a);

  return <SiteChrome active="chart">
    <div className="chart-date-band"><p>{dateTitle()}</p></div>
    <main className="chart-page">
      <section className="chart-intro">
        <h1>Satta King Chart {years[0]} – Satta Result Chart &amp; Old Charts</h1>
        <p className="chart-intro-text">Select any game below to open its full yearly result chart. Every chart lists the declared result for each day of the month, from January to December, with older years available in the table.</p>
      </section>
      <ChartFinder {...finder}/>
      <section className="chart-gradient-heading"><h2>Gali Chart, Desawar History</h2></section>
      <section className="chart-gradient-heading"><h2>SATTA KING CHART</h2></section>
      <section className="chart-gradient-heading"><h3>{years[0]} Record, Daily Result List</h3></section>
      {games.length && years.length ? <div className="chart-table-scroll">
        <table className="chart-index-table">
          <thead><tr><th>Game</th>{years.map(year => <th key={year}>{year}</th>)}</tr></thead>
          <tbody>{games.map(game => <tr key={game.id}>
            <th scope="row">{game.name}</th>
            {years.map(year => <td key={year}>{resultKeys.has(`${game.id}:${year}`) ? <Link href={`/chart/${chartSlugForGame(game)}?year=${year}`}>{year}</Link> : '--'}</td>)}
          </tr>)}</tbody>
        </table>
      </div> : <div className="empty-state">No chart data is available yet.</div>}
      <section className="chart-seo-text">
        <h2>Satta King Record Chart {years[0]} – How to Read It</h2>
        <p>The Satta King chart page collects the full result history of every game shown on Fast Satta Result, including Gali, Desawar, Faridabad, Ghaziabad, Delhi Bazar and Shri Ganesh. Each year link opens a month-by-month grid for that game, where the row is the date and the column is the month. Results are updated as soon as they are declared, so the current year&apos;s chart always includes today&apos;s result.</p>
        <p>For today&apos;s live results go to the <Link href="/">Fast Satta Result homepage</Link>. Chart records are shared for information only.</p>
      </section>
    </main>
  </SiteChrome>;
}
