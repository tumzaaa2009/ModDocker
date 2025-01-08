<?php
if (!isset($_SESSION)) {
    session_start();
}
include_once '../connect/connect.php';

///////////////////////////////////////////
$select_datecheck = "SELECT Financial_year,start_holiday,end_holiday FROM tbl_total_limit_holiday WHERE emp_id ='" . $_SESSION["Emp_id"] . "' AND status_limit = 1 ";
$query_datecheck = mysqli_query($objconnect, $select_datecheck);
$featch_datecheck = mysqli_fetch_array($query_datecheck);
$startdate_check = $featch_datecheck["start_holiday"];
$enddate_check  = $featch_datecheck["end_holiday"];

 $financialYear = $featch_datecheck['Financial_year'];
// echo print_r($_POST);


// ////////////////////////////////////////

$select = "SELECT *,gtype.name AS gname,psgroup.name AS pname ,thoildiay.type_name FROM tbl_employment AS emp INNER JOIN tbl_holiday_detail as detail  ON emp.Emp_id = detail.Emp_id
INNER JOIN tbl_group_type AS gtype ON gtype.group_id=emp.group_id
INNER JOIN tbl_position_group As psgroup ON psgroup.Position_id = emp.Position_id 
INNER JOIN tbl_type_hoilday AS thoildiay ON thoildiay.type_id = detail.type_id
where detail.status_leave=3   AND detail.Financial_year=$financialYear";
$select_query = mysqli_query($objconnect, $select);
$num = mysqli_num_rows($select_query);

// echo $select;
?>

<!DOCTYPE html>
<html>

