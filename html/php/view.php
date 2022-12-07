<?php
function makeHeader(){
    echo('
    <header>
    <title>IP Location</title>
    <link rel="icon" type="image/x-icon" href="/icon/gear.ico">
    <link href="style.css" rel="stylesheet" />
    </header>
    ');
}

function makeContent($p_server_ip, $p_server_ip_parameters){
  $country_code = "NOT FOUND";
  $country_name = "NOT FOUND";
  $region_name = "NOT FOUND";
  $city_name = "NOT FOUND";
  $latitude = "NOT FOUND";
  $longitude = "NOT FOUND";
  if (!empty($p_server_ip_parameters)){
      $country_code = $p_server_ip_parameters['country_code'];
      $country_name = $p_server_ip_parameters['country_name'];
      $region_name = $p_server_ip_parameters['region_name'];
      $city_name = $p_server_ip_parameters['city_name'];
      $latitude = $p_server_ip_parameters['latitude'];
      $longitude = $p_server_ip_parameters['longitude'];
  }
  echo('
    <body>
      <h1>Server IP : '.$p_server_ip.'</h1>
      <h2>Hello french person !</h2>
      <table>
      <tr>
        <th>Country Code</th>
        <th>Country Name</th>
        <th>Region name</th>
        <th>City Name</th>
        <th>Latitude</th>
        <th>Longitude</th>
      </tr>
      <tr>
        <td>'.$country_code.'</td>
        <td>'.$country_name.'</td>
        <td>'.$region_name.'</td>
        <td>'.$city_name.'</td>
        <td>'.$latitude.'</td>
        <td>'.$longitude.'</td>
      </tr>
      </table>
    </body>
  ');
}

function make403(){
  makeHeader();
  echo('
    <body>
      <h1>ERROR 403</h1>
    </body>
  ');
}

function makeView($p_server_ip, $p_server_ip_parameters){
    makeHeader();
    makeContent($p_server_ip, $p_server_ip_parameters);
}