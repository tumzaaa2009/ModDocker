<?php 
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

$userdeletby =  $_SESSION["name"].' '.$_SESSION["lastname"];
// echo $userdeletby;

include_once 'connect/connect.php';
 $select = "update tbl_holiday_detail
set status_leave= -1 , delete_by='".$userdeletby."'
,datedelete_by=NOW()
where hoilday_detail_id ='".$_GET["id"]."'";
$queryhd = mysqli_query($objconnect,$select);
// echo 'string'.$queryhd;

if ($_GET["type_id"]==1) {
// เอาวันกลับ
$selectcount = "SELECT detail.hoilday_totalday today,detail.holiday_totalhour tohour,limithd.count_oldyear_hoilday countold,limithd.count_year_holiday countyear,limithd.sum_thisyear_holiday sumyear,emp.group_id gid
FROM tbl_employment emp 
INNER JOIN tbl_holiday_detail detail ON emp.Emp_id=detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithd ON limithd.Emp_id = emp.Emp_id
 where  detail.hoilday_detail_id ='".$_GET["id"]."' and emp.Emp_id ='".$_GET["emp"]."'  " ; 
$querycount = mysqli_query($objconnect,$selectcount);
$numcount = mysqli_num_rows($querycount);
$featchcount = mysqli_fetch_array($querycount);

//บวกวันเพื่ออัพเดทกลับ
$day =$featchcount["today"] ;
$hour=$featchcount["tohour"];
$sum = $day+$hour; 

//update tbl_limit
$countold = $featchcount["countold"];
$countyear =  $featchcount["countyear"];
$sumtotalcount =$countold+$countyear;

// echo 'string'.$sumyearhoilday;
$g_id =$featchcount["gid"];
// ///////////////////////////////////////////////////////////////////////////////////
$text ="";



 if( $_SESSION["Username"]="pratoom"){;

	//บวกวันเพื่ออัพเดทกลับ
$day =$featchcount["today"] ;
$hour=$featchcount["tohour"];
$sum = $day+$hour; 

//update tbl_limit
$countold = $featchcount["countold"];
$countyear =  $featchcount["countyear"];
$total =$countold+$countyear;
if ($sum>20 || $sum<20 || $sum==20) {
			if($countold<20){
			// ปรับใหม่ //

				$countold= $countold+$sum;
				$countyear =$countyear;
				$total =$countold+$countyear;

				$discount_new =10-$countyear; 
				$discount_new;
				$countold=$countold-$discount_new;
				$countyear=$countyear+$discount_new;
				$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
						if($countold>20){ 
							$x = 20;
							$y = $countold-$x;
							// echo 'string',$y;
							$countyear = $countyear+$y;
							$total  = $x+$countyear;
						$text = "set count_oldyear_hoilday ='".$x."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
						}

						}else if ($countold==0){// อันใหม่
				$countold= $countold+$sum;
				$countyear =$countyear;
				$total =$countold+$countyear;
				$text = "set count_year_holiday='".$total."',sum_thisyear_holiday ='".$total."' " ;
							
						}
			else  if ($countold>20) { 

			 	// echo 'if';
			 	$countold=$x=20;
				$discount = $sum-$x ;
				$countyear =$countyear+$discount; 
				$total = $countyear+$countold;
				$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 }else{
			 	// echo 'elseggssss';

			 	$countold=$countold;
			 	$countyear =$countyear+$sum;
			 	$total =$countyear+$countold;
			  $text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 }
			 if ($featchcount["sumyear"]<=20) {//เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
			 	 $check = $featchcount["sumyear"]+$sum; //บวกวันเพิ่ม
			 	 // echo "ssss",$check;
			 
			 	  if ($check>20) { //ถ้าบวกกันแล้วได้มากกว่า 10 

			 	  	// echo 'string'.$check."<br>";
			 	  	// echo 'ssdssd'.$countyear;
					$countyear =20;
					$discount = $check-$countyear;//ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
					// echo 'discount'.$discount;
					$countold = $discount;
					$total =$countyear+$countold;

					///เงือนไขสลับ ขขขขขขข
					$text = "set count_oldyear_hoilday ='".$countyear."',count_year_holiday='".$countold."',sum_thisyear_holiday ='".$total."' " ;
			 	  } 
			 }	 


			}



}






else if ($g_id!=1) {

//บวกวันเพื่ออัพเดทกลับ
$day =$featchcount["today"] ;
$hour=$featchcount["tohour"];
$sum = $day+$hour; 

//update tbl_limit
$countold = $featchcount["countold"];
$countyear =  $featchcount["countyear"];
$total =$countold+$countyear;
if ($sum>5 || $sum<5 || $sum==5) {
			if($countold<5){
				// echo 'ifggg';
		// ปรับใหม่ //

				$countold= $countold+$sum;
				$countyear =$countyear;
				$total =$countold+$countyear;

				$discount_new =10-$countyear; 
				$discount_new;
				$countold=$countold-$discount_new;
				$countyear=$countyear+$discount_new;
				$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			
						if($countold>5){
							$x = 5;
							$y = $countold-$x;
							echo 'string',$y;
							$countyear = $countyear+$y;
							$total  = $x+$countyear;
						$text = "set count_oldyear_hoilday ='".$x."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
						}

						}
			else  if ($countold>5) {
			 	// echo 'if';
			 	$countold=$x=5;
				$discount = $sum-$x ;
				$countyear =$countyear+$discount; 
				$total = $countyear+$countold;
				$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 }else{
			 	// echo 'elseggssss';

			 	$countold=$countold;
			 	$countyear =$countyear+$sum;
			 	$total =$countyear+$countold;
			  $text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 }
			 	 if ($featchcount["sumyear"]<=10) { //เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
			 	 $check = $featchcount["sumyear"]+$sum; //บวกวันเพิ่ม
			 	 // echo "ssss",$check;
			 	  if ($check>10) { //ถ้าบวกกันแล้วได้มากกว่า 10
			 	  	// echo 'string'.$check."<br>";
			 	  	// echo 'ssdssd'.$countyear;
					$countyear =10;
					$discount = $check-$countyear;//ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
					// echo 'discount'.$discount;
					$countold = $discount;
					$total =$countyear+$countold;
					$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 	  } 
			 }
			}

}
else{ // id ==1 
	//บวกวันเพื่ออัพเดทกลับ
$day =$featchcount["today"] ;
$hour=$featchcount["tohour"];
$sum = $day+$hour; 

//update tbl_limit
$countold = $featchcount["countold"];
$countyear =  $featchcount["countyear"];
$total =$countold+$countyear;
if ($sum>10 || $sum<10 || $sum==10) {
			if($countold<10){
				
		// ปรับใหม่ //

				$countold= $countold+$sum;
				$countyear =$countyear;
				$total =$countold+$countyear;

				$discount_new =10-$countyear; 
				$discount_new;
				$countold=$countold-$discount_new;
				$countyear=$countyear+$discount_new;
				$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
						if($countold>10){
							$x = 10;
							$y = $countold-$x;
							// echo 'string',$y;
							$countyear = $countyear+$y;
							$total  = $x+$countyear;
						$text = "set count_oldyear_hoilday ='".$x."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
						}

						}
			else  if ($countold>10) {
			 	// echo 'if';
			 	$countold=$x=10;
				$discount = $sum-$x ;
				$countyear =$countyear+$discount; 
				$total = $countyear+$countold;
				$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 }else{
			 	// echo 'elseggssss';

			 	$countold=$countold;
			 	$countyear =$countyear+$sum;
			 	$total =$countyear+$countold;
			  $text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 }
			 if ($featchcount["sumyear"]<=10) {//เพิ่มใหม่ //วันหยุดปีน้อยกว่า10 //ไม่ต้องปรับเลขเพราะเป็นของปีนี้
			 	 $check = $featchcount["sumyear"]+$sum; //บวกวันเพิ่ม
			 	 // echo "ssss",$check;
			 	  if ($check>10) { //ถ้าบวกกันแล้วได้มากกว่า 10
			 	  	// echo 'string'.$check."<br>";
			 	  	// echo 'ssdssd'.$countyear;
					$countyear =10;
					$discount = $check-$countyear;//ส่วนต่างมีค่าเป็นบวกเอาไปทดกับปีก่อน
					// echo 'discount'.$discount;
					$countold = $discount;
					$total =$countyear+$countold;
					$text = "set count_oldyear_hoilday ='".$countold."',count_year_holiday='".$countyear."',sum_thisyear_holiday ='".$total."' " ;
			 	  } 
			 }

			}

}



///////////////////////////////////////////////////////////////////////////////////////
// update วัันกลับ
$selectupdatelimit = "update tbl_total_limit_holiday
$text where
Emp_id='".$_GET['emp']."'";
$queryhd = mysqli_query($objconnect,$selectupdatelimit);
}//if GET_typeid =1
?>

<script>
alert("ลบข้อมูลเสร็จสิน");
location.href="index.php?ht=history";
</script>