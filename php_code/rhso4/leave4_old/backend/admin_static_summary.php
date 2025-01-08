<?php
include_once '../connect/connect.php';
include_once 'header_css.php';
 $type_Financial_year = $_POST['type_Financial_year'];
$status  = "";
/////////////////////////////////////////เช็ควันลา
  $select_datecheck = "SELECT start_holiday,end_holiday FROM tbl_total_limit_holiday  where Financial_year ='" . $type_Financial_year . "' ";
$query_datecheck = mysqli_query($objconnect, $select_datecheck);
$featch_datecheck = mysqli_fetch_array($query_datecheck);
$startdate_check = $featch_datecheck["start_holiday"];
$enddate_check  = $featch_datecheck["end_holiday"];

 
if ($_POST['type'] == 1) {
    // AND  tbl_employment.Emp_Status = 1 
   $select = "SELECT hoilday_detail_name,type_id,(sum(hoilday_totalday)+SUM(holiday_totalhour)) AS sumtype_1
from tbl_holiday_detail  INNER JOIN tbl_employment ON  tbl_holiday_detail.Emp_id=tbl_employment.Emp_id
WHERE tbl_holiday_detail.status_leave>0  AND tbl_holiday_detail.Financial_year = '" . $type_Financial_year . "'
and holiday_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=1
group by tbl_holiday_detail.type_id,tbl_holiday_detail.emp_id 
";
} else {
    // AND  tbl_employment.Emp_Status = 2
     $select = "SELECT hoilday_detail_name,holiday_positon,( 
sum(sick_totalday)+SUM(sick_totalhour))sumtype_2
from tbl_holiday_detail  INNER JOIN tbl_employment ON  tbl_holiday_detail.Emp_id=tbl_employment.Emp_id
WHERE tbl_holiday_detail.status_leave>0   AND tbl_holiday_detail.Financial_year = '" . $type_Financial_year . "'
and sick_startdate  
BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=2
group by tbl_holiday_detail.type_id,tbl_holiday_detail.emp_id ";
}
////////////////////////////////////////////////////


$query_select = mysqli_query($objconnect, $select);
$row_cnt = mysqli_num_rows($query_select);
 
$i = 0;
$emp_name = array();
$emp_some = array();
$color_rgb = array();

function randomColour() {
    // Found here https://css-tricks.com/snippets/php/random-hex-color/
    $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
    return $color;
}
 
while ($row = mysqli_fetch_array($query_select)) {

    if ($_POST["type"] == 1) {
        $emp_name[$i] = $row['hoilday_detail_name'];
        $emp_some[$i] = $row['sumtype_1'];
    } else {
        $emp_name[$i] = $row['hoilday_detail_name'];
        $emp_some[$i] = $row['sumtype_2'];
    }
    $color_rgb[$i] =randomColour()  ;

    $i++;
}
// 
// echo json_encode($emp_some);



// สี




?>
<!DOCTYPE html>
<html>
<style type="text/css">
@media print {

    #static_type {
        display: none;
    }

    #print {
        display: none;
    }

    #nav_sub {
        display: none;
    }

    #example_length,
    #example_filter {
        display: none;
    }

    .dt-buttons {
        display: none;
    }
}
</style>

