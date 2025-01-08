<!DOCTYPE html>
<html>
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title></title>
</head>
<body>

</body>
</html>
<?php 
include_once 'connect/connect.php' ;
$select_test = "SELECT * FROM tbl_holiday_detail";
$event_array=array(); 
$query_test = mysqli_query($objconnect,$select_test);
while ($featchselect = mysqli_fetch_array($query_test)) {
$eventArray['id'] = $featchselect["hoilday_detail_id"];
$eventArray['datestart']=$featchselect["holiday_enddate"];
$eventArray['enddate']=$featchselect["holiday_startdate"];
array_push($event_array,$eventArray);


}

echo json_encode($event_array,JSON_UNESCAPED_UNICODE);
?>
