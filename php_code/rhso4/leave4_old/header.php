<?php
if (!isset($_SESSION)) {
  session_start();
}
?>
<!DOCTYPE html>
<html>

<head>


</head>
<?php
include 'connect/connect.php';
include 'head/headder.php';


?>
<?php
if (isset($_SESSION["Username"])) {
$select_calholidayLockButton = "SELECT * FROM  tbl_total_limit_holiday AS countlimit 
WHERE countlimit.Emp_id ='" . $_SESSION["Emp_id"] . "' and countlimit.status_limit=1";
$querry_calholidayLockButton = mysqli_query($objconnect, $select_calholidayLockButton);
 
$featch_calholidayLockButton= mysqli_fetch_array($querry_calholidayLockButton);
 
 $count_year_holiday = $featch_calholidayLockButton['count_year_holiday'] ?? 0;
$disabled = $count_year_holiday == 0 ? 'disabled' : '';

?>



<body class="no-skin">

    <!--start container-fulid -->
    <div class="container-fulid" style="width: 99%">
        <div class="row">
            <div class="col-12 col-sm12 col-md-12 col-lg-12 col-xl-12">
                <nav id="navbar-example2" class="navbar navbar-light bg-light">
                    <a class="navbar-brand" href="#" onclick="index();"><img src="icon/logo2.png" alt="" width="55px"
                            height="55px">
                        <font color="black"> ระบุวันลา</font>
                    </a>
                    <div class="container-fulid">
                        <div class="row">
                            <div class="col-12 col-sm12 col-md-12 col-lg-12 col-xl-12">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <i class="fas fa-user" style="margin-top: 100%"></i>
                                    </li>
                                    <li class="nav-item">
                                        <font class="nav-link">สวัสดี <?= $_SESSION["name"] ?></font>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

            </div>
        </div>
    </div>

    <!--End-container-fulid  -->

    <!-- start main-container -->

    <div class="container-fulid main-container">
        <div id="sidebar" class="sidebar responsive">
            <ul class="list-group">
                <div class="dropdown show">
                    <li class="list-group-item ">
                        <h4 align="center"><a href="manual/manual_leaverh4.pdf"> คู่มือการลา</a></h4>
                    </li>
                    <li class="list-group-item ">
                        <h4 align="center"><a href="img_formation/formating.png"><span
                                    style="color:red;">ตั้งค่าหน้ากระดาษ</span></a></h4>
                    </li>
                    <li class="list-group-item active">หมวดหมู่</li>
                    <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-pencil-square-o"></i> บันทึกวันลา</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?php if ($count_year_holiday == 0): ?>
                        <span class="dropdown-item disabled">รอทำงานครบ 6 เดือน น่ะจ๊ะ</span>
                        <?php else: ?>
                        <a class="dropdown-item" href="index.php?st=add">บันทึกเวลาพักผ่อน</a>
                        <?php endif; ?>

                        <a class="dropdown-item" href="index.php?lv=leave">บันทึกวันลากิจ/ลาป่วย/ลาคลอดบุตร</a>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-pencil-square-o"></i> ประวัตัวันลา</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="index.php?ht=history">ประวัติวันลาทั้งหมด</a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="menu-icon fa fa-pencil-square-o"></i> แผนภูมิ/ปฏิทินแสดงวันลา</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="index.php?chart">แสดงรายการภูมิและวันลาที่เหลือ</a>
                            <a class="dropdown-item" href="index.php?calrender" class="list-group-item"> ปฏิทินลา</a>

                        </div>
                    </div>

                </div>
                <?php
          if ($_SESSION["Username"] == "admin" or $_SESSION["Username"] == "pratoom"  or $_SESSION["Username"] == "Usanee") { ?>
                <div class="dropdown">
                    <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuButton_history"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="menu-icon fa fa-pencil-square-o"></i> รายงาน</a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_history">
                        <a class="dropdown-item" href="backend/index_admin.php">รายงานวันลา</a>
                    </div>
                </div>
                <?php } ?>
                <a class="list-group-item" href="logout.php?id=1"> <i class="ace-icon fa fa-power-off"> </i> ออกระบบ</a>
                <a class="list-group-item" href="logout.php?id=2"> <i class="ace-icon fa fa-home"> </i>
                    กลับสู่หน้าหลัก</a>

        </div>
        </ul>

    </div>

    <?php if ($_SESSION["Username"] == 'admin') { ?>
    <form method="POST">
        <button type="submit" name="submit_gen_facel_year" value="submit_gen_facel_year" class="btn btn-primary">GEN
            วันลา</button>
    </form>
    <?php } ?>

    <!-- start boad-crumb -->
    <div class="container-fluid">
        <div class="form-row">
            <div class="form-group col-md-12">
                <nav class="navbar navbar-deafult" style="background-color: #e3f2fd; ">
                    <div class="container-fluid">
                        <?php
              if (isset($_GET['calrender'])) {
                echo "<div align=\"center\" style=\"width: 100%;\"> <h4 >ปฏิทินการลา</h4></div>";
              } else { ?>

                        <div class="form-group col-md-3">
                            <ul class="breadcrumb">
                                <?php
                    if (isset($_GET["st"])) {
                      if ($_GET["st"] == "add") { ?>
                                <li><a href="index.php">Home</a></li>
                                <li>Hoiliday</li>
                                <?php }
                    }
                    if (isset($_GET["lv"])) {
                      if ($_GET["lv"] == "leave") { ?>
                                <li><a href="index.php">Home</a></li>
                                <li>SICK</li>
                                <?php }
                    }

                    if (isset($_GET["id"])) {
                      if ($_GET["id"] == $_SESSION["Emp_id"]) { ?>
                                <li><a href="index.php">Home</a></li>
                                <li>Calerndar</li>
                                <?php  } ?>
                                <?php  }
                    if (isset($_GET["ht"])) {
                      if ($_GET["ht"] == "history") { ?>
                                <li><a href="index.php">Home</a></li>
                                <li>History</li>
                                <?php }
                    } //ทำปุ่มค้นหาวันนลส
                    ?>
                                <?php
                    if (isset($_GET["chart"])) { ?>

                                <li><a href="index.php">Home</a></li>
                                <li>chart</li>
                                <?php  } //ทำปุ่มค้นหาวันนลส
                    ?>
                            </ul>
                        </div>
                        <div class="form-group col-md-2">
                            <?php
                  if (isset($_GET["ht"])) {
                    if ($_GET["ht"] == "history") { ?>
                            <h4>เลือกสถานะใบลา</h4>
                            <?php }
                  } elseif (isset($_GET["id"])) { ?>
                            <h4>เลือกประเภทวันลา</h4>
                            <?php } ?>
                        </div>
                        <div class="form-group col-md-3">
                            <?php
                  if (isset($_GET["ht"])) {
                    if ($_GET["ht"] == "history") { ?>
                            <select class="form-control " id="typeleave">
                                <option value="0">------เลือกใบลา------</option>
                                <option value="1">ลาพักร้อน</option>
                                <option value="2">บันทึกวันลากิจ/ลาป่วย/ลาคลอดบุตร</option>
                            </select>
                            <?php }
                  } else if (isset($_GET["id"])) { ?>
                            <select class="form-control " id="typecalrendar">
                                <option value="0">------เลือกใบลา------</option>
                                <option value="1">ลาพักร้อน</option>
                                <option value="2">บันทึกวันลากิจ/ลาป่วย/ลาคลอดบุตร</option>
                            </select>
                            <?php   } ?>

                        </div>
                        <div class="form-group col-md-2">
                            <?php
                  if (isset($_GET["ht"])) {
                    if ($_GET["ht"] == "history") { ?>
                            <select class="form-control " id="statusleave">
                                <option value="0">------เลือกสถานะ------</option>
                                <option value="1">1.ส่งเรื่องการลา</option>
                                <option value="-1">2.ยกเลิกการลา</option>
                                <option value="2">3.อนุมัติใบลาจากหัวหน้า</option>
                                <option value="-2">4.ไม่อนุมัติใบลาจากหัวหน้า</option>
                                <option value="3">5.เสร็จสินการอนุมัติ</option>
                                <option value="-3">6.ไม่อนุมัติการลานี้</option>
                                <option value="-4">7.ยกเลิกการลากรณีพิเศษ</option>
                                }

                            </select>
                            <?php }
                  }   ?>

                        </div>
                        <div class="form-group col-2">
                            <?php
                  if (isset($_GET["ht"])) {
                    if ($_GET["ht"] == "history") { ?>
                            <button type="button" class="btn btn-success" value="1" id="btnseach">
                                <i class="fas fa-search"></i> ค้นหา</button>
                            <?php }
                  } elseif (isset($_GET["id"])) { ?>
                            <button type="button" class="btn btn-success" value="1" id="btnseach_1">
                                <i class="fas fa-search"></i> ค้นหา</button>
                            <?php  } ?>
                        </div>
                        <?php } ?>
                    </div>

                </nav>
            </div>
        </div>
    </div>

    <div class="container-fulid">
        <div class="row">
            <div class="col-12 col-sm12 col-md-12 col-lg-12 col-xl-12">
                <!-- ///////////////////////////// index -->
                <?php

          if (isset($_GET["calrender"])) { ?>

                <table style="width: 100%;" class="table">
                    <tbody>
                        <tr>
                            <td><?php include 'index_carlender.php'; ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php    } ?>

                <!-- //////////////////////////////index -->
                <?php
          if (isset($_GET["st"])) {
            if ($_GET["st"] = "add") { ?>
                <table style="width: 100%;" class="table">
                    <tbody>
                        <tr>
                            <td><?php include 'diary_form_new.php'; ?></td>
                        </tr>
                    </tbody>
                </table><?php
                    }
                  } ?>
                <!-- ///////////////////////////// LEAVE -->
                <?php
          if (isset($_GET["lv"])) {
            if ($_GET["st"] = "leave") { ?>
                <table style="width: 100%;" class="table">
                    <tbody>
                        <tr>
                            <td><?php include 'diary_form_leave_new.php'; ?></td>
                        </tr>
                    </tbody>
                </table><?php
                    }
                  } 
                      ?>

                <!-- //////////////////////////////leave -->

                <!-- ADMINHISTORY -->
                <?php
          if (isset($_GET["ht"])) {
            if ($_GET["ht"] = "history") { ?>
                <table style="width: 100%;" class="table">
                    <tbody>
                        <tr>
                            <td><?php include 'discription_main.php'; ?></td>
                        </tr>
                    </tbody>
                </table><?php
                    }
                  } 


                      ?>

                <!-- END HISTORY -->
                <!-- chart  -->
                <?php
          if (isset($_GET["chart"])) { ?>

                <table style="width: 100%;" class="table">
                    <tbody>
                        <tr>
                            <td><?php include 'chart_test.php'; ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php
          }
 
          ?>

                <!-- END chart -->
            </div>


        </div>
    </div>
    <!-- edn main-include -->
    </div>
    <!-- end container-main -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div id="list-data1">
                </div>

            </div>
        </div>

    </div>

