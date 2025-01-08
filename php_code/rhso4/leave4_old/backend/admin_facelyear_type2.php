<?php
if (!isset($_SESSION)) {
	session_start();
}
include_once '../connect/connect.php';
include_once 'header_css.php';
$fs_y = $_GET['fs_y'];
?>
<!DOCTYPE html>
<html>

<body>
    <div class="container " style="margin-top: 1%;margin-bottom: 1%;" id="printbtton">
        <div class="row">
            <div class="col">
                <div align="center"> <input type="button " class="btn btn-primary" onclick="printDiv('divprint')"
                        value="print" /> <button id='DLtoExcel' class="btn btn-danger" onclick="excelim()">Export
                        Excel</button></div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- ปีงบประมาณ ปัจจุบัน ถ้า ไม่ปัจจุบันไม่ขึ้น -->
                    <?php;
					echo '<div align=\'center\'>ปีงบประมาณ ' . $fs_y . '</div>';
					?>
                    <div align="center">
                        <h1>สำนักงานเขตสุขภาพที่ 4</h1>
                    </div>
                    <table class="table table-bordered table-responsive" width="100%" id="tableData">
                        <thead align="center" class="mb-5">
                            <tr>
                                <th>
                                    <div align="center ">ลำดับ</div>
                                </th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>ตำแหน่ง</th>
                                <th rowspan="2">
                                    <div class="mb-5" align="center">จำนวนวันลาพักผ่อนรวม</div>
                                </th>
                                <th colspan="7">ครึ่งปีแรก (1ต.ค.<?= $_GET['fs_y']-1 ?>-31 มี.ค.<?= $_GET['fs_y'] ?>)
                                </th>
                                <th colspan="7">ครึ่งปีหลัง (1เม.ย.<?= $_GET['fs_y'] ?>-30 ก.ย.<?= $_GET['fs_y'] ?>)
                                </th>
                                <th colspan="7">รวมปีงบ (1ต.ค.<?= $_GET['fs_y']-1 ?>-30 ก.ย.<?= $_GET['fs_y'] ?>) </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th rowspan="2">จำนวนวันลาพักผ่อน</th>
                                <th rowspan="2">ลาป่วย(วัน)</th>
                                <th rowspan="2">ลากิจ(วัน)</th>
                                <th colspan="2">จำนวนลาป่วย/กิจ</th>
                                <th rowspan="2">สาย(วัน)</th>
                                <th rowspan="2">ขาด(วัน)</th>
                                <!-- อันที่2 -->
                                <th rowspan="2">จำนวนวันลาพักผ่อน</th>
                                <th rowspan="2">ลาป่วย(วัน)</th>
                                <th rowspan="2">ลากิจ(วัน)</th>
                                <th colspan="2">จำนวนลาป่วย/กิจ</th>
                                <th rowspan="2">สาย(วัน)</th>
                                <th rowspan="2">ขาด(วัน)</th>

                                <!-- อันที่3 -->
                                <th rowspan="2">จำนวนวันลาพักผ่อน</th>
                                <th rowspan="2">ลาป่วย(วัน)</th>
                                <th rowspan="2">ลากิจ(วัน)</th>
                                <th colspan="2">จำนวนลาป่วย/กิจ</th>
                                <th rowspan="2">สาย(วัน)</th>
                                <th rowspan="2">ขาด(วัน)</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>วัน</th>
                                <th>ครั้ง</th>

                                <!-- ครั้งที่2 -->
                                <th>วัน</th>
                                <th>ครั้ง</th>

                                <!-- ครั้งที่3 -->
                                <th>วัน</th>
                                <th>ครั้ง</th>


                            </tr>

                        </thead>
                        <tbody>

                            <?php $fix_start_facal = $_GET['fs_y']-544; 
        $fix_end_facle = $_GET['fs_y']-543;
