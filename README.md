# Fast Satta Result — Next.js

This project is a standalone Next.js application. It uses a local JSON data
store, so PHP, Laravel, Composer, and MySQL are not required.

## Development

```bash
npm install
npm run dev
```

Open `http://localhost:3000`.

## Production

```bash
npm run build
npm start
```

Copy `.env.example` to `.env.local` to customize the local administrator login.

## Import the legacy database

The application data from the legacy MySQL/MariaDB SQL dump can be loaded into
the local JSON store with:

```bash
npm run import:sql -- "../u261634547_fast_satta_res (2).sql"
```

The source dump is read-only. The importer replaces `data/site.json` with the
converted application data and merges legacy and current results by game/date.
