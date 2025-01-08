<?php
require_once '../connect/connect.php'; 
require_once'header_css.php';
// echo $_GET['id'];

/////////////////////////////////////////เช็ควันลา
$select_datecheck = "SELECT start_holiday,end_holiday FROM tbl_total_limit_holiday  where status_limit = 1 and Emp_id='".$_GET['id']."'"; 
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
 ON pg.Position_id=emp.Position_id WHERE hd.status_leave>0 and holiday_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND hd.type_id=1 and hd.Emp_id='".$_GET["id"]."'AND lt.status_limit=1";
$query_count_limit = mysqli_query($objconnect,$count_limit);
$featch_count_limit = mysqli_fetch_array($query_count_limit);
$check_limitdate=$featch_count_limit["total"]+$featch_count_limit["sum_thisyear_holiday"];
$date_history_check=$featch_count_limit["total"];
$showficial_year=date("Y",strtotime($featch_count_limit["start_holiday"]))+543;
$show_name = $featch_count_limit["Titile_name"]." ".$featch_count_limit["Emp_name"]." ".$featch_count_limit["Emp_lastname"];
// /////////////////////////////////////////////////////////////////
  $select = "select lt.start_holiday,hd.sick_startdate,hd.sick_enddate,hd.hoilday_detail_name,hd.sick_type
,hd.type_id,(SUM(hd.sick_totalday)+SUM(hd.sick_totalhour))AS total 
 from tbl_holiday_detail hd INNER JOIN tbl_total_limit_holiday lt ON lt.Emp_id=hd.Emp_id WHERE hd.status_leave>0 and sick_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND hd.type_id=2 and hd.Emp_id='".$_GET['id']."' AND lt.status_limit=1 GROUP BY hd.hoilday_detail_id";
$query_select = mysqli_query($objconnect,$select);
// $featch_ficalyear=mysqli_fetch_array($query_select);

// echo $select;
?>

<!DOCTYPE html>
<html>

<body>
<head>
<div class="container-fluid">
	<div class="form-row">
		<div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12">
				<nav class="navbar navbar-light bg-light">
					<span class="navbar-brand mb-0 h1">ประวัติการลาป่วย/ลากิจ/ลาคลอดบุตร รายบุคคล</span>
					<input type="button" onclick="printDiv('divprint')" value="print a divssss!" />
				</nav>
		</div>
	</div>
</div>
	
</head>
<div id="divprint">
 <main>
 	<!-- START MAIN -->
 	<div class="container" style="margin-top: 5%;" id="print">
 		<div class="form-row">
 			<div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12" align="center" >
					<p>สำนักงานสุขภาพเขตที่ 4</p>
 			</div>
 			<div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12" align="center" >
					<p>ปีงบประมาณ <?=$showficial_year?></p>
 			</div>
 		</div>
 <div class="form-row  mb-4 ">
 			<div class="col-4 " align="center" >
 				<p>ชื่อ <?=$show_name?></p>
 			</div>
			<div class="col-4" align="center">
				<h3>สถิติการลาป่วย/ลากิจ/ลาคลอดบุตร</h3>
			</div>
 			<div class="col-4 "  align="center">
 				<p>ตำแหน่ง <?=$featch_count_limit['name']?></p>
 			</div>
 		</div>
			<div class="container" >
					<div class="form-row " >
						<div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12">
							<table class="table table-bordered" id="example"  style="width: 100%;">
								<thead>
	
									<tr align="center">
										<th colspan="1" rowspan="2">ปีงบประมาณ</th>
										<th colspan="1" rowspan="2">ประเภทการลา</th>
										<!-- <th colspan="1" rowspan="2">วันลาในปีงบประมาณ</th>
										<th colspan="1" rowspan="2">รวม(วัน)</th> -->
										<th rowspan="1" colspan="2">วันลาในปีงบประมาณ</th>
										<th colspan="1"rowspan="2">ครั้งที่</th>
										<th colspan="1"rowspan="2">จำนวน</th>
										<th colspan="1"rowspan="2">รวม</th>
							<!-- 			<th colspan="1"rowspan="2">คงเหลือ</th> -->
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
// $check_limit = 0;
			while ($count=mysqli_fetch_array($query_select)) {
$startdate=$count['sick_startdate'];
$enddate=$count['sick_enddate'];
$total=$count["total"];
$check_test=$check_test+$total;


// $dis_day= $check_limitdate-$check_test;

$fiscal_year=date("Y",strtotime($count["start_holiday"]))+543;
$this_year=10;

$num++;
				?>		
									<tr>
										<td><?=$fiscal_year ?></td>
										<td><?=$count["sick_type"]?></td>
									<!-- 	<td></td>
										<td></td> -->
										
									
	
			    
										<td><?=date('d/m/y',strtotime($startdate))?></td>
										<td><?=date('d/m/y',strtotime($enddate))?></td>
										<td><?=$num?></td>
										<td><?=$total?></td>
										<td><?=$check_test?></td>
										<td></td> 
										
								
									</tr>
										<?php }$check_test=0;?>
								</tbody>
							</table>
						</div>

					</div>
			</div>



 	</div>
 <!-- END MAIN -->
 </main>
</div>
</body>
</html>

<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel','print'
        ]
    } );
} );
</script>
<script>
        function printDiv(divName) {
        	console.log(document.getElementById("divprint"));
            var btnfilter = document.getElementById("example_filter");
            btnfilter.style.display = "none";
           // $(".dt-buttons").hide();
            $(".dt-buttons").removeAttr("style").hide();
         	$("#example_paginate").removeAttr("style").hide();
          $("#example_info").removeAttr("style").hide();
            var printContents = document.getElementById(divName).innerHTML;
            btnfilter.style.display = "block";
            $(".dt-buttons").show();
            $("#example_paginate").show();
            $("#example_info").show();
            var originalContents = document.body.innerHTML;
            
            document.body.innerHTML = printContents;


            window.print();
            
            document.body.innerHTML = originalContents;
        }
    </script>