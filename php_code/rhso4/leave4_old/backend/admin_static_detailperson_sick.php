<?php
require_once '../connect/connect.php';
require_once 'header_css.php';
// echo $_GET['id'];

/////////////////////////////////////////เช็ควันลา
$year_start = date("Y") + 542;
$year_END = date("Y") + 543;
 $select_datecheck = "SELECT start_holiday,end_holiday FROM tbl_total_limit_holiday  where Financial_year ='" . $_GET['fs_y'] . "'";
$query_datecheck = mysqli_query($objconnect, $select_datecheck);
$featch_datecheck = mysqli_fetch_array($query_datecheck);
$startdate_check = $featch_datecheck["start_holiday"];
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
BETWEEN '$startdate_check' AND '$enddate_check'  AND hd.type_id=1 and hd.Emp_id='" . $_GET["id"] . "'AND lt.status_limit=1";

$query_count_limit = mysqli_query($objconnect, $count_limit);
$featch_count_limit = mysqli_fetch_array($query_count_limit);
$check_limitdate = $featch_count_limit["total"] + $featch_count_limit["sum_thisyear_holiday"];
$date_history_check = $featch_count_limit["total"];
$showficial_year = date("Y", strtotime($featch_count_limit["start_holiday"])) + 544;
$show_name = $featch_count_limit["Titile_name"] . " " . $featch_count_limit["Emp_name"] . " " . $featch_count_limit["Emp_lastname"];
// /////////////////////////////////////////////////////////////////
 $select = "select lt.start_holiday,hd.sick_startdate,hd.sick_enddate,hd.hoilday_detail_name,hd.sick_type
,hd.type_id,hd.sick_totalday,hd.sick_totalhour 
 from tbl_holiday_detail hd INNER JOIN tbl_total_limit_holiday lt ON lt.Emp_id=hd.Emp_id WHERE hd.status_leave>0 and sick_startdate 
BETWEEN '$startdate_check' AND '$enddate_check'  AND hd.type_id=2 and hd.Emp_id='" . $_GET["id"] . "'  AND hd.Financial_year = '" . $_GET['fs_y'] . "' and lt.Financial_year = '" . $_GET['fs_y'] . "'  GROUP BY hd.hoilday_detail_id ORDER BY  hd.sick_startdate ASC ";
$query_select = mysqli_query($objconnect, $select);
// $featch_ficalyear=mysqli_fetch_array($query_select);
$num_query_select = mysqli_num_rows($query_select);
$sum_num_query = 1 + $num_query_select;
// echo $select;
?>

<!DOCTYPE html>
<html>

<body>
    <div id="divprint">

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

        </head>
        <main>
            <!-- START MAIN -->
            <table class="table table-borderless" id="tableData">
                <div class="container" style="margin-top: 5%;" id="print">
                    <thead id="header">
                        <div class="form-row">
                            <tr>
                                <td colspan="12">
                                    <div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12" align="center">
                                        <p>สำนักงานสุขภาพเขตที่ 4</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="12">
                                    <div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12" align="center">
                                        <p>ปีงบประมาณ <?php echo $_GET['fs_y'] ?></p>
                                    </div>
                                </td>
                            </tr>
                            <!-- <div class="form-row  mb-4 " > -->
                            <tr>
                                <div class="col-4 " align="center">
                                    <td colspan="2">
                                        <p>ชื่อ <?= $show_name ?></p>
                                    </td>
                                </div>

                                <div class="col-4">
                                    <td colspan="6" align="center">
                                        <h3>สถิติการลาป่วย/ลากิจ/ลาคลอดบุตร</h3>
                                    </td>
                                </div>
                                <div class="col-4 " align="center">
                                    <td colspan="4">
                                        <p>ตำแหน่ง <?= $featch_count_limit['name'] ?></p>
                                    </td>
                                </div>
                            </tr>
                            <!-- </div> -->
                        </div>
                    </thead>
                    <div class="container">
                        <div class="form-row ">
                            <div class="col-12 col-md-12 col-xl-12 col-sm-12 col-lg-12">
                                <thead>
                                    <tr align="center">
                                        <th colspan="1" rowspan="2">ปีงบประมาณ</th>
                                        <th colspan="1" rowspan="2">ประเภทการลา</th>
                                        <!-- <th colspan="1" rowspan="2">วันลาในปีงบประมาณ</th>
										<th colspan="1" rowspan="2">รวม(วัน)</th> -->
                                        <th rowspan="1" colspan="2">วันลาในปีงบประมาณ</th>
                                        <th colspan="1" rowspan="2">ครั้งที่</th>
                                        <th colspan="1" rowspan="2">จำนวน</th>
                                        <th colspan="1" rowspan="2">รวม</th>
                                        <!-- 			<th colspan="1"rowspan="2">คงเหลือ</th> -->
                                        <th colspan="1" rowspan="2">หมายเหตุ</th>
                                    </tr>
                                    <tr align="center">
                                        <th> ตั้งแต่</th>
                                        <th>ถึง</th>
                                    </tr>
                                </thead>
                                <tbody align="center">
                                    <?php $num = 0;
									$check_test = 0;
									// $check_limit = 0;
									while ($count = mysqli_fetch_array($query_select)) {
										$startdate = $count['sick_startdate'];
										$enddate = $count['sick_enddate'];
										$total = $count["total"];
										$check_test = $check_test + $total;


										// $dis_day= $check_limitdate-$check_test;

										// $fiscal_year = date("Y", strtotime($count["start_holiday"])) + 543;
										$this_year = 10;

										$num++;


										$sum_num_query--;o;


									?>
                                    <tr>
                                    <td><?= $_GET['fs_y']; ?></td>
                                        <td><?= $count["sick_type"] ?></td>
                                        <!-- 	<td></td>
										<td></td> -->




                                        <td><?= date('d/m/y', strtotime($startdate)) ?></td>
                                        <td><?= date('d/m/y', strtotime($enddate)) ?></td>
                                        <td><?= $num ?></td> <!--sum_num_query -->
                                        <td><?= $total=$count['sick_totalday']+$count['sick_totalhour'];
												$check_test = $check_test + $total;?></td>
                                        <td><?= $check_test ?></td>
                                        <td></td>
                                    </tr>
                                    <?php }
									$check_test = 0; ?>
                                </tbody>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END MAIN -->
            </table>
        </main>
    </div>
    </div>
</body>

</html>
<script>
function excelim() {
    $("#tableData").excelexportjs({
        containerid: "tableData",
        datatype: 'table'
    });
}

function printDiv(divName) {
    console.log(document.getElementById("divprint"));
    // var btnfilter = document.getElementById("example_filter");
    // btnfilter.style.display = "none";
    // $(".dt-buttons").hide();
    $(".dt-buttons").removeAttr("style").hide();
    $("#example_paginate").removeAttr("style").hide();
    $("#example_info").removeAttr("style").hide();
    var printContents = document.getElementById(divName).innerHTML;
    // btnfilter.style.display = "block";
    $(".dt-buttons").show();
    $("#example_paginate").show();
    $("#example_info").show();
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;


    window.print();

    document.body.innerHTML = originalContents;
}
</script>