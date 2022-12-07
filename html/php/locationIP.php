<?php
define("MYSQL_HOST", "localhost");
define("MYSQL_DATABASE", "ip");
define("MYSQL_USER", "jordan");
define("MYSQL_PASSWORD", "toto");
$dbh = null;

function geolocalisation($ip_to_find){
    //16799232 JP  1844410624 FR  3701407488 US
    $location_ip_dictionary = array();
    $array_ip = explode(".", $ip_to_find);
    $computed_ip = $array_ip[3] + $array_ip[2] * 256 + $array_ip[1] * 256 * 256 + $array_ip[0] * 256 * 256 * 256;
    try {
        $pdo = new PDO(
            "mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DATABASE,
            MYSQL_USER,
            MYSQL_PASSWORD,
            array(
                PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            )
        );
    } catch (PDOException $exception) {
        error_log('database connection failed!');
        die("database connection failed: " . $exception->getMessage());
    }
    $start_time = microtime(true);
    $stmt = $pdo->prepare("SELECT country_code, country_name, region_name, city_name, latitude, longitude FROM geoip WHERE ip_from <= ? AND ip_to >= ? ORDER BY ip_to DESC LIMIT 1");
    $stmt->bindValue(1, $computed_ip, PDO::PARAM_INT);
    $stmt->bindValue(2, $computed_ip, PDO::PARAM_INT);
    $stmt->execute();
    foreach ($stmt as $row) {
        $location_ip_dictionary = array("country_code"=>$row[0], "country_name"=>$row[1], "region_name"=>$row[2], "city_name"=>$row[3], "latitude"=>$row[4], "longitude"=>$row[5]);
    }
    $end_time = microtime(true);
    $execution_time = ($end_time - $start_time) * 1000;
    echo("Total time : ". $execution_time."ms");
    if (count($location_ip_dictionary)===0){
        return null;
    }
  	return $location_ip_dictionary;
}



function insertData(){
    global $dbh;
    define("SQL_INSERT", "INSERT INTO geoip(ip_from, ip_to, country_code, country_name, region_name, city_name, latitude, longitude) VALUES (%d, %d, '%s', '%s', '%s', '%s', %f, %f) ");

    $pdo_connection_string = sprintf( "mysql:host=%s;dbname=%s;charset=utf8", MYSQL_HOST, MYSQL_DATABASE );
    
    $dbh = new PDO( $pdo_connection_string, MYSQL_USER, MYSQL_PASSWORD );
    $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    execSQL("TRUNCATE TABLE geoip");
    execSQL("START TRANSACTION");
    
    $row = 1;
    if (($file_reader = fopen("../csv/geoip.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($file_reader, 1000, ",")) !== FALSE) {
            $row++;
            if ($row % 500 == 0) {
                print("$row enregistrements\n");
    
                execSQL("COMMIT");
                execSQL("START TRANSACTION");
            }
    
            $query = sprintf(SQL_INSERT, 
                $data[0], 
                $data[1], 
                $data[2], 
                addslashes($data[3]), 
                addslashes($data[4]), 
                addslashes($data[5]), 
                $data[6], 
                $data[7] 
            );
    
            execSQL($query);
        }
        fclose($file_reader);
    }
    
    print("$row enregistrements\n");
    execSQL("COMMIT");
    
    $dbh = NULL;
}
    
function execSQL($p_query)
{
    global $dbh;
    
    $stmt = $dbh->prepare( $p_query );
    if ( $stmt !== false ) {
        $stmt->execute();
    }
}