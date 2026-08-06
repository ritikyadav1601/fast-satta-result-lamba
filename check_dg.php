<?php
require __DIR__.'/vendor/autoload.php';
function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    return curl_exec($ch);
}
$html = fetchUrl('https://satta-king-fast.com/delhi-golden/satta-result-chart/delhi-golden/?month=08&year=2026');
$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

$headers = $xpath->query("//tr[@class='date-name']//th[@class='name']");
echo "Headers:\n";
foreach($headers as $h) {
    echo trim($h->textContent) . "\n";
}

$days = $xpath->query("//tr[@class='day-number']");
foreach($days as $d) {
    $date = trim($xpath->query(".//td[@class='day']", $d)->item(0)->textContent ?? '');
    if ($date == '03') {
        echo "Row 03 HTML: " . $dom->saveHTML($d) . "\n";
    }
}
