<?php
require_once('php/locationIP.php');
require_once('php/view.php');
session_start();
$server_ip = $_SERVER["REMOTE_ADDR"];
$server_ip_parameters = geolocalisation($server_ip);
if ($server_ip_parameters==null){
    make403();
} else {
    if ($server_ip_parameters["country_code"] == 'FR'){
    makeView($server_ip, $server_ip_parameters);
} else {
    make403($server_ip);
}
}