<?php 
//defind variable
$Server="203.157.102.84"; //ip address ของ mysql sever
$User="root"; // mysql user
$Pass="kethealth4"; //mysql password
$DBName="r4"; // ชื่อฐานข้อมูล
$Port = '';
// ////////

$objconnect = mysqli_connect($Server,$User,$Pass,$DBName) or die('Connect Error: ' . mysqli_connect_errno());
// $objquery = mysqli_query($objconnect,"SET CHARACTER SET UTF8");
// print_r($_POST);
 
date_default_timezone_set('Asia/Bangkok');  
$objquery = mysqli_query($objconnect,"SET CHARACTER SET UTF8");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
  }
?>


