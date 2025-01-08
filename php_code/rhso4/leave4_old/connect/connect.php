<?php 
//defind variable
$Server="localhost"; //ip address ของ mysql sever
$User="root"; // mysql user
$Pass="kethealth4"; //mysql password
$DBName="rhso4_db_2"; // ชื่อฐานข้อมูล
$Port = '';
// ////////

$objconnect = mysqli_connect($Server,$User,$Pass) or die('Connect Error: ' . mysqli_connect_errno());
// $objquery = mysqli_query($objconnect,"SET CHARACTER SET UTF8");
// print_r($_POST);
mysqli_select_db($objconnect,"rhso4_db_2");
 
date_default_timezone_set('Asia/Bangkok');  
$objquery = mysqli_query($objconnect,"SET CHARACTER SET UTF8");

?>


