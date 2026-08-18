# Fast Satta Result — Next.js

This project is a standalone Next.js application backed by MongoDB, with
`data/site.json` as the primary-data fallback. PHP, Laravel, Composer, and
MySQL are not required at runtime.

## Development

```bash
npm install
npm run dev
```

Open `http://localhost:3000`.

## Production

Build with `npm run build`, then deploy the generated standalone server or let
your Next.js hosting provider run the project. The health-check endpoint is
`/api/health`.

Configure every variable from `.env.example` in the production hosting
environment. Never commit `.env.local`. Use a unique admin password and a
random `ADMIN_SESSION_SECRET` of at least 24 characters.

The restricted result panel is available at `/result-admin/login`. Configure
`RESTRICTED_ADMIN_USERNAME`, `RESTRICTED_ADMIN_PASSWORD`, and a unique
`RESTRICTED_ADMIN_SESSION_SECRET` of at least 24 characters. This account can
read and update only Prem Nagar and Jammu City results.

The production domain is `https://fast-satta-result.com`. Next.js generates
`/robots.txt`, `/sitemap.xml`, `/manifest.webmanifest`, canonical metadata, and
security headers automatically.

## Import the legacy database

The application data from the legacy MySQL/MariaDB SQL dump can be loaded into
the local JSON store with:

```bash
npm run import:sql -- "../u261634547_fast_satta_res (2).sql"
```

The source dump is read-only. The importer replaces `data/site.json` with the
converted application data and merges legacy and current results by game/date.
