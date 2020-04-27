<?php
session_start();
header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
require('connect-db.php');

if (isset($_SESSION['user'])){
    echo '[{"user": "true"}]';
}
else {
    echo '[{"user": "false"}]';
}



?>

