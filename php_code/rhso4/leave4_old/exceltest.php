
<?php
include_once 'connect/connect.php';
@$act=$_GET['act'];
if($act=='excel'){
header("Content-Type: application/vnd.ms-excel"); // ประเภทของไฟล์
header('Content-Disposition: attachment; filename="myexcel.xls"'); //กำหนดชื่อไฟล์
header("Content-Type: application/force-download"); // กำหนดให้ถ้าเปิดหน้านี้ให้ดาวน์โหลดไฟล์
header("Content-Type: application/octet-stream"); 
header("Content-Type: application/download"); // กำหนดให้ถ้าเปิดหน้านี้ให้ดาวน์โหลดไฟล์
header("Content-Transfer-Encoding: binary"); 
header("Content-Length: ".filesize("myexcel.xls"));   
 
@readfile($filename); 
}

/////////////////////////////////////////เช็ควันลา
$select_datecheck = "SELECT start_holiday,end_holiday FROM tbl_total_limit_holiday  where status_limit = 1 and Emp_id=1"; 
$query_datecheck = mysqli_query($objconnect,$select_datecheck);
$featch_datecheck = mysqli_fetch_array($query_datecheck);
$startdate_check= $featch_datecheck["start_holiday"];
$enddate_check  = $featch_datecheck["end_holiday"];


// echo $startdate_check;
/////////////////////////////////////////////////////////////////////

  $count_limit = "SELECT emp.Titile_name,emp.Emp_name,emp.Emp_lastname,pg.name,lt.start_holiday,lt.sum_thisyear_holiday,hd.holiday_enddate,hd.holiday_startdate,hd.hoilday_detail_name
,hd.type_id ,hd.hoilday_totalday,hd.holiday_totalhour 
,SUBSTRING(lt.start_holiday, 1,4) AS sub
,(SUM(hd.hoilday_totalday)+SUM(hd.holiday_totalhour))AS total
 from tbl_holiday_detail hd INNER JOIN tbl_total_limit_holiday lt
 ON lt.Emp_id=hd.Emp_id
 INNER JOIN tbl_employment emp
 ON emp.Emp_id=hd.Emp_id
 INNER JOIN tbl_position_group pg 
 ON pg.Position_id=emp.Position_id WHERE hd.status_leave=3 and holiday_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND hd.type_id=1 and hd.Emp_id=1 AND lt.status_limit=1";
$query_count_limit = mysqli_query($objconnect,$count_limit);
$featch_count_limit = mysqli_fetch_array($query_count_limit);
$check_limitdate=$featch_count_limit["total"]+$featch_count_limit["sum_thisyear_holiday"];
$date_history_check=$featch_count_limit["total"];
$showficial_year=date("Y",strtotime($featch_count_limit["start_holiday"]))+543;
$show_name = $featch_count_limit["Titile_name"]." ".$featch_count_limit["Emp_name"]." ".$featch_count_limit["Emp_lastname"];

  $select = "SELECT lt.start_holiday,lt.sum_thisyear_holiday,hd.holiday_enddate,hd.holiday_startdate,hd.hoilday_detail_name
,hd.type_id 
,SUBSTRING(lt.start_holiday, 1,4) AS sub,
(SUM(hd.hoilday_totalday)+SUM(hd.holiday_totalhour))AS total
from tbl_holiday_detail hd INNER JOIN tbl_total_limit_holiday lt ON lt.Emp_id=hd.Emp_id
WHERE hd.status_leave=3 
and holiday_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND hd.type_id=1 and hd.Emp_id=1 AND lt.status_limit=1 GROUP BY hd.hoilday_detail_id";
$query_select = mysqli_query($objconnect,$select);
// $featch_ficalyear=mysqli_fetch_array($query_select);

?>
<!DOCTYPE html>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">

	<head>
		<meta charset="utf-8">
		<title>devbanban</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<div class="col-md-5">
					<br /><br /><br />
					<h4> ::ตย. PHP EXPORT TO EXCEL by devbanban.com ::
					</h4>
					
					<p>
						<a href="?act=excel" class="btn btn-primary"> Export->Excel </a>
					</p>
					
					<table border="1" class="table table-hover">
						<thead>
							<tr align="center">
										<th colspan="1" rowspan="2">งบประมาณ</th>
										<th colspan="1" rowspan="2">วันลาสะสมยกมา</th>
										<th colspan="1" rowspan="2">วันลาในปีงบประมาณ</th>
										<th colspan="1" rowspan="2">รวม(วัน)</th>
										<th rowspan="1" colspan="2">วันลาในปีงบประมาณบวก</th>
										<th colspan="1"rowspan="2">ครั้งที่</th>
										<th colspan="1"rowspan="2">จำนวน</th>
										<th colspan="1"rowspan="2">รวม</th>
										<th colspan="1"rowspan="2">คงเหลือ</th>
										<th colspan="1"rowspan="2">หมายเหตุ</th>
									</tr>
									<tr align="center"> 
										<th> ตั้งแต่</th>
										<th >ถึง</th>
									</tr>
						</thead>
						<tbody>
						<?php $num=0;  
$check_test=0;	
$check_limit = 0;
			while ($count=mysqli_fetch_array($query_select)) {
$startdate=$count['holiday_startdate'];
$enddate=$count['holiday_enddate'];
$total=$count["total"];
$check_test=$check_test+$total;


$dis_day= $check_limitdate-$check_test;

$fiscal_year=date("Y",strtotime($count["start_holiday"]))+543;
$this_year=10;

$num++;
				?>		
									<tr>
										<td><?=$fiscal_year ?></td>
										<td><?=$date_history_check?></td>
										<td><?=$this_year?></td>
										<td><?=$date_history_check+$this_year?></td>
										
									
	
			    
										<td><?=date('d/m/y',strtotime($startdate))?></td>
										<td><?=date('d/m/y',strtotime($enddate))?></td>
										<td><?=$num?></td>
										<td><?=$total?></td>
										<td><?=$check_test?></td>
										<td><?=$dis_day?></td>
										<td></td>
								
									</tr>
										<?php }$check_test=0;?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>