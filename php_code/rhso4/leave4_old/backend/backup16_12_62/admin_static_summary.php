<?php 
include_once '../connect/connect.php';
include_once'header_css.php';

$status  = "";
/////////////////////////////////////////เช็ควันลา
$select_datecheck = "SELECT start_holiday,end_holiday FROM tbl_total_limit_holiday  where status_limit = 1 "; 
$query_datecheck = mysqli_query($objconnect,$select_datecheck);
$featch_datecheck = mysqli_fetch_array($query_datecheck);
$startdate_check= $featch_datecheck["start_holiday"];
$enddate_check  = $featch_datecheck["end_holiday"];

echo 'string';
/////////////////////////////////////////////////////////////////////
$_POST['type'];
if ($_POST['type']==1) {

   $select = "SELECT hoilday_detail_name,type_id,(sum(hoilday_totalday)+SUM(holiday_totalhour)) AS sumtype_1
from tbl_holiday_detail
WHERE tbl_holiday_detail.status_leave>0  
and holiday_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=1
group by type_id,emp_id 
" ;

}else {
    
   $select = "SELECT hoilday_detail_name,holiday_positon,( 
sum(sick_totalday)+SUM(sick_totalhour))sumtype_2
from tbl_holiday_detail
WHERE tbl_holiday_detail.status_leave>0  
and sick_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=2
group by type_id,emp_id ";

}
////////////////////////////////////////////////////


$query_select = mysqli_query($objconnect,$select);
$i=0;
$emp_name=array();
$emp_some=array();
while ($row= mysqli_fetch_array($query_select)) {
    if ($_POST["type"]==1) {
     $emp_name[$i] = $row['hoilday_detail_name'];
     $emp_some[$i] = $row['sumtype_1'];   
    }else{
     $emp_name[$i] = $row['hoilday_detail_name'];
     $emp_some[$i] = $row['sumtype_2'];   
    }

    
$i++;}
// echo json_encode($emp_some);
?>
<!DOCTYPE html>
<html>
<style type="text/css">
    @media print
{

#static_type { display: none; }
#print{ display: none; }
#nav_sub{ display: none; }

}
</style>
<body>

	<div class="container-fluid" id="static" style="margin-right: 100%;">
    <div class="row">
<div class="col-12">
<ul class="nav justify-content-end" id="print">
  <li class="nav-item">
   <img src='../icon/print.png' width="25px;" height="30px;"   onclick="window.print()">
  </li>
</ul>
</div>
    </div>

		<div class="row">  <div class="col-12"><canvas id="myChart" ></canvas></div>
		 </div>
		 <div class="row">
		 	    <?php if ($_POST["type"]==1) {
$select_day1="
SELECT d.Emp_id,d.hoilday_detail_name,d.holiday_positon,( SUM(d.hoilday_totalday)+SUM(d.holiday_totalhour))sumtype_1,l.sum_thisyear_holiday
 FROM tbl_holiday_detail d INNER JOIN tbl_total_limit_holiday l  ON d.Emp_id=l.Emp_id  
 WHERE holiday_startdate  BETWEEN '$startdate_check' AND '$enddate_check'  AND d.type_id=1 AND d.status_leave>0 
  group BY d.type_id, d.emp_id ";

$query_day1 = mysqli_query($objconnect,$select_day1);


// echo $select_day1;
?>
            <div class="col-12">
		 		<div class="card card-header text-white bg-primary ">สรุปการลาพักร้อน</div>
		 		<div class="card card-body">
		 <table id="example" class="table " >
		 			<thead class="thead-light" >
		 				<tr>
		 					<th>ชื่อ</th>
                            <th>แผนก</th>
                            <th>ลาไปแล้วทั้งหมด</th>
                            <th>วันคงเหลือ</th>
                            <th>ดูวันลารายบุคคล</th>
		 				</tr>
		 			</thead>
		 			<tbody>
  <?php  while ($feacth_status_1=mysqli_fetch_array($query_day1)) {?> 
<tr >
            <td><?=$feacth_status_1["hoilday_detail_name"]?></td>
            <td><?=$feacth_status_1["holiday_positon"]?></td>
            <td><?=$feacth_status_1["sumtype_1"]?> วัน</td>
            <td><?=$feacth_status_1["sum_thisyear_holiday"]?> วัน</td>
            <td><button type="button"  class="btn btn-secondary" 
                onclick="send_static(<?=$feacth_status_1["Emp_id"] ?>)">คลิ๊กเพื่อสถิติรายบุคคล</button></td>
 </tr>
		        <?php     }?> 			
            </tbody>
		 		</table>
		 		</div>
		 	</div>
         <?php } else if ($_POST["type"]==2) {;
$select_day2="
SELECT Emp_id,hoilday_detail_name,sick_positon,( 
sum(sick_totalday)+SUM(sick_totalhour))sumtype_2
from tbl_holiday_detail
WHERE status_leave>0
and sick_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=2
group by type_id,emp_id ";

$query_day2 = mysqli_query($objconnect,$select_day2);


?>
            <div class="col-12">
                <div class="card card-header text-white bg-primary ">สรุปการลาป่วย</div>
                <div class="card card-body">
              <table id="example" class="table " style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th>ชื่อ</th>
                            <th>แผนก</th>
                            <th>ลาไปแล้วทั้งหมด</th>
                     <th>ดูวันลารายบุคคล</th>
                        </tr>
                    </thead>
                    <tbody>
  <?php  while ($feacth_status_2=mysqli_fetch_array($query_day2)) {?> 
<tr >
            <td><?=$feacth_status_2["hoilday_detail_name"]?></td>
            <td><?=$feacth_status_2["sick_positon"]?></td>
            <td><?=$feacth_status_2["sumtype_2"]?> วัน</td>
<td><button type="button"  class="btn btn-secondary" 
                onclick="send_static_type2(<?=$feacth_status_2["Emp_id"]?>)">คลิ๊กเพื่อสถิติรายบุคคล</button></td>
 </tr>
                <?php     }?>           
            </tbody>
                </table>
                </div>
            </div>
         <?php }?>

		 </div>
	</div>

</body>
</html>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
       type: 'bar',
    data: {
        labels:<?=json_encode($emp_name);?>
    
        ,
        datasets: [{


            label: 'วันลา พักร้อน',
            data:<?=json_encode($emp_some);?>
            ,
          

            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1

        }]
    },
    options: {
          legend: {
            labels: {
                // This more specific font property overrides the global property
                fontColor: 'red',
                defaultFontFamily:'Arial',
                defaultFontStyle :'normal' 
            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        
        }


    }

});

</script>
<script type="text/javascript">
    console.log(document.getElementById("nav_sub"));
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel',
        ]
    } );
} );
</script>
<script type="text/javascript">
    function send_static(emp_id){
window.open("admin_static_detailperson.php?id="+emp_id, "", "width=500,height=750");
    }
</script>
<script type="text/javascript">
    function send_static_type2(emp_id){
 window.open("admin_static_detailperson_sick.php?id="+emp_id, "", "width=500,height=750");
    }
</script>