?>

                            <tr>

                                <td align="center" colspan="25">พนักงานราชการ</td>
                            </tr>
                            <?php   $select_sumfacel = " SELECT count_year_holiday,emp.Emp_id,emp.Titile_name,emp.Emp_name,emp.Emp_lastname,tgt.name ,pg.name as pointname,fix_count_oldyear_holiday,(SELECT SUM(hd1.hoilday_totalday+hd1.holiday_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.holiday_startdate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "' and hd1.holiday_enddate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "' 
 AND hd1.type_id=1 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id ) d1
 ,(SELECT SUM(hd1.sick_totalday+hd1.sick_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "' and hd1.sick_enddate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "' 
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id AND hd1.sick_type=\"ป่วย\" ) sick1

  ,(SELECT SUM(hd1.sick_totalday+hd1.sick_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "' and  hd1.sick_enddate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "'
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id AND hd1.sick_type=\"ขอลากิจส่วนตัว\" ) sick_personal_leave


,(SELECT count(hd1.hoilday_detail_id) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "'  and  hd1.sick_enddate  BETWEEN'" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-03-31' . "'
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id  ) COUNT1

 ,(SELECT SUM(hd1.hoilday_totalday+hd1.holiday_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.holiday_startdate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.holiday_enddate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' 
 AND hd1.type_id=1 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id ) d2
 ,(SELECT SUM(hd1.sick_totalday+hd1.sick_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.sick_enddate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' 
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id AND hd1.sick_type=\"ป่วย\" ) sick2

 ,(SELECT SUM(hd1.sick_totalday+hd1.sick_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.sick_enddate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' 
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id AND hd1.sick_type=\"ขอลากิจส่วนตัว\" ) sick_personal_leave2

,(SELECT count(hd1.hoilday_detail_id) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.sick_enddate  BETWEEN'" . $fix_end_facle . '-04-01' . "' and '" . $fix_end_facle . '-09-30' . "' 
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id  ) COUNT2

 ,(SELECT SUM(hd1.hoilday_totalday+hd1.holiday_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.holiday_startdate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.holiday_enddate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "'
 AND hd1.type_id=1 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id ) yd1

 ,(SELECT SUM(hd1.sick_totalday+hd1.sick_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.sick_enddate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "'
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id AND hd1.sick_type=\"ป่วย\" ) ysick

 ,(SELECT SUM(hd1.sick_totalday+hd1.sick_totalhour) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.sick_enddate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "'
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id AND hd1.sick_type=\"ขอลากิจส่วนตัว\" ) ypersonaltype

 ,(SELECT count(hoilday_detail_id) FROM tbl_holiday_detail hd1 WHERE hd1.sick_startdate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "' and hd1.sick_enddate  BETWEEN '" . $fix_start_facal . '-10-01' . "' and '" . $fix_end_facle . '-09-30' . "'
 AND hd1.type_id=2 AND hd1.status_leave=3  AND hd1.Emp_id=emp.Emp_id  ) ycount
 FROM tbl_employment emp 
 INNER JOIN tbl_group_type tgt ON tgt.group_id=emp.group_id
 INNER JOIN tbl_position_group pg ON pg.Position_id=emp.Position_id
 INNER JOIN tbl_total_limit_holiday li ON li.Emp_id = emp.Emp_id 
 WHERE emp.group_id=2 and emp.Emp_id NOT IN (2)and li.Financial_year ='" . $fs_y . "'  ORDER BY emp.Emp_id  
 ";
							$query_sumfacel = mysqli_query($objconnect, $select_sumfacel);
							$num = 1;

							?>
                            <?php  while ($row_sumfacel = mysqli_fetch_array($query_sumfacel)) {?>
                            <tr>
                                <td><?= $num; ?></td>
                                <td><?= $row_sumfacel["Titile_name"] . " " . $row_sumfacel["Emp_name"] . $row_sumfacel["Emp_lastname"]; ?>
                                </td>
                                <td><?= $row_sumfacel["pointname"]; ?></td>
                                <td>
                                    <?php echo  $row_sumfacel["fix_count_oldyear_holiday"]+$row_sumfacel['count_year_holiday'];?>
                                </td>
                                <td>
                                    <?php if ($row_sumfacel["d1"]=="") {
													echo 0	;													
	                                        }else{echo	$row_sumfacel["d1"];}?>
                                </td>
                                <td>
                                    <?php if ($row_sumfacel["sick1"]=="") {echo 0;}else{echo 	$row_sumfacel["sick1"];	}?>
                                </td>
                                <td>
                                    <?php if ($row_sumfacel["sick_personal_leave"]=="") {echo 0;}else{echo $row_sumfacel["sick_personal_leave"];}?>
                                </td>
                                <td><?= $row_sumfacel["sick1"] + $row_sumfacel["sick_personal_leave"]; ?></td>
                                <td><?= $row_sumfacel["COUNT1"]; ?></td>
                                <td></td>
                                <td></td>
                                <td><?php if ($row_sumfacel["d2"]=="") { echo 0	;}else{echo	$row_sumfacel["d2"];}?></td>
                                <td><?php if ($row_sumfacel["sick2"]=="") {echo 0;}else{echo 	$row_sumfacel["sick2"];	}?>
                                </td>
                                <td><?php if ($row_sumfacel["sick_personal_leave2"]=="") {echo 0;}else{echo $row_sumfacel["sick_personal_leave2"];}?>
                                </td>
                                <td><?php echo$row_sumfacel["sick2"] + $row_sumfacel["sick_personal_leave2"]; ?></td>
                                <td><?php echo $row_sumfacel["COUNT2"]; ?></td>
                                <td></td>
                                <td></td>
                                <td><?php if ($row_sumfacel["yd1"]=="") {echo 0	;}else{echo	$row_sumfacel["yd1"];}?>
                                </td>
                                <td><?php if ($row_sumfacel["ysick"]=="") {echo 0;}else{echo 	$row_sumfacel["ysick"];	}?>
                                </td>
                                <td><?php  if ($row_sumfacel["ypersonaltype"]=="") {echo 0;	}else{echo $row_sumfacel["ypersonaltype"];}?>
                                </td>
                                <td><?php echo $row_sumfacel["ysick"] + $row_sumfacel["ypersonaltype"]; ?></td>
                                <td><?php echo $row_sumfacel["ycount"]; ?></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php $num++;}?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END CONTINAER -->
    </div>
</body>

</html>

</div>
<!-- END CONTINAER -->
</div>

<script src="excelexportjs.js"></script>
<script>
function excelim() {
    $("#tableData").excelexportjs({
        containerid: "tableData",
        datatype: 'table'
    });
}

function printDiv(divName) {
    var css = '@page { size: landscape; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');
    var printButton = document.getElementById("printbtton");
    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet) {
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }
    printButton.style.visibility = 'hidden';
    head.appendChild(style);

    window.print();
    printButton.style.visibility = 'visible';
}
</script>

</body>

</html>