<?php
//written by Alan Zhai and Jennifer Liao
header('Access-Control-Allow-Origin: http://localhost:4200');
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Authorization, Accept, Client-Security-Token, Accept-Encoding');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
session_start();

$user="";

if (isset($_SESSION['user'])){
    $user=$_SESSION['user'];
}
echo json_encode(['user'=>$user]);


?>


