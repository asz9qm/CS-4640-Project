<?php 
//written by Jennifer Liao
//update the files
require("allActions.php");
session_start(); 
$stats = getStats($_SESSION['user']);
echo json_encode($stats);
?>


