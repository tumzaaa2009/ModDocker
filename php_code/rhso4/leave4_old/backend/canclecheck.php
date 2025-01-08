<?php
include_once '../connect/connect.php';

    if (!isset($_SESSION)) {
        session_start();
    }
    
 
    
    if (isset($_POST['btnsearch'])) {
        # code...

    
    $typeleave = $_POST["typeleavename"];
    $statusleave = $_POST["typeleavestatus"];
 
    
    
    if ($typeleave == 1  and $statusleave == 1) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=1";
    } else if ($typeleave == 1 and $statusleave == -1) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=-1";
    } else if ($typeleave == 2 and $statusleave == 1) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=1";
    } else if ($typeleave == 2 and $statusleave == -1) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=-1";
    } else if ($typeleave == 1 and $statusleave == 2) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=2";
    } else if ($typeleave == 1 and $statusleave == -2) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=-2";
    } else if ($typeleave == 2 and $statusleave == 2) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=2";
    } else if ($typeleave == 2 and $statusleave == -2) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=-2";
    } else if ($typeleave == 1 and $statusleave == 3) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=3";
    } else if ($typeleave == 1 and $statusleave == -3) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=-3";
    } else if ($typeleave == 2 and $statusleave == 3) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=3";
    } else if ($typeleave == 2 and $statusleave == -3) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=-3";
    } else if ($typeleave == 1 and $statusleave == -4) {
        $typeleave = "detail.type_id=1";
        $statusleave = "detail.status_leave=0";
    } else if ($typeleave == 2 and $statusleave == -4) {
        $typeleave = "detail.type_id=2";
        $statusleave = "detail.status_leave=0";
    }
    
     $select = "SELECT *,gtype.name AS gname,psgroup.name AS pname ,thoildiay.type_name FROM tbl_employment AS emp INNER JOIN tbl_holiday_detail as detail  ON emp.Emp_id = detail.Emp_id
    INNER JOIN tbl_group_type AS gtype ON gtype.group_id=emp.group_id
    INNER JOIN tbl_position_group As psgroup ON psgroup.Position_id = emp.Position_id 
    INNER JOIN tbl_type_hoilday AS thoildiay ON thoildiay.type_id = detail.type_id
    WHERE $statusleave AND emp.Emp_name='" . $_POST["inputname"] . "'   AND  $typeleave   ORDER BY detail.register_date DESC
        ";
    $select_query = mysqli_query($objconnect, $select);
    $num = mysqli_num_rows($select_query);
    
}
    ?>

