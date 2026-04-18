<?php
$tickers = ['^BVSP', '^IDIV', '^SMLL', '^IFIX', '^IFNC', '^ICON', '^IEEX', '^UTIL'];
$mh = curl_multi_init();
$handles = [];

foreach ($tickers as $t) {
    $ch = curl_init('https://brapi.dev/api/quote/' . urlencode($t) . '?token=nT3gsPnG5mG2oYdPfmb1fL');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $handles[$t] = $ch;
    curl_multi_add_handle($mh, $ch);
}

$active = null;
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) curl_multi_select($mh);
} while ($active && $status == CURLM_OK);

foreach ($handles as $t => $ch) {
    echo $t . ': ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . ' ' . curl_multi_getcontent($ch) . PHP_EOL;
}
?>