</body>

</html>
<?php } else {     ?>
<script type="text/javascript">
location.href = 'logout.php';
</script>
<?php } ?>
<!-- <script src="css_and_js/javascript/jquery-3.4.1.min.js"></script> -->
<script>
$(document).ready(function() {
    $(function() {

        $("#btnseach").click(function() {
            if ($("#typeleave").val() == 0 || $("#statusleave").val() == 0) {
                alert("เช็คการค้นหาใหม่อีกครั้ง");
                return false;
            } else {
                jQuery.ajax({
                    url: 'showdecriptionmain_ajax.php',
                    type: 'POST',
                    data: {
                        type: $("#typeleave").val(),
                        status: $("#statusleave").val(),
                        btn: $("#btnseach").val(),
                    },
                    complete: function(xhr, textStatus) {
                        $(".loading").hide();
                    },
                    success: function(data, textStatus, xhr) {
                        $("#list-data").html(data);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("gg");
                    }
                })
            };

        })

    });
    $("#btnseach_1").click(function() {
        if ($("#typecalrendar").val() == 0) {
            alert("เช็คการค้นหาใหม่อีกครั้ง");
            return false;
        } else {
            jQuery.ajax({
                url: 'index_carlender.php',
                type: 'POST',
                data: {
                    type: $("#typecalrendar").val(),
                    btn: $("#btnseach_1").val()
                },
                complete: function(xhr, textStatus) {
                    $(".loading").hide();
                },
                success: function(data, textStatus, xhr) {
                    $("#list-data1").html(data);
                },
                error: function(xhr, textStatus, errorThrown) {

                }
            })
        };

    })

});
</script>

