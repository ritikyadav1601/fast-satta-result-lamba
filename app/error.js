'use client';

export default function ErrorPage({ reset }) {
  return <main className="service-error">
    <section>
      <p className="service-error-code">503</p>
      <h1>Results are temporarily unavailable</h1>
      <p>The database connection could not be established. Please wait a moment and try again.</p>
      <button type="button" onClick={() => reset()}>Try again</button>
    </section>
  </main>;
}
