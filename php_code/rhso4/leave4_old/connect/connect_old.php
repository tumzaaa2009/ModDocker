<?php 
//defind variable
$Server="203.157.102.84"; //ip address ของ mysql sever
$User="root"; // mysql user
$Pass="kethealth4"; //mysql password
$DBName="leave4"; // ชื่อฐานข้อมูล
$Port = '3306';
// ////////
$objconnect = mysqli_connect($Server,$User,$Pass,$DBName) or die("GG");
// $objquery = mysqli_query($objconnect,"SET CHARACTER SET UTF8");
// print_r($_POST);
date_default_timezone_set('Asia/Bangkok');	

?>


