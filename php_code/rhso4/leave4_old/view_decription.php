<!-- <link rel="stylesheet" href="fonts/style_tum.css"> -->
<link rel="stylesheet" href="fonts/thsarabunnew.css">
<!-- <style type="text/css" media="screen">
{ font-family: 'THSarabunNew', sans-serif; }  


</style> -->
<?php
if (!isset($_SESSION)) {
  session_start();
}
include_once 'head/headder.php';
include_once 'connect/connect.php';
// funtion 
function thainumDigit($num)
{
  return str_replace(
    array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'),
    array("o", "๑", "๒", "๓", "๔", "๕", "๖", "๗", "๘", "๙"),
    $num
  );
};

function DateThai($strDate)
{
  $strYear = date("Y", strtotime($strDate)) + 543;
  $strMonth = date("n", strtotime($strDate));
  $strDay = date("d", strtotime($strDate));
  $strHour = date("H", strtotime($strDate));
  $strMinute = date("i", strtotime($strDate));
  $strSeconds = date("s", strtotime($strDate));
  $strMonthCut = date("m", strtotime($strDate));


  // $strMonthThai=$strMonthCut[$strMonth];
  return "$strDay/$strMonthCut/$strYear";
}

// 
  $selectdecrip = "SELECT *,tyhd.type_name  typename FROM tbl_employment emp INNER JOIN tbl_holiday_detail detail ON emp.Emp_id = detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithol ON emp.Emp_id = limithol.Emp_id 
INNER JOIN tbl_type_hoilday tyhd ON tyhd.type_id = detail.type_id
where emp.Emp_id = '" . $_GET["emp"] . "' and detail.hoilday_detail_id = '" . $_GET["id"] . "'AND limithol.status_limit = 1 AND detail.type_id='" . $_GET["type_id"] . "'";
$queryselect = mysqli_query($objconnect, $selectdecrip);
$num_row = mysqli_num_rows($queryselect);
$featchselect = mysqli_fetch_array($queryselect);
// query ficalyear 
$featchselect['Financial_year'];

// 
$check_status = $featchselect["status_leave"];
$signatle_name = $featchselect["Titile_name"] . " " . $featchselect["Emp_name"] . " " . $featchselect["Emp_lastname"];
$newDate = thainumDigit(DateThai($featchselect["dateapprove_by"]));
if (isset($_POST["confrim"])) {
  if ($_POST["confrim"] == "confrim") {
    $update = "Update tbl_holiday_detail set status_leave =2 
      where Emp_id='" . $_GET["emp"] . "' and hoilday_detail_id='" . $_GET["id"] . "' and type_id='" . $_GET["type_id"] . "'";
    mysqli_query($objconnect, $update); ?>
<script>
x = <?= $_GET["id"] ?>;
y = <?= $_GET["emp"] ?>;
z = <?= $_GET["type_id"] ?>;
location.href = 'view_decription.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
</script>";
<?php
  }
} else if (isset($_POST["cancel"])) {
  if ($_POST["cancel"] = "cancel") {
    $update = "Update tbl_holiday_detail set status_leave =-2 
      where Emp_id='" . $_GET["emp"] . "' and hoilday_detail_id='" . $_GET["id"] . "' and type_id='" . $_GET["type_id"] . "'";
    mysqli_query($objconnect, $update);
  } ?>

<script>
x = <?= $_GET["id"] ?>;
y = <?= $_GET["emp"] ?>;
z = <?= $_GET["type_id"] ?>;
location.href = 'view_decription.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
</script>";
<?php
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css_and_js/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css_and_js/fontawesome-free-5.10.1-web/css/all.min.css">
    <script src="css_and_js/fontawesome-free-5.10.1-web/js/all.min.js"></script>
    <style type="text/css">
    body,
    .table {
        font-family: 'THSarabunNew', sans-serif;
        font-size: 20px;
        line-height: 1.7em;
    }

    h1,
    h2,
    h3,
    h4,
    h5 {
        font-family: 'THSarabunNew', sans-serif;
        line-height: 2em;
        clear: both;
    }

    p {
        padding: 0 0 10px 0;
    }

    .n {
        font-weight: normal;
        font-style: normal;
    }

    .b {
        font-weight: bold;
        font-style: normal;
    }

    .i {
        font-weight: normal;
        font-style: italic;
    }

    .bi {
        font-weight: bold;
        font-style: italic;
    }

    ul,
    ol {
        padding: 0 0 10px 0;
    }

    li {
        margin: 0 0 0 2em;
        padding: 0 0 5px 0;
    }

    code {
        background: #FFF;
    }

    #container {
        width: 900px;
        margin: 20px auto 0;
        border: 1px solid #333;
        padding: 20px;
        background: #FFF;
    }

    #instruction {
        padding: 10px 10px 0;
        background: #e6e6e6;
    }

    #footer {
        width: 940px;
        margin: 0 auto;
        padding: 10px 0 20px;
    }

    .right {
        text-align: right;
    }

    .type {
        font-family: 'THSarabunNew', sans-serif;
        height: 5em;
        width: 98%;
        font-size: 1em;
        clear: both;
        margin: 20px auto 10px;
        padding: 5px 1%;
    }

    @media print {
        #non-printable {
            display: none;
        }

        #container_div,
        .table {
            font-family: 'THSarabunNew', sans-serif;
            font-size: 20px;
            line-height: 1.7em;
            /*        margin-top: 10px;*/
            margin-left: 3cm;


        }

    }

    /*  body {  font-family: 'THSarabunNew', sans-serif,font-size: 40em; }*/
    </style>
