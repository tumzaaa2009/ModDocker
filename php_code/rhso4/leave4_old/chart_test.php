<?php

include_once 'connect/connect.php';
//เช็ควันลางบประมาณ
$select_datecheck = "SELECT start_holiday,end_holiday,Financial_year FROM tbl_total_limit_holiday WHERE emp_id ='".$_SESSION["Emp_id"]."' AND status_limit = 1 "; 
$query_datecheck = mysqli_query($objconnect,$select_datecheck);
$featch_datecheck = mysqli_fetch_array($query_datecheck);
$startdate_check= $featch_datecheck["start_holiday"];
$enddate_check  = $featch_datecheck["end_holiday"];

 
/////////////////////////////////////////////////////
// $date_start=$_POST["date_start"];
// $date_end=$_POST["date_end"];
/////////////////////////////////////////////////////
////////////////////////////////////////////////////

///////////////////////////////////////////////////
$text = "";
// if ($typeleave==1  ) {
//     $typeleave = "tbl_holiday_detail.type_id=1";
 
// }else if($typeleave ==1 ) {
//     $typeleave = "tbl_holiday_detail.type_id=1";

// }else if($typeleave==2 ) {
//     $typeleave = "tbl_holiday_detail.type_id=2";
 
// }else if ($typeleave ==2 ) {
//  $typeleave = "tbl_holiday_detail.type_id=2";

// }else if($typeleave==1 ) {
//     $typeleave = "tbl_holiday_detail.type_id=1";
 
// }else if ($typeleave ==1) {
//  $typeleave = "tbl_holiday_detail.type_id=1";
 
// }else if($typeleave==2 ) {
//     $typeleave = "tbl_holiday_detail.type_id=2";
   
// }else if ($typeleave ==2 ) {
//  $typeleave = "tbl_holiday_detail.type_id=2";
 
// }else if($typeleave==1 ) {
//     $typeleave = "tbl_holiday_detail.type_id=1";
 
// }else if ($typeleave ==1 ) {
//  $typeleave = "tbl_holiday_detail.type_id=1";
 
// }else if($typeleave==2  ) {
//     $typeleave = "tbl_holiday_detail.type_id=2";

// }else if ($typeleave ==2 ) {
//  $typeleave = "tbl_holiday_detail.type_id=2";
//   }

// SUM(hoilday_totalday)AS sum_day,SUM(holiday_totalhour)AS sum_hr,
//////////////////////////////////////////////////
 $text = "SELECT *,holiday_startdate,holiday_enddate,holiday_startime,holiday_endtime ,type_id
,(case 
when type_id = 1 then \"ลาพักร้อน\"
when type_id = 2 then \"ลาป่วย\"
End
) AS type_name
,
(case 
when type_id=1 then ((SELECT 
SUM(hoilday_totalday)+SUM(holiday_totalhour)
FROM tbl_holiday_detail where status_leave>0  and  Financial_year ='".$featch_datecheck['Financial_year']."' and
Emp_id='".$_SESSION["Emp_id"]."' AND holiday_startdate BETWEEN '$startdate_check' AND '$enddate_check'
))
when type_id=2 then ((SELECT 
SUM(sick_totalday)+SUM(sick_totalhour)
FROM tbl_holiday_detail where status_leave>0  and Emp_id='".$_SESSION["Emp_id"]."' and
sick_startdate BETWEEN '$startdate_check' AND '$enddate_check' and  Financial_year ='".$featch_datecheck['Financial_year']."' 
 ))
END )sumtype
 FROM tbl_holiday_detail
where tbl_holiday_detail.status_leave > 0  and tbl_holiday_detail.Emp_id='".$_SESSION["Emp_id"]."'  and  Financial_year = '".$featch_datecheck['Financial_year']."'
GROUP BY type_id

";
// and $typeleave
// echo $text;
$result2 = mysqli_query($objconnect,$text); 
$dataPoints =array();
$dataPointsY = array();
$title=array();
$num =mysqli_num_rows($result2);
// echo $text;
$i=0;
 while($row = mysqli_fetch_array($result2)) {
       
             $title[$i]=$row['type_name'];
             $dataPoints[$i]=$row['sumtype'];
                 $i++;
  
          }
// echo json_encode( $dataPoints);

?>
<!DOCTYPE HTML>
<html>

<head>

</head>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card text-white bg-secondary  ">
                    <h3> สรุปการลาประจำปี</h3>
                </div>
                <?php 


///เช็ควันลา ตามปีงบประมาณ //tyep1
  $select_day1="SELECT 
