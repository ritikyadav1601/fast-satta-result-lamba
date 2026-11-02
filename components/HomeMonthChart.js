"use client";
import { useState } from "react";

// Homepage monthly result chart with "Last Month" / "Next Month" buttons.
// The current month is rendered on the server (good for SEO); other months load from /api/home-month.
const MONTHS = ["JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER"];
const shiftMonth = (ym, step) => {
  const [y, m] = ym.split("-").map(Number);
  const d = new Date(Date.UTC(y, m - 1 + step, 1));
  return `${d.getUTCFullYear()}-${String(d.getUTCMonth() + 1).padStart(2, "0")}`;
};
const monthLabel = (ym) => {
  const [y, m] = ym.split("-").map(Number);
  return `${MONTHS[m - 1]} ${y}`;
};
const daysFor = (ym, todayIso) => {
  const [y, m] = ym.split("-").map(Number);
  const last = ym === todayIso.slice(0, 7) ? Number(todayIso.slice(8, 10)) : new Date(Date.UTC(y, m, 0)).getUTCDate();
  return Array.from({ length: last }, (_, i) => {
    const dd = String(i + 1).padStart(2, "0");
    return { label: `${dd}-${String(m).padStart(2, "0")}`, date: `${ym}-${dd}` };
  });
};
const WaitResult = () => (
  <img className="pending-result-gif" src="/assets/img/wait.gif" alt="Result awaiting update" width="40" height="40" loading="lazy" decoding="async" />
);

export default function HomeMonthChart({ games, initialResults, todayIso }) {
  const currentMonth = todayIso.slice(0, 7);
  const [month, setMonth] = useState(currentMonth);
  const [cache, setCache] = useState({ [currentMonth]: initialResults });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  async function go(step) {
    const target = shiftMonth(month, step);
    if (target > currentMonth) return;
    setError("");
    if (cache[target]) return setMonth(target);
    setLoading(true);
    try {
      const slugs = games.map((g) => g.slug).join(",");
      const res = await fetch(`/api/home-month?month=${target}&slugs=${encodeURIComponent(slugs)}`);
      if (!res.ok) throw new Error();
      const data = await res.json();
      setCache((c) => ({ ...c, [target]: data.results || {} }));
      setMonth(target);
    } catch {
      setError("Could not load that month. Please try again.");
    } finally {
      setLoading(false);
    }
  }

  const results = cache[month] || {};
  const days = daysFor(month, todayIso);
  const groups = [];
  for (let i = 0; i < games.length; i += 6) groups.push(games.slice(i, i + 6));
  const cell = (g, date) => {
    const value = results[g.slug]?.[date];
    if (value != null && value !== "") return value;
    return date === todayIso ? <WaitResult /> : "--";
  };
  const table = (list) => (
    <div className="table-responsive marginBottom">
      <table className="table table-bordered table-extra">
        <thead>
          <tr>
            <td className="table_chart_section_01 forfirtcolor date text-center"><strong className="fon">Date</strong></td>
            {list.map((g) => (
              <td className="table_chart_section forfirtcolor text-center" key={g.slug}>{g.name.toUpperCase()}</td>
            ))}
          </tr>
        </thead>
        <tbody>
          {days.map((day) => (
            <tr key={day.date}>
              <td className="forfirtcolor text-center"><span className="fon">{day.label}</span></td>
              {list.map((g) => (
                <td key={g.slug}><span className="table_chart_section_02">{cell(g, day.date)}</span></td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );

  return (
    <>
      <section className="octoberresultchart">
        <p style={{ fontSize: "1.17em", fontWeight: "bold", margin: "1em 0" }}>
          {monthLabel(month)} RESULT CHART{loading ? " – Loading…" : ""}
        </p>
        {error ? <p className="month-switch-error">{error}</p> : null}
      </section>
      <section className="newtable home-month-table" aria-busy={loading}>
        <div className="month-table-desktop">{table(games)}</div>
        <div className="month-table-mobile">
          {groups.map((group, index) => <div key={index}>{table(group)}</div>)}
        </div>
      </section>
      <div className="month-switch">
        <button type="button" onClick={() => go(-1)} disabled={loading}>« Last Month</button>
        <button type="button" onClick={() => go(1)} disabled={loading || month >= currentMonth}>Next Month »</button>
      </div>
    </>
  );
}
