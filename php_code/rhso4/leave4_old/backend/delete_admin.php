<?php
if (!isset($_SESSION)) {
	session_start();
}

$_POST['ficalyear'];
$userdeletby =  $_SESSION["name"] . ' ' . $_SESSION["lastname"];
// echo $_POST["param1"];

include_once '../connect/connect.php';
include_once 'header_css.php';
$emp = '' ;
$id  ='';
if(isset($_POST['emp'])){

$emp = $_POST['emp'];
 
}else if ($emp){
$emp =$_GET['emp'];
}
if(isset($_POST['id_holiday'])){
	$id =$_POST['id_holiday'];
}elseif (isset($_GET["id"])){
$id  = $_GET["id"] ;
} 
 

$selectcount = "SELECT detail.type_id,detail.hoilday_totalday today,detail.holiday_totalhour tohour ,limithd.count_year_holiday countyear,limithd.sum_thisyear_holiday sumyear,emp.group_id gid
FROM tbl_employment emp 
INNER JOIN tbl_holiday_detail detail ON emp.Emp_id=detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithd ON limithd.Emp_id = emp.Emp_id
 where  detail.hoilday_detail_id ='" . $id . "'and  limithd.Financial_year='" . $_POST['ficalyear'] . "' and emp.Emp_id ='" . $emp . "'  ";
$querycount = mysqli_query($objconnect, $selectcount);
$numcount = mysqli_num_rows($querycount);
$featchcount = mysqli_fetch_array($querycount);

//?ทำใหม่ เรียกค่า limit เพื่อมาบวก
$selectSum ="SELECT sum_thisyear_holiday FROM tbl_total_limit_holiday WHERE Financial_year='".$_POST['ficalyear']."' and Emp_id ='" . $emp . "'";
$querySum = mysqli_query($objconnect,$selectSum);
$featchSum = mysqli_fetch_array($querySum);

