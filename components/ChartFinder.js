// "SATTA RECORD CHART" picker: choose a game, month and year, press Check, open that chart.
// A plain GET form, so it works without JavaScript; /chart/find redirects to /chart/[game]?year=&month=.
const MONTHS = ['January','February','March','April','May','June','July','August','September','October','November','December'];

export default function ChartFinder({ games = [], years = [], game = '', year, month = '', heading = true, title = '' }) {
  if (!games.length || !years.length) return null;
  const monthValue = /^(?:[1-9]|1[0-2])$/.test(String(month)) ? String(Number(month)) : '';
  return <section className="chart-finder" aria-labelledby="chart-finder-title">
    {heading ? <h2 id="chart-finder-title">{title || `SATTA RECORD CHART ${years[0]}`}</h2> : <h2 id="chart-finder-title" className="sr-only">Check another chart</h2>}
    <form className="chart-finder-form" action="/chart/find" method="get">
      <label className="sr-only" htmlFor="chart-finder-game">Select game</label>
      <select id="chart-finder-game" name="game" defaultValue={game || games[0].slug} required>
        {games.map(item => <option key={item.slug} value={item.slug}>{item.name}</option>)}
      </select>
      <label className="sr-only" htmlFor="chart-finder-month">Select month</label>
      <select id="chart-finder-month" name="month" defaultValue={monthValue}>
        <option value="">All Months</option>
        {MONTHS.map((item, index) => <option key={item} value={String(index + 1)}>{item}</option>)}
      </select>
      <label className="sr-only" htmlFor="chart-finder-year">Select year</label>
      <select id="chart-finder-year" name="year" defaultValue={String(year || years[0])}>
        {years.map(item => <option key={item} value={item}>{item}</option>)}
      </select>
      <button type="submit">Check</button>
    </form>
  </section>;
}