<body>
    <div class="container-fluid">
        <form method="POST" action="?canclecheck">
            <div class="form-row">
                <div class="form-group col-md-12">
                    <nav class="navbar navbar-deafult" style="background-color: #e3f2fd; ">
                        <div class="form-group col-md-2 mx-sm-3 mb-2">
                            <h4>เลือกสถานะใบลา</h4>
                        </div>
                        <div class="form-group mx-sm-3 mb-2">

                            <input type="text" class="form-control " name="inputname" id="inputPassword2"
                                placeholder="ชื่อ">
                        </div>


                        <div class="form-group col-md-3">
                            <select class="form-control " id="typeleave" name="typeleavename">
                                <option value="0">------เลือกใบลา------</option>
                                <option value="1">ลาพักร้อน</option>
                                <option value="2">บันทึกวันลากิจ/ลาป่วย/ลาคลอดบุตร</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control " id="statusleave" name="typeleavestatus">
                                <option value="0">------เลือกสถานะ------</option>
                                <option value="-1">2.ยกเลิกการลา</option>
                                <option value="-4">7.ยกเลิกการลากรณีพิเศษ</option>
                                }

                            </select>
                        </div>
                        <div class="form-group col-2">
                            <button type="submit" class="btn btn-success" id="btnseach" name="btnsearch">
                                <i class="fas fa-search"></i>
                                ค้นหา
                            </button>
                        </div>
                    </nav>
                </div>
            </div>
        </form>

        <?php 
    if (isset($_POST['btnsearch'])) {?>

        <table id="myTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>ตำแหน่ง</th>
                    <th>ประเภทการลา</th>
                    <th>วันที่ลงลา</th>
                    <th>ตั้งแต่วันที่</th>
                    <th>เวลา</th>
                    <th>ถึงวันที่</th>
                    <th>เวลา</th>
                    <th>จำนวนวันลา</th>
                    <th>ปีงบประมาณ</th>
                    <th>สถานะ</th>
                    <th>VIEW</th>

                </tr>
            </thead>
            <tbody>
                <?php $numid = 1;
            while ($featchselect = mysqli_fetch_array($select_query)) {
                $finyear =$featchselect['Financial_year'];
                $id = "hd_id" . $numid;
                $finnace_year = "fin_year".$numid;
            ?>
                <tr>
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
                                        if ($_POST["type"] == 1) {
                                            echo date("d-m-y", strtotime($featchselect["holiday_startdate"]));
                                        } else {
                                            echo date("d-m-y", strtotime($featchselect["sick_startdate"]));
                                        } ?></td>
                    <td><?php
                        if ($_POST["type"] == 1) {
                            echo $featchselect["holiday_startime"];
                        } else {
                            echo $featchselect["sick_startime"];
                        }
                        ?></td>
                    <td width="10%;"><?php
                                        if ($_POST["type"] == 1) {
                                            echo date("d-m-y", strtotime($featchselect["holiday_enddate"]));
                                        } else {
                                            echo date("d-m-y", strtotime($featchselect["sick_enddate"]));
                                        }
                                        ?></td>
                    <td><?php
                        if ($_POST["type"] == 1) {
                            echo $featchselect["holiday_endtime"];
                        } else {
                            echo $featchselect["sick_endtime"];
                        }
                        ?></td>
                    <td width="5%;"><?php
                                    if ($_POST["type"] == 1) {
                                        // $featchselect["holiday_totalhour"]=0.5;
                                        echo $featchselect["hoilday_totalday"] + $featchselect["holiday_totalhour"];
                                    } else {
                                        // $featchselect["sick_totalhour"]=0.5;
                                        echo  $featchselect["sick_totalday"] + $featchselect["sick_totalhour"];
                                    }
                                    ?></td>
                    <td>
                        <?php echo $featchselect['Financial_year'] ; ?>
                    </td>
                    <td><?php
                        if ($featchselect["status_leave"] == 1) {
                            echo 'ดำเนินงาน';
                        } else if ($featchselect["status_leave"] == -1) {
                            echo 'ยกเลิก';
                        } else if ($featchselect["status_leave"] == 2) {
                            echo 'อนุมัติจากหัวนหน้า';
                        } else if ($featchselect["status_leave"] == -2) {
                            echo 'ไม่อนุมัติจากหัวหน้า';
                        } else if ($featchselect["status_leave"] == 3) {
                            echo 'เสร็จสิน';
                        } else if ($featchselect["status_leave"] == -3) {
                            echo 'ไม่อนุมัติการลาครั้งนี้';
                        } else if ($featchselect["status_leave"] == 0) {
                            echo 'ยกเลิกการลากรณีพิเศษ';
                        }
                        ?></td>

                    <td><input type="hidden" id=emp_id value="<?= $featchselect["Emp_id"] ?>">
                        <?php if ($_POST["type"] == 1) { ?>
                        <input type="hidden" id="<?= $id ?>" value="<?= $featchselect["hoilday_detail_id"] ?>"><input
                            type="button" class="btn btn btn-primary " id="view" onclick="viewid(<?= $numid ?>)"
                            value="VIEW">
                        <?php  } else { ?>
                        <input type="hidden" id="<?= $id ?>" value="<?= $featchselect["hoilday_detail_id"] ?>"><input
                            type="button" class="btn btn btn-primary " id="view"
                            onclick="viewid2(<?= $numid ?>,<?php echo $featchselect['Financial_year'] ; ?>)"
                            value="VIEW">
                        <?php } ?>

                    </td>
                </tr>
                <?php $numid++;
            } ?>
            </tbody>
            <tfoot>
                <tr>
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
                    <th>VIEW</th>
                </tr>
            </tfoot>
        </table>
        <?php } ?>

    </div>
</body>
<script>
function viewid(x) {
    var id = "hd_id"
    var id_detail = document.getElementById(id + x).value;
    var id_emp = document.getElementById("emp_id").value;
    var type_id = "<?= $_POST["typeleavename"] ?>";
 
 location.href = "../view_decription.php?id=" + id_detail + "&&emp=" + id_emp + "&&type_id=" + type_id;
}

function viewid2(x,y) {
    var id = "hd_id";
    var id_detail = document.getElementById(id + x).value;
    var finace_year = "<?php echo $finyear ?>";

    var type_id = "<?= $_POST["typeleavename"] ?>";
    var id_emp = document.getElementById("emp_id").value;
 
    location.href = "../view_decription_leave.php?id=" + id_detail + "&&emp=" + id_emp + "&&type_id=" + type_id +
        "&&finyear=" + y;

}
</script>