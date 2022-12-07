<?php
require_once('locationIP.php');
define("NB_TEST", 98);

$aTab = [
    "37.58.179.26",
    "192.168.122.143"
];

for($i = 0; $i < NB_TEST; $i++) {
    $sIP = sprintf("%d.%d.%d.%d", rand(1,255), rand(1,255), rand(1,255), rand(1,255) );
    $aTab[] = $sIP;
}

foreach ($aTab as $sIP) {
    print_r(geolocalisation($sIP));
}