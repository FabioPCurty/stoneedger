<?php
$tickers=['^IXIC','^FTSE','^N225','^GDAXI'];
foreach($tickers as $t){
    $ch=curl_init('https://brapi.dev/api/quote/' . urlencode($t) . '?token=nT3gsPnG5mG2oYdPfmb1fL');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res=curl_exec($ch);
    echo $t . ' ' . $res . "\n";
}
?>