// status= -2
if (isset($_GET["cancle"])) {

	if ($_GET["cancle"] == "CANCLE") {
		echo "cancle";

		$select = "update tbl_holiday_detail
set status_leave= -2 , approve_by='" . $userdeletby . "'
,dateapprove_by=NOW(),approve_section_by='" . $_SESSION["secname"] . "',approve_comment='CANCLE'
,dateapprove_by =NOW()
where hoilday_detail_id ='" . $id . "'";
		$queryhd = mysqli_query($objconnect, $select);
		// echo 'string'.$queryhd;

		if ($_GET["type_id"] == 1) {
			// เอาวันกลับ


			//บวกวันเพื่ออัพเดทกลับ
			$day = $featchcount["today"];
			$hour = $featchcount["tohour"];
			$sum = $day + $hour;

			//update tbl_limit
			$countold = $featchcount["countold"];
			$countyear =  $featchcount["countyear"];
			$sumtotalcount = $countold + $countyear;

			// echo 'string'.$sumyearhoilday;
			$g_id = $featchcount["gid"];
			// ///////////////////////////////////////////////////////////////////////////////////
			$text = "";
			if ($g_id != 1) {

				//บวกวันเพื่ออัพเดทกลับ
				$day = $featchcount["today"];
				$hour = $featchcount["tohour"];
				$sum = $day + $hour;

				//update tbl_limit
				$countold = $featchcount["countold"];
				$countyear =  $featchcount["countyear"];
				$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
				if ($sum > 5 || $sum < 5 || $sum == 5) {
					if ($countold < 5) {
						// echo 'ifggg';
						$countold = $countold + $sum;
						$countyear = $countyear;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";

						if ($countold > 5) {
							$x = 5;
							$y = $countold - $x;
							echo 'string', $y;
							$countyear = $countyear + $y;
							$total  = $x + $countyear;
							$text = "set count_oldyear_hoilday ='" . $x . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					} else  if ($countold > 5) {
						// echo 'if';
						$countold = $x = 5;
						$discount = $sum - $x;
						$countyear = $countyear + $discount;
						$total = $countyear + $countold;
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					} else {
						// echo 'elseggssss';

						$countold = $countold;
						$countyear = $countyear + $sum;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					}
					if ($featchcount["sumyear"] <= 10) { //เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
						$check = $featchcount["sumyear"] + $sum; //บวกวันเพิ่ม
						// echo "ssss",$check;
						if ($check > 10) { //ถ้าบวกกันแล้วได้มากกว่า 10
							// echo 'string'.$check."<br>";
							// echo 'ssdssd'.$countyear;
							$countyear = 10;
							$discount = $check - $countyear; //ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
							// echo 'discount'.$discount;
							$countold = $discount;
							$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
							$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					}
				}
			} else { // id ==1
				//บวกวันเพื่ออัพเดทกลับ
				$day = $featchcount["today"];
				$hour = $featchcount["tohour"];
				$sum = $day + $hour;

				//update tbl_limit
				$countold = $featchcount["countold"];
				$countyear =  $featchcount["countyear"];
				$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
				if ($sum > 10 || $sum < 10 || $sum == 10) {
					if ($countold < 10) {

						$countold = $countold + $sum;
						$countyear = $countyear;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						if ($countold > 10) {
							$x = 10;
							$y = $countold - $x;
							// echo 'string',$y;
							$countyear = $countyear + $y;
							$total  = $x + $countyear;
							$text = "set count_oldyear_hoilday ='" . $x . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					} else  if ($countold > 10) {
						// echo 'if';
						$countold = $x = 10;
						$discount = $sum - $x;
						$countyear = $countyear + $discount;
						$total = $countyear + $countold;
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					} else {
						// echo 'elseggssss';

						$countold = $countold;
						$countyear = $countyear + $sum;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					}
					if ($featchcount["sumyear"] <= 10) { //เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
						$check = $featchcount["sumyear"] + $sum; //บวกวันเพิ่ม
						// echo "ssss",$check;
						if ($check > 10) { //ถ้าบวกกันแล้วได้มากกว่า 10
							// echo 'string'.$check."<br>";
							// echo 'ssdssd'.$countyear;
							$countyear = 10;
							$discount = $check - $countyear; //ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
							// echo 'discount'.$discount;
							$countold = $discount;
							$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
							$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					}
				}
			}
		} //type id

	} //get status-2
} //isset status= -2	

// status= -3
if (isset($_GET["cancel_sus"])) {

	if ($_GET["cancel_sus"] == "cancel_sus") {

		$select = "update tbl_holiday_detail
set status_leave= -3 , approve_sussecc='" . $userdeletby . "'
,dateapprove_sussecc=NOW(),approve_section_seccess='" . $_SESSION["secname"] . "',approve_comment_susecc='" . $_POST['param1'] . "'
where hoilday_detail_id ='" . $id . "'";
		$queryhd = mysqli_query($objconnect, $select);
		// echo 'string'.$queryhd;

		if ($_GET["type_id"] == 1) {
			// เอาวันกลับ
			$selectcount = "SELECT detail.hoilday_totalday today,detail.holiday_totalhour tohour,limithd.count_oldyear_hoilday countold,limithd.count_year_holiday countyear,limithd.sum_thisyear_holiday sumyear,emp.group_id gid
FROM tbl_employment emp 
INNER JOIN tbl_holiday_detail detail ON emp.Emp_id=detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithd ON limithd.Emp_id = emp.Emp_id
 where  detail.hoilday_detail_id ='" . $id . "' and  limithd.Financial_year='" . $_POST['ficalyear'] . "'and emp.Emp_id ='" . $_GET["emp"] . "'  ";
			$querycount = mysqli_query($objconnect, $selectcount);
			$numcount = mysqli_num_rows($querycount);
			$featchcount = mysqli_fetch_array($querycount);

			//บวกวันเพื่ออัพเดทกลับ
			$day = $featchcount["today"];
			$hour = $featchcount["tohour"];
			$sum = $day + $hour;

			//update tbl_limit
			$countold = $featchcount["countold"];
			$countyear =  $featchcount["countyear"];
			$sumtotalcount = $countold + $countyear;

			// echo 'string'.$sumyearhoilday;
			$g_id = $featchcount["gid"];
			// ///////////////////////////////////////////////////////////////////////////////////
			$text = "";
			if ($g_id != 1) {

				//บวกวันเพื่ออัพเดทกลับ
				$day = $featchcount["today"];
				$hour = $featchcount["tohour"];
				$sum = $day + $hour;

				//update tbl_limit
				$countold = $featchcount["countold"];
				$countyear =  $featchcount["countyear"];
				$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
				if ($sum > 5 || $sum < 5 || $sum == 5) {
					if ($countold < 5) {
						// echo 'ifggg';
						$countold = $countold + $sum;
						$countyear = $countyear;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";

						if ($countold > 5) {
							$x = 5;
							$y = $countold - $x;
							echo 'string', $y;
							$countyear = $countyear + $y;
							$total  = $x + $countyear;
							$text = "set count_oldyear_hoilday ='" . $x . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					} else  if ($countold > 5) {
						// echo 'if';
						$countold = $x = 5;
						$discount = $sum - $x;
						$countyear = $countyear + $discount;
						$total = $countyear + $countold;
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					} else {
						// echo 'elseggssss';

						$countold = $countold;
						$countyear = $countyear + $sum;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					}
					if ($featchcount["sumyear"] <= 10) { //เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
						$check = $featchcount["sumyear"] + $sum; //บวกวันเพิ่ม
						// echo "ssss",$check;
						if ($check > 10) { //ถ้าบวกกันแล้วได้มากกว่า 10
							// echo 'string'.$check."<br>";
							// echo 'ssdssd'.$countyear;
							$countyear = 10;
							$discount = $check - $countyear; //ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
							// echo 'discount'.$discount;
							$countold = $discount;
							$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
							$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					}
				}
			} else { // id ==1
				//บวกวันเพื่ออัพเดทกลับ
				$day = $featchcount["today"];
				$hour = $featchcount["tohour"];
				$sum = $day + $hour;

				//update tbl_limit
				$countold = $featchcount["countold"];
				$countyear =  $featchcount["countyear"];
				$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
				if ($sum > 10 || $sum < 10 || $sum == 10) {
					if ($countold < 10) {

						$countold = $countold + $sum;
						$countyear = $countyear;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						if ($countold > 10) {
							$x = 10;
							$y = $countold - $x;
							// echo 'string',$y;
							$countyear = $countyear + $y;
							$total  = $x + $countyear;
							$text = "set count_oldyear_hoilday ='" . $x . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					} else  if ($countold > 10) {
						// echo 'if';
						$countold = $x = 10;
						$discount = $sum - $x;
						$countyear = $countyear + $discount;
						$total = $countyear + $countold;
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					} else {
						// echo 'elseggssss';

						$countold = $countold;
						$countyear = $countyear + $sum;
						$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
						$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
					}
					if ($featchcount["sumyear"] <= 10) { //เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
						$check = $featchcount["sumyear"] + $sum; //บวกวันเพิ่ม
						// echo "ssss",$check;
						if ($check > 10) { //ถ้าบวกกันแล้วได้มากกว่า 10
							// echo 'string'.$check."<br>";
							// echo 'ssdssd'.$countyear;
							$countyear = 10;
							$discount = $check - $countyear; //ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
							// echo 'discount'.$discount;
							$countold = $discount;
							$total = $featchcount["sumyear"] + $featchcount["today"] + $featchcount["tohour"];
							$text = "set count_oldyear_hoilday ='" . $countold . "',count_year_holiday='" . $countyear . "',sum_thisyear_holiday ='" . $total . "' ";
						}
					}
				}
			}
		} //type id

	} //get status-2
} //isset status= -2	

//status=0//////////////////////////////////////////////////////////////////////////////////////////////

if (isset($_GET["cancleapprove"])) {

	$cancleapprove = $_GET["cancleapprove"];
	$id = $_POST["id_holiday"];
	$_GET["emp"] = $_POST["emp"];
	$_POST["message"];
	// echo $_GET["cancleapprove"];
 	$select = "update tbl_holiday_detail
    set status_leave= 0 , cancle_approvesuscess_by='" . $userdeletby . "'
    ,date_cancle_apprvoesuscee=NOW(),cancle_approvesusecc_comment='" . $_POST["message"] . "',cancle_apprvoesuscess_section='" . $_SESSION["secname"] . "'
    where hoilday_detail_id ='" . $id . "'";
	$queryhd = mysqli_query($objconnect, $select);
 
?>


<?php } //isset status= 0
 
if($featchcount['type_id']==1){
	$sumResult = $featchSum['sum_thisyear_holiday']+$_POST['sumResult'];
     $selectupdatelimit = "UPDATE tbl_total_limit_holiday SET sum_thisyear_holiday =" .$sumResult. " WHERE Emp_id='" . $emp. "' and Financial_year ='" . $_POST['ficalyear'] . "' ";
     $queryhd = mysqli_query($objconnect, $selectupdatelimit);
 
}



?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<script type="text/javascript">
	alert("ยกเลิกการลาเรียบร้อย");
	location.href = 'index_admin.php';
</script>