</head>

<body>
    <!-- Breadcome -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb" id="non-printable">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item "> <a href="index.php?ht=history" class="btn btn-primary"><i
                                    class="fas fa-home"></i>HOME</a></li>
                        <div style="margin-left: 40%">
                            <button type="button" class="btn btn-primary" id="iconprint" onclick="window.print()"><i
                                    class="fa fa-print"></i> Print</button>
                        </div>
                        <?php if ($featchselect["status_leave"] == 1) { ?>
                        <div style="margin-left: 1%">
                            <button type="button" class="btn btn-custon-four btn-warning "
                                onclick="edit (<?= $_GET["id"] ?> ,<?= $_GET["emp"] ?> )"><i class="fa fa-edit"></i>
                                Edit</button>
                        </div>
                        <div style="margin-left: 1%">
                            <button type="button" class="btn btn-custon-three btn-danger" onclick="del
                       (<?php echo $_GET["id"] ?>,<?php echo $_GET["emp"] ?>,<?php echo $_GET["type_id"] ?>,  )"><i
                                    class="adminpro-icon adminpro-danger-error"></i> Delete</button></li>
                        </div>
                        <?php } else {
              echo "<div>
            
          </div>";
            } ?>
                    </ol>

                </nav>



            </div>
        </div>
    </div>
    <div class="container-fluid " id="non-printable" style="margin-left: 10%;">
        <div class="row">
            <div class="col-12">
                <div class="container-fluid">
                    <div class="col-6">
                        <!--    <form method="POST" accept-charset="utf-8">
          <div class="row" >
                
        <div  class="col-3">
     <?php if ($featchselect["status_leave"] == 2) { ?>
             <font><i class="fas fa-check"></i></font> <?php } ?>
                <button class="btn btn-success" name="confrim"  id="confrim" value="confrim"><i class="fas fa-check"></i>ยืนยัน</button> 
         </div>
         <div class="col-3" >
    <?php if ($featchselect["status_leave"] == -2) { ?>
              <font><i class="fas fa-check"></i></font>      <?php } ?>
                 <button class="btn btn-danger" name="cancel" id="cancle" value="cancle"><i class="fas fa-ban"></i>ไม่อนุมัติ</button>   
         </div>

      </div>
   </form>   -->
                    </div>
                    <div class="col-6"></div>

                </div>
            </div>

        </div>
    </div>

    </div>
    <?php
  $totalday = $featchselect["hoilday_totalday"];
  $totalhour = $featchselect["holiday_totalhour"];
  if ($featchselect["holiday_totalhour"] == 4) {
    // echo 'fuck';
    $totalhour = 0.5;
  }
  if ($featchselect["hoilday_totalday"] == "0") {
    // echo 'iftotal_day';
    $totalday = 0;
  }
  if ($featchselect["holiday_totalhour"] == "0") {
    // echo 'iftotal_hour';
    $totalhour = 0;
  }
  $totalsumsick = $totalday + $totalhour;

  $start_date = date("d/m/y", strtotime($featchselect["holiday_startdate"]));

  ?>
    <div id="container_div">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <h2 class="b" align="center">แบบใบลาพักผ่อน
                                        <?php if ($featchselect["status_leave"] == -1 || $featchselect["status_leave"] == -2 || $featchselect["status_leave"] == -3 || $featchselect["status_leave"] == -0) { ?>
                                        <h1 style="color: red;text-align: center;">ยกเลิก <?php if (($featchselect["status_leave"] == 0)) {
                                                                          echo "(เนื่องจาก" . $featchselect["cancle_approvesusecc_comment"] . ")";
                                                                        } ?></h1>
                                        <?php } ?>
                                    </h2><br>
                                    <p class="card-text" align="right" style="margin-right: 5%;">
                                        เขียน......<?= thainumDigit($featchselect["holiday_section"]) ?>.......</p>
                                    <p class="card-text" align="right" style="margin-right: 5%;">
                                        <font>วันที่.....................<?php


                                                      echo thainumDigit(DateThai($featchselect["register_date"])) ?>
                                        </font>.....................
                                    </p>

                                    <p class="card-text input_show1" style="margin-right: 31%;">
                                        <font>เรื่อง <?= $featchselect["typename"] ?></font>
                                    </p>
                                    <p class="card-text input_show1" style="margin-right: 31%;">
                                        <font>เรียน ผู้อำนวยการสำนักงานเขตสุขภาพที่ <?= thainumDigit(4) ?></font>
                                    </p>
                                    <font style="margin-left: 15%" class="text-justify">ข้าพเจ้า
                                        <?= $featchselect["Titile_name"], $featchselect["hoilday_detail_name"] ?>
                                        ตำแหน่ง <?= $featchselect["holiday_positon"] ?></font>
                                    <font class="text-justify"> สังกัด
                                        <?= thainumDigit($featchselect["holiday_section"]) ?> มีวันลาพักผ่อนสะสม
                                        <?= thainumDigit($featchselect["fix_count_oldyear_holiday"]) ?> วันทำการ
                                        มีสิทธิลาพักผ่อนประจำปีนี้อีก <?= thainumDigit(10) ?> วันทำการรวมเป็น
                                        <?= thainumDigit($fix_total = $featchselect["fix_count_oldyear_holiday"] + 10); ?>
                                        วันทำการ ขอลาพักผ่อนตั้งแต่วันที่
                                        <?= thainumDigit(DateThai(($featchselect["holiday_startdate"]))) ?> เวลา
                                        <?= thainumDigit(substr($featchselect["holiday_startime"], 0, 5)), " น." ?>...ถึงวันที่...<?= thainumDigit(DateThai($featchselect["holiday_enddate"])) ?>
                                        เวลา <?= thainumDigit(substr($featchselect["holiday_endtime"], 0, 5)), " น." ?>
                                        มีกำหนด <?= thainumDigit($totalsumsick) ?> วัน ในระหว่างลาจะติดต่อข้าพเจ้าได้ที่
                                        <?= thainumDigit($featchselect["holiday_contract"]) ?>
                                        ในระหว่างลานี้ได้มอบหมายให้ <?= $featchselect["holiday_contractobject"] ?>
                                    </font>
                                    <br>
                                    <p align="right" class="mb-3" style="margin-right: 25%;">
                                        <font>ขอแสดงความนับถือ</font>
                                    </p>
                                    <p align="right" style="margin-right: 45%">
                                        <font>ลงชื่อ</font>
                                    </p>
                                    <p align="right" class="mb-1" style="margin-right: 20%;">
                                        <font>(...<?= $signatle_name ?>...)</font>
                                    </p>
                                    <p align="right" class="mb-1" style="margin-right: 20%; ">ตำแหน่ง....<font>
                                            <?= $featchselect["holiday_positon"] ?></font>....
                                    <p>

                                        <?php
                      // เช็ควันเก่า//
                     $selectstatic = "SELECT *,tyhd.type_name  typename,SUM(hoilday_totalday)sumhd,SUM(holiday_totalhour)sumhr 
