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
// include 'index_mobile.php'

?>
<?php
if (isset($_SESSION["Username"])) {


?>



<body>
    <header>
        <div class="collapse bg-dark" id="navbarHeader">
            <div class="container">
                <div class="row">
                    <div class="col-sm offset-md-1 py-4">
                        <h4 class="text-white">Contact</h4>
                        <ul class="list-group">
                            <div class="dropdown show">
                                <li class="list-group-item">
                                    <h4 align="center"><a href="manual/manual_leaverh4.pdf"> คู่มือการลา</a></h4>
                                </li>
                                <li class="list-group-item">
                                    <h4 align="center"><a href="img_formation/formating.png"><span
                                                style="color:red;">ตั้งค่าหน้ากระดาษ</span></a></h4>
                                </li>
                                <li class="list-group-item active">หมวดหมู่</li>
                                <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="menu-icon fa fa-pencil-square-o"></i> บันทึกวันลา
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" href="header_mobile.php?st=add">บันทึกเวลาพักผ่อน</a>
                                    <a class="dropdown-item"
                                        href="index.php?lv=leave">บันทึกวันลากิจ/ลาป่วย/ลาคลอดบุตร</a>
                                </div>

                                <div class="dropdown">
                                    <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="menu-icon fa fa-pencil-square-o"></i> ประวัติวันลา
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" href="index.php?ht=history">ประวัติวันลาทั้งหมด</a>
                                    </div>
                                </div>

                                <div class="dropdown">
                                    <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="menu-icon fa fa-pencil-square-o"></i> แผนภูมิ/ปฏิทินแสดงวันลา
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item"
                                            href="index.php?chart">แสดงรายการภูมิและวันลาที่เหลือ</a>
                                        <a class="dropdown-item" href="index.php?calrender" class="list-group-item">
                                            ปฏิทินลา</a>
                                    </div>
                                </div>
                            </div>

                            <?php if ($_SESSION["Username"] == "admin" or $_SESSION["Username"] == "pratoom" or $_SESSION["Username"] == "Usanee") { ?>
                            <div class="dropdown">
                                <a href="#" class="list-group-item dropdown-toggle" id="dropdownMenuButton_history"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="menu-icon fa fa-pencil-square-o"></i> รายงาน
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_history">
                                    <a class="dropdown-item" href="backend/index_admin.php">รายงานวันลา</a>
                                </div>
                            </div>
                            <?php } ?>

                            <a class="list-group-item" href="logout.php?id=1"> <i class="ace-icon fa fa-power-off"> </i>
                                ออกระบบ</a>
                            <a class="list-group-item" href="logout.php?id=2"> <i class="ace-icon fa fa-home"> </i>
                                กลับสู่หน้าหลัก</a>

                            <?php if ($_SESSION["Username"] == 'admin') { ?>
                            <form method="POST">
                                <button type="submit" name="submit_gen_facel_year" value="submit_gen_facel_year"
                                    class="btn btn-primary">GEN
                                    วันลา</button>
                            </form>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container d-flex justify-content-between">

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>

    <main role="main">
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <?php
                        if (isset($_GET["calrender"])) {
                        include 'index_carlender.php';
                        } elseif (isset($_GET["st"]) && $_GET["st"] === "add") {
                        include 'diary_form_new.php';
                        } elseif (isset($_GET["lv"]) && $_GET["lv"] === "leave") {
                        include 'diary_form_leave_new.php';
                        } elseif (isset($_GET["ht"]) && $_GET["ht"] === "history") {
                        include 'discription_main.php';
                        } elseif (isset($_GET["chart"])) {
                        include 'chart_test.php';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
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


});
</script>

<script type="text/javascript">
function index() {
    location.href = "index.php";
}
</script>

<style>
.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

@media (min-width: 768px) {
    .bd-placeholder-img-lg {
        font-size: 3.5rem;
    }
}
</style>