<head>
    <!--  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"> -->


    <link rel="stylesheet" type="text/css" href="../css_and_js/js_datatable/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-10">
                <table id="myTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อ</th>
                            <th>ตำแหน่ง</th>
                            <th>ประเภทการลา</th>
                            <th>วันที่ลงลา</th>
                            <th>ตั้งแต่วันที่</th>
                            <th>เวลา</th>
                            <th>ถึงวันที่</th>
                            <th>เวลา</th>
                            <th>จำนวนวันลา</th>
                            <th>สถานะ</th>
                            <th>เหตุผล</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php $numid = 1;
                        while ($featchselect = mysqli_fetch_array($select_query)) {
                            $id = "hd_id" . $numid;


                            $save = "save" . $numid;
                            $textarea = "textarea" . $numid;
                            $contact = "contact-modal" . $numid;
                            $contactForm = "contactForm" . $numid;
                            $submit = "approve" . $numid;
                            $numemp = "numemp" . $numid;
                            $holiday = "holidaynum" . $numid;
                            $contact1 = "contract1" . $numid;
                            $ficel = "ficel" . $numid;


                            // echo$holiday;
                        ?>
                            <tr>
                                <td><?= $numid ?></td>
                                <td width="10%"><?= $featchselect["hoilday_detail_name"]; ?></td>
                                <!--     <td ><?= $featchselect["gname"]; ?></td> -->
                                <td><?= $featchselect["pname"]; ?></td>
                                <td width="10%;"><?php
                                                    if ($featchselect["type_id"] == 1) {
                                                        echo $featchselect["type_name"];;
                                                    } else {
                                                        echo $featchselect["sick_type"];
                                                    } ?></td>
                                <td width="10%;"><?= date("d-m-y", strtotime($featchselect["register_date"])); ?></td>
                                <td width="10%;"><?php
                                                    if ($featchselect["type_id"] == 1) {
                                                        echo date("d-m-y", strtotime($featchselect["holiday_startdate"]));
                                                    } else {
                                                        echo date("d-m-y", strtotime($featchselect["sick_startdate"]));
                                                    } ?></td>
                                <td><?php
                                    if ($featchselect["type_id"] == 1) {
                                        echo $featchselect["holiday_startime"];
                                    } else {
                                        echo $featchselect["sick_startime"];
                                    }
                                    ?></td>
                                <td width="10%;"><?php
                                                    if ($featchselect["type_id"] == 1) {
                                                        echo date("d-m-y", strtotime($featchselect["holiday_enddate"]));
                                                    } else {
                                                        echo date("d-m-y", strtotime($featchselect["sick_enddate"]));
                                                    }
                                                    ?></td>
                                <td><?php
                                    if ($featchselect["type_id"] == 1) {
                                        echo $featchselect["holiday_endtime"];
                                    } else {
                                        echo $featchselect["sick_endtime"];
                                    }
                                    ?></td>
                                <td width="5%;"><?php
                                                if ($featchselect["type_id"] == 1) {

                                                    echo $featchselect["hoilday_totalday"] + $featchselect["holiday_totalhour"];
                                                } else {
                                                    echo  $featchselect["sick_totalday"] + $featchselect["sick_totalhour"];
                                                }
                                                ?></td>
                                <td><?php
                                    if ($featchselect["status_leave"] == 1) {
                                        echo 'ส่งเรื่องการลา';
                                    } else if ($featchselect["status_leave"] == -1) {
                                        echo "<font color=\"red\" >" . 'ยกเลิกการลา' . "<font>";
                                    } else if ($featchselect["status_leave"] == 2) {
                                        echo "<font color=\"blue\" >" . 'อนุมัติจากหัวหน้า' . "<font>";
                                    } else if ($featchselect["status_leave"] == -2) {
                                        echo "<font color=\"red\" >" . 'ไม่อนุมัติจากหัวหน้า' . "<font>";
                                    } else if ($featchselect["status_leave"] == 3) {
                                        echo "<font color=\"green\" >" . 'เสร็จสิน' . "<font>";
                                    } else if ($featchselect["status_leave"] == -3) {
                                        echo "<font color=\"red\" >" . 'ไม่อนุมัติการลาครั้งนี้' . "<font>";
                                    }
                                    ?></td>
                                <td>
                                    <button type="button" class="btn btn-danger btn" data-toggle="modal" data-target="#<?= $contact1 ?>" id="<?= $contact1 ?>" onclick="viewid(<?= $numid ?>,
        <?= $numid ?>,<?= $numid ?>,<?= $featchselect["Emp_id"] ?>,<?= $featchselect["hoilday_detail_id"] ?>,<?= $featchselect["type_id"] ?>,<?= $featchselect["Financial_year"] ?>)">ยกเลิก</button>

                                </td>
                            </tr>

                            <div id="<?= $contact1 ?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">ยกเลิกใบลากรณีพิเศษ</h5>
                                            <a class="close" data-dismiss="modal">×</a>

                                        </div>
                                        <form id="<?= $contactForm ?>" name="contact" method="post" action="delete_admin.php?cancleapprove=cancleapprove&&type_id=1">
                                            <div class="modal-body">

                                                <div class="form-group">
                                                    <label for="message">ระบุเหตุผลการยกเลิก</label>
                                                    <textarea name="message" required="true" name="comment" class="form-control"></textarea>
                                                    <input type="hidden" name="emp" id="<?= $numemp ?>">
                                                    <input type="hidden" name="id_holiday" id="<?= $holiday ?>">
                                                    <input type="hidden" name="ficalyear" id="<?php echo $ficel ?>" ?>
                                                    <input type ="hidden" name ="sumResult" value="<?php  echo $featchselect["hoilday_totalday"] + $featchselect["holiday_totalhour"]?>" ?>

                                                </div>
                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                                <input type="submit" class="btn btn-success" id="<?= $submit ?>" name="<?= $submit ?>" value="ยืนยัน">
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        <?php $numid++;
                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อ</th>
                            <th>ตำแหน่ง</th>
                            <th>ประเภทการลา</th>
                            <th>วันที่ลงลา</th>
                            <th>ตั้งแต่วันที่</th>
                            <th>เวลา</th>
                            <th>ถึงวันที่</th>
                            <th>เวลา</th>
                            <th>จำนวนวันลา</th>
                            <th>สถานะ</th>
                            <th>เหตุผล</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</body>
<script src="../css_and_js/javascript/jquery-3.4.1.min.js"></script>
<script src="../css_and_js/js_datatable/js/jquery.dataTables.min.js"></script>
<script src="../css_and_js/js_datatable/js/dataTables.bootstrap4.js"></script>

</html>

<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
<!-- 
<script type="text/javascript">
// var contactForm ="#ssssss";
// console.log( $(contactForm).val());
    $(document).ready(function(){   
    $("#contactForm").submit(function(event){
         console.log( $(contactForm).val());
        submitForm();
        return false;
    });
// console.log(x);
// x++;
});

</script> -->
<script type="text/javascript">
    function viewid(num, num1, num2, x, y, z, xz) {
        var contactnum = "contract1" + num;
        var contact = document.getElementById(contactnum);
        var numempnum = "numemp" + num;
        var num = document.getElementById(numempnum).value = x;
        var holidaynum = "holidaynum" + num1;
        var holiday = document.getElementById(holidaynum).value = y;
        var sumbitnum = "approve" + num2;
        var submit = document.getElementById(sumbitnum);
        var ficel = "ficel" + num1
        document.getElementById(ficel).value = xz;

    }
</script>


</body>

</html>