FROM tbl_employment emp
INNER JOIN tbl_holiday_detail detail ON emp.Emp_id = detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithol ON emp.Emp_id = limithol.Emp_id 
INNER JOIN tbl_type_hoilday tyhd ON tyhd.type_id = detail.type_id 
where emp.Emp_id = '" . $_GET["emp"] . "' and  tyhd.type_id=1 AND limithol.status_limit = 1  and detail.status_leave>0
 and detail.register_date < '" . $featchselect["register_date"] . "' AND detail.Financial_year='" . $featchselect['Financial_year'] . "'

";

                      $querystatic = mysqli_query($objconnect, $selectstatic);
                      $num_row = mysqli_num_rows($querystatic);
                      $featchstatic_old = mysqli_fetch_array($querystatic);

                      // echo 'string',$featchselect["holiday_startdate"]."<br>";

                      // echo 'old'.$featchstatic_old["holiday_startdate"];
                      //บวกค่า
                      $sumold = $featchstatic_old["sumhd"] + $featchstatic_old["sumhr"];
                      $sumday = $totalsumsick;
                      $sumtotal = $sumold + $sumday;
                      ?>
                                    <div class="container-fluid ">
                                        <div class="row">
                                            <div class="col-6">
                                                <font><u>สถิติการลาในปีงบประมาณนี้</u></font>

                                                <table class="table " style="margin-left: -10%;">
                                                    <thead class="thead-dark">
                                                        <tr align="center">
                                                            <td>ลามาแล้ว<br>(วันทำการ)</td>
                                                            <td>ลาครั้งนี้<br>(วันทำการ)</td>
                                                            <td>รวมเป็น<br>(วันทำการ)</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr align="center">
                                                            <td><?= thainumDigit($featchstatic_old["sumhd"] + $featchstatic_old["sumhr"]) ?>
                                                            </td>
                                                            <td><?= thainumDigit($totalsumsick) ?></td>
                                                            <td><?= thainumDigit($sumtotal) ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="col-6">
                                                <p>
                                                    <font><b><u>ความเห็นผู้บังคับบัญชา</u></b><br>
                                                        <font style=" border-bottom: 1px dashed #000 ">
                                                            <?= $featchselect["approve_comment"] ?>
                                                        </font>
                                                        <br><br>
                                                        <?php if (isset($featchselect["approve_by"])) { ?>
                                                        <font>
                                                            (ลงชื่อ).........<?= $featchselect["approve_by"] ?>...............
                                                        </font>
                                                        <?php } else { ?>
                                                        <font>(ลงชื่อ)..............................................
                                                        </font>
                                                        <?php } ?>
                                                        <?php if (isset($featchselect["approve_section_by"])) { ?>
                                                        <p>(ตำแหน่ง)....<?= $featchselect["approve_section_by"] ?>....
                                                        </p>
                                                        <?php   } else { ?>
                                                        <p>(ตำแหน่ง)..........................................</p>
                                                        <?php   } ?>



                                                        <?php
                                if ($featchselect["dateapprove_by"] == "") {
                                  echo   "<p>วันที่.................................................. </p>";
                                } else {
                                  echo   "<p>วันที่................." . $newDate . "................ </font>";
                                } ?>

                                                    </font>
                                                </p>
                                                <div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-6">
                                                <?php if (isset($featchselect["approve_by"])) { ?>
                                                <p>(ลงชื่อ).....<?= $featchselect["approve_by"] ?>.....ผู้ตรวจสอบ</p>
                                                <?php    } else { ?>
                                                <p>(ลงชื่อ).............................ผู้ตรวจสอบ</p>
                                                <?php    } ?>

                                                <?php if (isset($featchselect["approve_section_by"])) {
                            ?>
                                                <p>(ตำแหน่ง).....<?= $featchselect["approve_section_by"] ?>......</p>
                                                <?php } else { ?>

                                                <p>(ตำแหน่ง)........................................</p>
                                                <?php } ?>

                                                <?php if ($featchselect["status_leave"] == -1 or $featchselect["status_leave"] == 1) {
                              echo   "<font>วันที่..............................................</font>";
                            } else {


                              echo   "<font>วันที่................." . $newDate . ".............</font>";
                            } ?>
                                            </div>
                                            <div class="col-6">
                                                <p><u>คำสั่ง</u></p>

                                                <p style="width: 100%;">
                                                    <input type="checkbox" id="approve" disabled="true"
                                                        style="width: 20px; height: 20px; ">
                                                    <font> อนุญาต </font>
                                                    <font style="margin-left:1%;">
                                                        <input type="checkbox" disabled="true" id="disapporve" style="width: 20px; 
            height: 20px; "> ไม่อนุญาต
                                                    </font>
                                                </p>
                                                <font style=" border-bottom: 1px dashed #000 ">
                                                    <?= $featchselect["approve_comment_susecc"] ?>
                                                </font>
                                                <?php if (isset($featchselect["approve_sussecc"])) { ?>
                                                <p class="mt-5">
                                                    (ลงชื่อ)............<?= $featchselect["approve_sussecc"] ?>...............
                                                </p>
                                                <?php  } else { ?>
                                                <p class="mt-5">(ลงชื่อ).......................................</p>
                                                <?php } ?>
                                                <?php if (isset($featchselect["approve_section_by"])) { ?>
                                                <p>(ตำแหน่ง)....<?= $featchselect["approve_section_by"] ?>....</p>
                                                <?php   } else { ?>
                                                <p>(ตำแหน่ง)..........................................</p>
                                                <?php   } ?>

                                                <?php if ($check_status == 3 or $check_status == 3 or $check_status == 0) {
                              echo   "<p>วันที่...................." . thainumDigit(DateThai($featchselect["dateapprove_sussecc"])) . "........................ </font>";
                            } else {

                              echo   "<p>วันที่.............................................</font>";
                            } ?>
                                            </div>


                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- div end body  -->
                <!--         <div class="card-footer text-muted">2 days ago</div>
 -->
            </div>

        </div>

    </div>


    </div>

</body>
<script src="css_and_js/bootstrap-4.3.1-dist/js/jquery-3.3.1.slim.min.js"></script>
<script src="css_and_js/javascript/popper.min.js"></script>
<script src="css_and_js/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script>
function del(x, y, z, ficalyear) {

    if (confirm("ต้องการลบข้อมูลหรือไม่")) {
        location.href = 'delete_decription.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
    } else {
        console.log('g');
    }

}

function edit(x, y) {

    if (confirm("ต้องการแก้ไขข้อมูลหรือไม่")) {
        location.href = 'editdecription.php?id=' + x + '&&emp=' + y;
    } else {
        console.log('g');
    }

}
</script>
<script>
var status = <?= $check_status ?>;

if (status == 2 || status == 3) {
    document.getElementById("approve").checked = true;
} else if (status == -2 || status == -3 || status == 0) {
    document.getElementById("disapporve").checked = true;
}
</script>

</html>