<body>

    <div class="container-fluid" id="static" style="margin-right: 100%;">
        <div class="row">
            <div class="col-12">
                <ul class="nav justify-content-end" id="print">
                    <li class="nav-item">
                        <img src='../icon/print.png' width="25px;" height="30px;" onclick="window.print()">
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-12"><canvas id="myChart"></canvas></div>
        </div>
        <div class="row">
            <?php if ($_POST["type"] == 1) {
                // AND emp.Emp_Status = 1 
         $select_day1 = "
          SELECT holiday_positon,tbl_total_limit_holiday.fix_count_oldyear_holiday,tbl_total_limit_holiday.count_year_holiday,tbl_total_limit_holiday.sum_thisyear_holiday,tbl_employment.Emp_id,hoilday_detail_name,type_id,(sum(hoilday_totalday)+SUM(holiday_totalhour)) AS sumtype_1
          from tbl_holiday_detail  
          INNER JOIN tbl_employment ON  tbl_holiday_detail.Emp_id=tbl_employment.Emp_id
          INNER JOIN tbl_total_limit_holiday ON tbl_total_limit_holiday.Emp_id = tbl_holiday_detail.Emp_id
          WHERE tbl_holiday_detail.status_leave>0  
          AND tbl_total_limit_holiday.Financial_year = '" . $type_Financial_year . "'
          AND tbl_holiday_detail.Financial_year = '" . $type_Financial_year . "'
          and holiday_startdate 
          BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=1
          group by tbl_holiday_detail.type_id,tbl_holiday_detail.emp_id ";

                $query_day1 = mysqli_query($objconnect, $select_day1);


                // echo $select_day1;
            ?>
            <div class="col-12">
                <div class="card card-header text-white bg-primary ">สรุปการลาพักร้อน</div>
                <div class="card card-body">
                    <table id="example" class="table ">
                        <thead class="thead-light">
                            <tr>
                                <th>ชื่อ</th>
                                <th>แผนก</th>
                                <th>ยกยอดวันลาจากปีงบก่อน</th>
                                <th>วันลาในปีนี้</th>
                                <th>ลาไปแล้วทั้งหมด</th>
                                <th>วันคงเหลือ</th>
                                <th>ดูวันลารายบุคคล</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($feacth_status_1 = mysqli_fetch_array($query_day1)) { ?>
                            <tr>
                                <td><?php echo $feacth_status_1["hoilday_detail_name"] ?></td>
                                <td><?php echo $feacth_status_1["holiday_positon"] ?></td>
                                <td><?php echo $feacth_status_1["fix_count_oldyear_holiday"] ?> วัน</td>
                                <td><?php echo $feacth_status_1["count_year_holiday"] ?> วัน</td>
                                <td><?php echo $feacth_status_1["sumtype_1"] ?> วัน</td>
                                <td><?php echo($feacth_status_1["fix_count_oldyear_holiday"]+$feacth_status_1["count_year_holiday"])- $feacth_status_1["sumtype_1"]; ?>
                                    วัน</td>
                                <td><button type="button" class="btn btn-secondary"
                                        onclick="send_static(<?php echo $type_Financial_year ?>,<?php echo $feacth_status_1["Emp_id"] ?>)">คลิ๊กเพื่อสถิติรายบุคคล</button>
                                </td>
                            </tr>
                            <?php     } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } else if ($_POST["type"] == 2) {;
                // AND emp.Emp_Status = 1
             $select_day2 = "
                  SELECT sick_positon,tbl_employment.Emp_id,hoilday_detail_name,holiday_positon,( 
                    sum(sick_totalday)+SUM(sick_totalhour))sumtype_2
                    from tbl_holiday_detail  INNER JOIN tbl_employment ON  tbl_holiday_detail.Emp_id=tbl_employment.Emp_id
                    WHERE tbl_holiday_detail.status_leave>0   AND tbl_holiday_detail.Financial_year = '" . $type_Financial_year . "'
                    and sick_startdate  
                    BETWEEN '$startdate_check' AND '$enddate_check'  AND type_id=2
                    group by tbl_holiday_detail.type_id,tbl_holiday_detail.emp_id ";
                $query_day2 = mysqli_query($objconnect, $select_day2);
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
                            <?php while ($feacth_status_2 = mysqli_fetch_array($query_day2)) { ?>
                            <tr>

                                <td><?php echo $feacth_status_2["hoilday_detail_name"] ?></td>
                                <td><?php echo $feacth_status_2["sick_positon"] ?></td>
                                <td><?php echo $feacth_status_2["sumtype_2"] ?> วัน</td>
                                <td><button type="button" class="btn btn-secondary"
                                        onclick="send_static_type2(<?php echo $type_Financial_year ?>,<?php echo $feacth_status_2["Emp_id"]  ?>)">คลิ๊กเพื่อสถิติรายบุคคล</button>
                                </td>
                            </tr>
                            <?php     } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>

</body>

</html>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {

    type: 'bar',
    data: {
        labels: <?php echo json_encode($emp_name); ?>

            ,

        datasets: [{

            label: 'สรุปวันลา',
            data: <?php echo json_encode($emp_some); ?>,
            backgroundColor: <?php echo json_encode($color_rgb) ?>,
            borderColor: <?php echo json_encode($color_rgb) ?>,
            borderWidth: 2

        }]
    },

    options: {
        legend: {
            labels: {
                // This more specific font property overrides the global property
                fontColor: 'black',
                defaultFontFamily: 'Arial',
                defaultFontStyle: 'normal',
                defaultFontSize: 15

            }
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
            xAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontSize: 15,
                    fontColor: "black",
                }
            }]

        }


    },

});
</script>
<script type="text/javascript">

</script>
<script type="text/javascript">
$(document).ready(function() {

    $("#example").DataTable({
        dom: 'lBfrtip',
        buttons: ['excel']
    });
});
</script>
<script type="text/javascript">
function send_static(fs_y, emp_id) {
    window.open("admin_static_detailperson.php?id=" + emp_id + "&&fs_y=" + fs_y, "width=500,height=750");
}
</script>
<script type="text/javascript">
function send_static_type2(fs_y, emp_id) {
    window.open("admin_static_detailperson_sick.php?id=" + emp_id + "&&fs_y=" + fs_y, "width=500,height=750");
}
</script>