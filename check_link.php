<?php
function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    return curl_exec($ch);
}
$html = fetchUrl('https://satta-king-fast.com/delhi-golden/satta-result-chart/dg/?month=08&year=2026');
preg_match_all("/<th class='name'>(.*?)<\/th>/is", $html, $m);
print_r($m[1]);