<script type="text/javascript">
function index() {
    location.href = "index.php";
}
</script>

<style>
/* Stackoverflow preview fix, please ignore */

/* Fixes dropdown menus placed on the right side */
.ml-auto .dropdown-menu {
    left: auto !important;
    right: 0px;
}

ul.breadcrumb {
    padding: 10px 16px;
    list-style: none;
    /*background-color: #eee;*/
}

/* Display list items side by side */
ul.breadcrumb li {
    display: inline;
    font-size: 18px;
}

/* Add a slash symbol (/) before/behind each list item */
ul.breadcrumb li+li:before {
    padding: 8px;
    color: black;
    content: "/\00a0";
}

/* Add a color to all links inside the list */
ul.breadcrumb li a {
    color: #0275d8;
    text-decoration: none;
}

/* Add a color on mouse-over */
ul.breadcrumb li a:hover {
    color: #01447e;
    text-decoration: underline;
}

.input_show {
    border: 0;
    outline: 0;
    border-bottom: 0px solid black;
    margin-top: -5px;
    vertical-align: top;
    font-weight: bold;
    /*  margin-left:100%;*/
}

.input_show1 {
    border: 0;
    outline: 0;
    border-bottom: 0px solid black;
    padding-right: 20px;

}

.input_show2 {
    border: 0;
    outline: 0;
    border-bottom: 0px solid black;
    text-align: center;
    width: 80%
}
</style>