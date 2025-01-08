<?php 
include_once 'connect/connect.php';
$select_typename2="SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr  
FROM tbl_holiday_detail
WHERE Emp_id = 1  AND Type_id = 2
AND sick_type='ขอลากิจส่วนตัว' and status_leave=1 
AND sick_startdate< '2019-08-13'";
$query_typename2 = mysqli_query($objconnect,$select_typename2);
$num_typename2 = mysqli_num_rows($query_typename2);
echo 'string',$num_typename2;
?>