export const gameSlug = value => String(value || '')
  .trim()
  .toLowerCase()
  .normalize('NFKD')
  .replace(/[\u0300-\u036f]/g, '')
  .replace(/[^\p{L}\p{N}]+/gu, '-')
  .replace(/^-+|-+$/g, '');

// Legacy (MONGO_URI) slugs that differ from the MAIN/EXTRA database names for the same game.
export const chartSlugAlias = {'shree-ganesh':'shri-ganesh','gaziabad':'ghaziabad','disawar':'desawar','surt-bazar':'surat-bazar','jaamu-city':'jammu-city','gwallior':'gwalior','फरीदकोट':'faridkot'};

// One URL per game: misspelled legacy slugs 301 to the correct spelling (see app/chart/[game_name]/page.js).
export const canonicalChartSlug = slug => chartSlugAlias[slug] || slug;
export const legacySlugsFor = slug => [slug, ...Object.keys(chartSlugAlias).filter(key => chartSlugAlias[key] === slug)];
export const chartSlugForGame = game => canonicalChartSlug(game.slug || gameSlug(game.englishName || game.english_name || game.name));