( SUM(hoilday_totalday)+SUM(holiday_totalhour))sumtype_1
FROM tbl_holiday_detail 
WHERE tbl_holiday_detail.Emp_id='". $_SESSION["Emp_id"]."'
AND holiday_startdate BETWEEN '$startdate_check' AND '$enddate_check'and type_id=1 AND status_leave>0  and  Financial_year = '".$featch_datecheck['Financial_year']."'";
$query_day1 = mysqli_query($objconnect,$select_day1);
$fetch_query = mysqli_fetch_array($query_day1);


// $num_check = mysqli_num_rows($query_day);
///////////////////////////////////////////////////
///// type2
  $select_day2="SELECT hoilday_detail_id,sick_type,sick_totalday,sick_totalhour
 -- (SUM(sick_totalday)+SUM(sick_totalhour))AS sum_type_2
FROM tbl_holiday_detail 
WHERE tbl_holiday_detail.Emp_id='". $_SESSION["Emp_id"]."'
and
sick_startdate BETWEEN '$startdate_check' AND '$enddate_check'and type_id=2  AND status_leave>0 
and  Financial_year = '".$featch_datecheck['Financial_year']."'
  ";
$query_day2 = mysqli_query($objconnect,$select_day2);

$sick_type_1 = 0 ;


$sick_type_2 = 0 ; 
while ($fetch_query2 = mysqli_fetch_array($query_day2)) {
    if ($fetch_query2["sick_type"]=="ป่วย") {
   
      $sick_arr1[0] =$sick_type_1 = $sick_type_1+$fetch_query2["sick_totalday"]+$fetch_query2["sick_totalhour"];

     	
    } else if ($fetch_query2["sick_type"]=="ขอลากิจส่วนตัว"){
    	
    	$sick_arr2[0]=$sick_type_2 = $sick_type_2+$fetch_query2["sick_totalday"]+$fetch_query2["sick_totalhour"];
     	
    }



}
 

///// end type2

// echo   $select_day2;
///////
$select_limit = "select sum_thisyear_holiday from tbl_total_limit_holiday WHERE  Emp_id='". $_SESSION["Emp_id"]."' AND status_limit = 1" ; 
 $query_limit = mysqli_query($objconnect,$select_limit);
 $featch_check = mysqli_fetch_array($query_limit);



 // echo $select_day;
 ?>
                <div class="form-row">
                    <div class="col-7"><canvas id="myChart"></canvas></div>
                    <div class="col-5">
                        <table class="table table-hover" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ประเภท</th>
                                    <th>ลาไป</th>
                                    <th>คงเหลือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>ลาพักร้อน</td>
                                    <td><?php if ($fetch_query["sumtype_1"]=="") {
              echo '0';
            }else echo $fetch_query["sumtype_1"]?> วัน</td>
                                    <td><?= $featch_check["sum_thisyear_holiday"]?></td>
                                </tr>
                                <tr class="table table-primary">
                                    <td> ลาป่วย</td>
                                    <td>
                                        <?php  if ($sick_arr1[0]==0) {
            		echo "0";
            	}else{echo $sick_arr1[0];}?>
                                        <!--             	<?php 
            	if ($fetch_query2["sum_type_2"]=="") { echo '0';   }else {echo $fetch_query2["sum_type_2"] ;}?> วัน -->
                                    </td>
                                    <td>-</td>
                                </tr>
                                <tr>
                                    <td>ลากิจส่วนตัว</td>
                                    <td><?php if ($sick_arr2[0]==0) {	echo '0';		}else{ echo $sick_arr2[0] ;}?></td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer card text-white bg-secondary  "></div>



            </div>
        </div>

    </div>
</div>
<hr>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode( $title);?> ,
        datasets: [{
            label: <?php  echo json_encode( $title); ?>,
            data: <?php echo json_encode( $dataPoints);?>,


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

        }],
        

    },
    options: {
        legend: {
            labels: {
                // This more specific font property overrides the global property
                fontColor: 'red',
                defaultFontFamily: 'Arial',
                defaultFontStyle: 'normal'
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
<!-- <script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
       type: 'bar',
  data: {
    labels: ['1', '2', '3', '4', '5'],

    datasets: [{
      label: 'A',
      yAxisID: 'A',
      data: [100, 96, 84, 76, 69]
    }, {
      label: 'B',
      yAxisID: 'B',
      data: [1, 1, 1, 1, 0]
    }
 
           
    ]
  },
  options: {
    scales: {
      yAxes: [{
        id: 'A',
        type: 'linear',
        position: 'left',
      }, {
        id: 'B',
        type: 'linear',
        position: 'right',
        ticks: {
          max: 1,
          min: 0
        }
      }]
    }
  }
});
</script>     -->



</body>

</html>
<script type="text/javascript">
// function submit () {
// var submit = document.getElementById("confrim").value ; 
// if (submit==1) {
//  document.getElementById("myChart").hidden =false;
// }

// }
</script>