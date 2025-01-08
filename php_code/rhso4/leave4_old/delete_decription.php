<?php
if (!isset($_SESSION)) {
	session_start();
}

$userdeletby =  $_SESSION["name"] . ' ' . $_SESSION["lastname"];
// echo $userdeletby;

include_once 'connect/connect.php';
$select = "update tbl_holiday_detail
set status_leave= -1 , delete_by='" . $userdeletby . "'
,datedelete_by=NOW()
where hoilday_detail_id ='" . $_GET["id"] . "'";
$queryhd = mysqli_query($objconnect, $select);
// echo 'string'.$queryhd;

// $_GET["id"] . "' and emp.Emp_id ='" . $_GET["emp"] . "' 

if ($_GET["type_id"] == 1) {
	// เอาวันกลับ

	$selectcount = "SELECT holiday_detail.hoilday_detail_id,SUM(hoilday_totalday+holiday_totalhour)reslut_day
	 FROM tbl_holiday_detail holiday_detail where holiday_detail.hoilday_detail_id ='" . $_GET["id"] . "' ";
	$querycount = mysqli_query($objconnect, $selectcount);
	$numcount = mysqli_num_rows($querycount);
	$featchcount = mysqli_fetch_array($querycount);
	$featchcount['reslut_day'];  //บวกค่ากลับ 
	// echo 'string'.$sumyearhoilday;
	$g_id = $featchcount["gid"];
	// ///////////////////////////////////////////////////////////////////////////////////
	$select_limit = "SELECT sum_thisyear_holiday from tbl_total_limit_holiday where status_limit ='1' AND Emp_id ='" . $_GET["emp"] . "' ";
	$query_limit = mysqli_query($objconnect, $select_limit);
	$featch_limit = mysqli_fetch_array($query_limit);
	$text = "";
	$return_date_relust = $featchcount['reslut_day'] + $featch_limit['sum_thisyear_holiday'];
	$select_return_date = "Update tbl_total_limit_holiday Set sum_thisyear_holiday ='" . $return_date_relust . "' where  Emp_id = '" . $_GET["emp"] . "' and status_limit='1'";
	mysqli_query($objconnect, $select_return_date);
}
?>

<script>
alert("ลบข้อมูลเสร็จสิน");
location.href="index.php?ht=history";
</script>