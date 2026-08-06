<?php
set_time_limit(0);
ini_set('memory_limit', '512M');
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

echo "Starting Scraper for missing games...\n";

function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$html) {
        return false;
    }
    return $html;
}

$html = fetchUrl('https://satta-king-fast.com/');
if (!$html) {
    die("Failed to fetch homepage.\n");
}

libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();
$xpath = new DOMXPath($dom);

$gameMap = [];
$rows = $xpath->query("//tr[contains(@class, 'game-result')]");
foreach ($rows as $row) {
    $nameNode = $xpath->query(".//h3[@class='game-name']", $row)->item(0);
    $linkNode = $xpath->query(".//h3[@class='game-link']/a", $row)->item(0);
    if ($nameNode && $linkNode) {
        $name = trim($nameNode->textContent);
        $url = $linkNode->getAttribute('href');
        $gameMap[Str::slug($name)] = $url;
    }
}
unset($dom, $xpath, $rows);
echo "Found " . count($gameMap) . " game links on homepage.\n";

$extraGames = DB::table('extra_games')
    ->whereNotIn('id', DB::table('extra_game_results')->select('extra_game_id'))
    ->get();

$inserted = 0;
DB::disableQueryLog();

foreach ($extraGames as $game) {
    $slug = Str::slug($game->name);
    
    if (!isset($gameMap[$slug])) {
        // Fallback: search for partial match
        $foundSlug = null;
        foreach ($gameMap as $mapSlug => $mapUrl) {
            // E.g. DUBAI MATKA KING -> dubai-matka
            if (str_contains($mapSlug, explode('-', $slug)[0])) {
                $foundSlug = $mapSlug;
                break; // Take first match
            }
        }
        
        if ($foundSlug) {
            echo "URL fuzzy mapped: {$game->name} -> {$foundSlug}\n";
            $slug = $foundSlug;
        } else {
            echo "URL completely missing for: {$game->name} (tried slug: $slug)\n";
            continue;
        }
    }

    $baseUrl = $gameMap[$slug];
    echo "Scraping {$game->name}...\n";

    for ($month = 1; $month <= 8; $month++) {
        $monthStr = str_pad($month, 2, '0', STR_PAD_LEFT);
        $year = 2026;
        $url = "{$baseUrl}?month={$monthStr}&year={$year}";
        
        echo "  Fetching month {$monthStr}...\n";
        
        $chartHtml = false;
        $retries = 3;
        while ($retries > 0) {
            $chartHtml = fetchUrl($url);
            if ($chartHtml) break;
            $retries--;
            sleep(1);
        }

        if (!$chartHtml) {
            echo "  Failed to fetch month {$monthStr}\n";
            continue;
        }

        $cdom = new DOMDocument();
        $cdom->loadHTML($chartHtml);
        libxml_clear_errors();
        $cxpath = new DOMXPath($cdom);

        $headers = $cxpath->query("//tr[@class='date-name']//th[@class='name']");
        $gameIndex = -1;
        
        foreach ($headers as $index => $header) {
            if (Str::slug(trim($header->textContent)) == $slug) {
                $gameIndex = $index;
                break;
            }
        }
        
        if ($gameIndex === -1) {
            foreach ($headers as $index => $header) {
                if (stripos(trim($header->textContent), explode(' ', $game->name)[0]) !== false) {
                    $gameIndex = $index;
                    break;
                }
            }
        }

        $days = $cxpath->query("//tr[@class='day-number']");
        $inserts = [];
        foreach ($days as $dayRow) {
            $dateNode = $cxpath->query(".//td[@class='day']", $dayRow)->item(0);
            $numbers = $cxpath->query(".//td[@class='number']", $dayRow);
            
            if ($dateNode && $numbers->length > 0 && $gameIndex !== -1 && $numbers->item($gameIndex)) {
                $dayStr = trim($dateNode->textContent);
                $resultVal = trim($numbers->item($gameIndex)->textContent);
                
                if (is_numeric($dayStr) && is_numeric($resultVal)) {
                    $date = "{$year}-{$monthStr}-" . str_pad($dayStr, 2, '0', STR_PAD_LEFT);
                    
                    DB::table('extra_game_results')->updateOrInsert(
                        ['extra_game_id' => $game->id, 'result_date' => $date],
                        ['result' => $resultVal, 'year' => $year, 'created_at' => now(), 'updated_at' => now()]
                    );
                    $inserted++;
                }
            }
        }
        unset($cdom, $cxpath, $days, $headers);
    }
}

echo "Done! Inserted/Updated {$inserted} results.\n";
