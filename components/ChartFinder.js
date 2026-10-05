// "SATTA RECORD CHART" picker: choose a game and a year, press Check, open that chart.
// A plain GET form, so it works without JavaScript; /chart/find redirects to /chart/[game]?year=.
export default function ChartFinder({ games = [], years = [], game = '', year, heading = true }) {
  if (!games.length || !years.length) return null;
  return <section className="chart-finder" aria-labelledby="chart-finder-title">
    {heading ? <h2 id="chart-finder-title">SATTA RECORD CHART {years[0]}</h2> : <h2 id="chart-finder-title" className="sr-only">Check another chart</h2>}
    <form className="chart-finder-form" action="/chart/find" method="get">
      <label className="sr-only" htmlFor="chart-finder-game">Select game</label>
      <select id="chart-finder-game" name="game" defaultValue={game || games[0].slug} required>
        {games.map(item => <option key={item.slug} value={item.slug}>{item.name}</option>)}
      </select>
      <label className="sr-only" htmlFor="chart-finder-year">Select year</label>
      <select id="chart-finder-year" name="year" defaultValue={String(year || years[0])}>
        {years.map(item => <option key={item} value={item}>{item}</option>)}
      </select>
      <button type="submit">Check</button>
    </form>
  </section>;
}
