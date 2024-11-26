
<?php

$mysqli = new mysqli('localhost', '2364710', '10mx61', 'db2364710');

if($mysqli -> connect_errno){
    echo 'Failed to connect to MySQL:'. $mysqli -> connect_error;
    exit();
}

$sql = "SELECT *
        FROM weather
        WHERE city = '{$_GET['city']}'
		AND weather_when >= DATE_SUB(NOW(), INTERVAL 10 SECOND)
        ORDER BY weather_when DESC limit 1";

$result = $mysqli -> query($sql);

if($result == false){
    echo("<h4>SQL error description:" . $mysqli -> error . '</h4>');

}

if ($result -> num_rows == 0){
    include('data-import.php');
    $result = $mysqli -> query($sql);
}


$row = $result -> fetch_assoc();
print json_encode($row);

$result -> free_result();
$mysqli -> close();

?>