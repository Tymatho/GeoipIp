<?php
//Les fichiers requis
require_once('php/locationIP.php');
require_once('php/view.php');

//Démarrage du serveur puis récupération des paramètres de l'ip du serveur
session_start();
$server_ip = $_SERVER["REMOTE_ADDR"];
$server_ip_parameters = geolocalisation($server_ip);

//Affichage ou non des paramètres de l'ip serveur
if ($server_ip_parameters==null){
    make403();
} else {
    if ($server_ip_parameters["country_code"] == 'FR'){
    makeView($server_ip, $server_ip_parameters);
} else {
    make403($server_ip);
}
}