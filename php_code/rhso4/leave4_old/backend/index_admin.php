<?php
if (!isset($_SESSION)) {
  session_start();
}
include_once '../connect/connect.php';
include_once 'header_css.php';
if ($_SESSION["Username"] == "admin" || $_SESSION["Username"] == "pratoom" || $_SESSION["Username"] == "Usanee") {



?>

<!DOCTYPE html>
<html>

<head>
    <style>
    .disbledbutton {
        visibility: hidden;
    }
    </style>
</head>

<body x>
    <nav class="navbar navbar-expand-lg navbar-light bg-light"> <a class="navbar-brand" href="#" onclick="index();"><img
                src="../icon/logo2.png" alt="" width="55px" height="55px">
            <font color="black"> รายงาน และ อนุมัติใบลา</font>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">

            </ul>
            <div style="margin-right: 2%;">
                <?php  $select_count1 = "SELECT count(type_id)as count
                                    FROM tbl_holiday_detail
                                    WHERE type_id =1 AND status_leave in (SELECT status_leave FROM tbl_holiday_detail WHERE status_leave=1 OR status_leave=2)
                                    ";
          $query_count1 = mysqli_query($objconnect, $select_count1);

          $feath_num = mysqli_fetch_array($query_count1);
          $num = $feath_num["count"];

          ?>

                <?php

          if ($_SESSION["Username"] == "Usanee") {

            $class = "disbledbutton";
          } else {
            $class = "if";
          }

          ?>
                <font color="red" class="<?= $class ?>"><?= $num ?></font>
                <a data-toggle="modal" href="#" data-target="#exampleModal1" class="<?= $class ?>"> <i
                        class="fas fa-envelope-open-text "></i>คลิ๊กเพื่อดูการแจ้งเตือนอนุมัติลา พักผ่อน
                </a>
                <!-- Modal 1 -->
                <div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">รอการอนุมัติ ลาพักผ่อน</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p><a href="admin_approve_status.php?approve&&type=1&&status=1"><i
                                            class="far fa-sticky-note"></i>
                                        <font size="5" color="red"><?php


                                                  $select = "SELECT status_leave  FROM tbl_holiday_detail WHERE status_leave =1 AND  type_id=1 ";
                                                  $query_select = mysqli_query($objconnect, $select);
                                                  $num = mysqli_num_rows($query_select);
                                                  echo $num ?></font>รอการอนุมัติจากผู้บังคับบัญชา
                                    </a></p>
                                <p>
                                    <a href="admin_approve_status.php?approve&&type=1&&status=2"><i
                                            class="far fa-sticky-note"></i>
                                        <font size="5" color="red">
                                            <?php $select_2 = "SELECT status_leave  FROM tbl_holiday_detail WHERE status_leave =2 AND  type_id=1 ";
                        $query_select_2 = mysqli_query($objconnect, $select_2);
                        $num2 = mysqli_num_rows($query_select_2);
                        echo $num2 ?></font>รอการอนุมัติจากผู้อำนวยการ
                                    </a>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Modal 1-->
            </div>
            <div style="margin-right: 4%;">
                <?php $select_count2 = "SELECT count(type_id)as count
FROM tbl_holiday_detail
 WHERE type_id =2 AND status_leave in (SELECT status_leave FROM tbl_holiday_detail WHERE status_leave=1 OR status_leave=2)
  ";

          $query_count2 = mysqli_query($objconnect, $select_count2);
          $feath_num2 = mysqli_fetch_array($query_count2);
          $num2 = $feath_num2["count"];
          ?>
                <font color="red" class="<?= $class ?>"><?= $num2 ?></font> <a class="<?= $class ?>" data-toggle="modal"
                    data-target="#exampleModal2" href="#"><i class="fas fa-envelope-open-text" onclick="test();"></i>
                    คลิ๊กเพื่อดูการแจ้งเตือนอนุมัติลา ลาป่วย </a>
            </div>
            <!-- Modal 2 -->
            <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">รอการอนุมัติ ลาป่วย</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><a href="admin_approve_status.php?approve&&type=2&&status=1"><i
                                        class="far fa-sticky-note"></i>
                                    <font size="5" color="red"><?php
                                                $select_t2 = "SELECT status_leave  FROM tbl_holiday_detail WHERE status_leave =1 AND  type_id=2 ";
                                                $query_select_t2 = mysqli_query($objconnect, $select_t2);
                                                $numt2 = mysqli_num_rows($query_select_t2);

                                                echo $numt2; ?></font> รอการอนุมัติจากผู้บังคับบัญชา
                                </a></p>
                            <p><a href="admin_approve_status.php?approve&&type=2&&status=2"><i
                                        class="far fa-sticky-note"></i>
                                    <font size="5" color="red"><?php

                                                $select_t2_2 = "SELECT status_leave  FROM tbl_holiday_detail WHERE status_leave =2 AND  type_id=2 ";
                                                $query_select_t2_2 = mysqli_query($objconnect, $select_t2_2);
                                                $numt2_2 = mysqli_num_rows($query_select_t2_2);



                                                echo $numt2_2 ?></font> รอการอนุมัติจากผู้อำนวยการ
                                </a></p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Modal 2-->
            <div style="margin-right: 3%">

                <span data-feather="user"></span> สวัสดีคุณ <?= $_SESSION["name"]; ?>
            </div>
            <!--        <div >
            <button  class="btn btn-success" onclick="logout()"> <i class="fas fa-sign-out-alt"></i> ออกจากระบบ  </button>
            </div> -->
        </div>
    </nav>

    <br>
    <!-- navflex -->
    <div class="container-fluid">
        <div class="row">
            <nav class="col-1.5 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky" id="nav_sub">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="../index.php">
                                <span data-feather="home"></span>
                                กลับหน้าวันลา <span class="sr-only">(current)</span>
                            </a>
                        </li>

                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center col-4">
                        <span>รายงาน</span>

                    </h6>
                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link" href="index_admin.php?canclecheck">
                                <span data-feather="bar-chart"></span>
                                ตรวจสอบการลากรณียกเลิก
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index_admin.php?chart">
                                <span data-feather="bar-chart"></span>
                                สถิติการลา
                            </a>
                        </li>
                        <li class="nav-item <?= $class ?>">
                            <a class="nav-link" href="index_admin.php?cancle_approve">
                                <span data-feather="x-circle"></span>
                                ยกเลิกการลากรณีพิเศษ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index_admin.php?re=report">
                                <span data-feather="database"></span>
                                ประวัติการลาทั้งหมด
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index_admin.php?check_facelYear">
                                <span data-feather="calendar"></span>
                                วันลาปีงบประมาณ
                            </a>


                        </li>
                        <? if($_SESSION["Username"]=="admin"){?>
                        <li class="nav-item">
                            <a class="nav-link" href="index_admin.php?edit=edit">
                                <span data-feather="settings"></span>
                                ปรับปรุงผู้ใช้
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index_query_admin.php">
                                <span data-feather="settings"></span>
                                GEN วันลา ใหม่ ผู้ใช้ใหม่
                            </a>
                        </li>
                        <?}?>

                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="logout()">
                                <span data-feather="log-out"></span>
                                ออกจากระบบ
                            </a>
                        </li>

                    </ul>

                </div>
            </nav>

            <div role="main" class="col-md-10">
                <?php
          if (isset($_GET["re"])) {
            if ($_GET["re"] == "report") {
              include_once 'show_history_admin.php';
            }
          } else if (isset($_GET["ap"])) {
            if ($_GET["ap"] == "approve") {
              include_once 'admin_approve.php';
            }
          } else if (isset($_GET["chart"])) {
            include_once 'admin_static.php';
          } else if (isset($_GET["cancle_approve"])) {
            include_once 'admin_cancle_speacia_approve.php';
          } else if (isset($_GET["edit"])) {
            include_once 'admin_edit_limit.php';
          } else if (isset($_GET["re"])) {
            include_once 'admin_edit_limit.php';
          } else if (isset($_GET["check_facelYear"])) {
            include_once 'admin_static_facel_year.php';
          }
          else if (isset($_GET["canclecheck"])) {
            include_once 'canclecheck.php';
          }
          ?>

            </div>


        </div>
    </div>

    <!-- endNavflex -->

</body>

<!-- <script src="../css_and_js/javascript/popper.min.js"></script>
<script src="../css_and_js/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script> -->
<!-- <script src="../css_and_js/fontawesome-free-5.10.1-web/js/all.min.js"></script> -->

<!-- Icons -->

<script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
<script>
feather.replace()
</script>

<!-- Graphs -->
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>	 -->
<!-- <script>
      var ctx = document.getElementById("myChart");
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
          datasets: [{
            data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: false
              }
            }]
          },
          legend: {
            display: false,
          }
        }
      });
    </script> -->
<script type="text/javascript">
function logout() {
    location.href = '../logout.php'
}
</script>





</html>
<?php } else { ?>
<script type="text/javascript">
location.href = '../logout.php';
</script>
<?php } ?>

<script type="text/javascript">
function index() {
    location.href = "index_admin.php";
}
</script>