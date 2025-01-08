<?php session_start();  ?>
<link rel="stylesheet" href="fonts/thsarabunnew.css">

<!-- <style type="text/css" media="screen">
{ font-family: 'THSarabunNew', sans-serif; }  

</style> -->
<?php

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
where emp.Emp_id = '" . $_GET["emp"] . "' and detail.hoilday_detail_id = '" . $_GET["id"] . "'AND limithol.status_limit = 1 
and  detail.type_id='" . $_GET["type_id"] . "' and detail.Financial_year ='" . $_GET['finyear'] . "' ";
$queryselect = mysqli_query($objconnect, $selectdecrip);
$num_row = mysqli_num_rows($queryselect);
$featchselect = mysqli_fetch_array($queryselect);
$check_status = $featchselect["status_leave"];
$newDate = thainumDigit(DateThai($featchselect["dateapprove_by"]));
$featchselect['Financial_year'];
$signatle_name = $featchselect["Titile_name"] . " " . $featchselect["Emp_name"] . " " . $featchselect["Emp_lastname"];
//////////////////////////////////////////////////// ลาครั้งหลังสุด//////////
$select_dayold = "SELECT sick_startdate,sick_enddate,sick_totalday,sick_totalhour,sick_type
FROM tbl_holiday_detail
WHERE Emp_id = '" . $_GET["emp"] . "'
 AND Type_id = '" . $_GET["type_id"] . "' 
 and status_leave>0
 and sick_startdate < '" . $featchselect["sick_startdate"] . " ' and Financial_year ='" . $_GET['finyear'] . "' 
 ORDER BY sick_startdate DESC ";
$query_dayold = mysqli_query($objconnect, $select_dayold);
$numdayold = mysqli_num_rows($query_dayold);
$featchnum_old = mysqli_fetch_array($query_dayold);
$count_old = $featchnum_old["sick_totalday"] + $featchnum_old["sick_totalhour"];


//////////////////////////////////////////////////// ลาครั้งหลังสุด//////////
//////////////////////////////////////////////////// typeป่วย //////////
$select_countsick = "SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr  
FROM tbl_holiday_detail
WHERE Emp_id = '" . $_GET["emp"] . "' AND Type_id = '" . $_GET["type_id"] . "' 
AND sick_type='ป่วย' and status_leave>0 
and sick_startdate< '" . $featchselect["sick_startdate"] . "' and Financial_year='" . $featchselect['Financial_year'] . "'";
$query_countsick = mysqli_query($objconnect, $select_countsick);
$mynum = mysqli_num_rows($query_countsick);

//ป่วย
$old_countsick = mysqli_fetch_array($query_countsick);
$old_daysick = $old_countsick["dy"] + $old_countsick["hr"];

/////////////////////////////type ลากิจส่วนตัว///////////////////////

$select_typename2 = "SELECT *,SUM(sick_totalday)dy ,SUM(sick_totalhour)hr     
FROM tbl_holiday_detail  WHERE Emp_id = '" . $_GET["emp"] . "' AND Type_id = '" . $_GET["type_id"] . "' AND sick_type='ขอลากิจส่วนตัว' and status_leave>0 
AND sick_startdate < '" . $featchselect["sick_startdate"] . "' and Financial_year ='" . $_GET['finyear'] . "'";

$query_typename2 = mysqli_query($objconnect, $select_typename2);
$num_typename2 = mysqli_num_rows($query_typename2);
// echo 'string',$num_typename2;
$featch_typename2 = mysqli_fetch_array($query_typename2);
$counttype_2 = $featch_typename2["dy"] + $featch_typename2["hr"];

//////////////////////////////////////////////////////////////////////
/////////////////////////////type ลาคลอด///////////////////////
$select_typename3 = "SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr     
FROM tbl_holiday_detail  WHERE Emp_id = '" . $_GET["emp"] . "' AND Type_id = '" . $_GET["type_id"] . "' AND sick_type='คลอตบุตร' and status_leave>0 
AND sick_startdate < '" . $featchselect["sick_startdate"] . "'";

$query_typename3 = mysqli_query($objconnect, $select_typename3);
$num_typename3 = mysqli_num_rows($query_typename3);
// echo 'string',$num_typename3;
$featch_typename3 = mysqli_fetch_array($query_typename3);
$counttype_3 = $featch_typename3["dy"] + $featch_typename3["hr"];

//////////////////////////////////////////////////////////////////////
if (isset($_POST["confrim"])) {
    if ($_POST["confrim"] == "confrim") {
        $update = "Update tbl_holiday_detail set status_leave =2 ,approve_by='" . $approve_by . "',dateapprove_by=NOW(),approve_section_by='" . $_SESSION["secname"] . "'
      where Emp_id='" . $_GET["emp"] . "' and hoilday_detail_id='" . $_GET["id"] . "' and type_id='" . $_GET["type_id"] . "'";
        mysqli_query($objconnect, $update); ?>
        <script>
            x = <?= $_GET["id"] ?>;
            y = <?= $_GET["emp"] ?>;
            z = <?= $_GET["type_id"] ?>;
            location.href = 'view_decription_leave.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
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
        location.href = 'view_decription_leave.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
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
        .table,
        #to_leave1 {
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

            #container_div {
                font-family: 'THSarabunNew', sans-serif;
                /* margin-top: 30px;*/
                /*  text-align: justify;
             text-justify: inter-word;*/
                margin-left: 1.5cm;

                font-size: 20px;

            }

            body {
                font-family: 'THSarabunNew', sans-serif;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <!-- Breadcome -->
    <nav aria-label="breadcrumb" id="non-printable">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "> <a href="index.php?ht=history" class="btn btn-primary">HOME</a></li>
            <div style="margin-left: 70%">
                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i>
                    Print</button>
            </div>
            <?php if ($featchselect["status_leave"] == 1) { ?>
                <div style="margin-left: 1%">
                    <button type="button" class="btn btn-custon-four btn-warning " onclick="edit (<?= $_GET["id"] ?> ,<?= $_GET["emp"] ?> ,<?= $_GET["type_id"] ?>,<?= $_GET['finyear'] ?>)"><i class="fa fa-edit"></i> Edit</button>
                </div>
                <div style="margin-left: 1%">
                    <button type="button" class="btn btn-custon-three btn-danger" onclick="del(<?= $_GET["id"] ?>,<?= $_GET["emp"] ?> ,<?= $_GET["type_id"] ?>)"><i class="adminpro-icon adminpro-danger-error"></i> Delete</button></li>
                </div>
            <?php } else {
                echo "<div>
          </div>";
            } ?>
        </ol>
    </nav>
    <div id="container_div">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body ">
                                    <h2 class="b" align="center">แบบใบลาป่วย ลาคลอดบุตร ลากิจส่วนตัว
                                        <?php if ($featchselect["status_leave"] == -1 || $featchselect["status_leave"] == -2 || $featchselect["status_leave"] == -3 || $featchselect["status_leave"] == 0) { ?>
                                            <h1 style="color: red;text-align: center;">ยกเลิก <?php if (($featchselect["status_leave"] == 0)) {
                                                                                                    echo "(เนื่องจาก" . $featchselect["cancle_approvesusecc_comment"] . ")";
                                                                                                } ?>
                                            </h1>
                                        <?php } ?>
                                    </h2>
                                    <p class="card-text" align="right" style="margin-right: 5%;">
                                        เขียน......<?= thainumDigit($featchselect["sick_section"]) ?>.......</p>
                                    <p class="card-text" align="right" style="margin-right: 5%;">
                                        <font>
                                            วันที่.........................<?= thainumDigit(DateThai($featchselect["register_date"])) ?>
                                        </font>.........................
                                    </p>
                                    <p class="card-text input_show1" style="margin-right: 31%;">
                                        <font>เรื่อง ขออนุญาตลา <?= thainumDigit($featchselect["sick_type"]) ?></font>
                                    </p>
                                    <p class="card-text input_show1" style="margin-right: 31%;">
                                        <font>เรียน ผู้อำนวยการสำนักงานเขตสุขภาพที่ <?= thainumDigit(4) ?></font>
                                    </p>
                                    <p class="card-text input_show1" style="margin-left: 5%;margin-bottom: -10px;margin-top: -10px;">
                                        <font> ข้าพเจ้า ......<font class="">
                                                <?= $featchselect["hoilday_detail_name"] ?>......</font>
                                            ตำแหน่ง ......<font class=""> <?= $featchselect["sick_positon"] ?></font>.....
                                        </font>
                                    </p>
                                    <?php
                                    $type_group = "select * from tbl_group_type";
                                    $query_group = mysqli_query($objconnect, $type_group);
                                    $num_type = mysqli_num_rows($query_group);

                                    ?>
                                    <p class="card-text " style="margin-left:1.5%">
                                        <?php $num_query_group = 1;
                                        while ($featch_query_group = mysqli_fetch_array($query_group)) {
                                            $checkboxtype = "checkboxtype" . $num_query_group;
                                            $num_query_group++;
                                        ?>
                                            <font style="margin-left: 1%"><input type="checkbox" name="checkboxtype" id="<?= $checkboxtype ?>" disabled value="<?= $featch_query_group["group_id"] ?>">
                                                <?= $featch_query_group["name"] ?>
                                            </font>
                                        <?php } ?>
                                    </p>
                                    <div class="checkbox-margin" style="margin-bottom: -10px;margin-top: -10px;">
                                        <p class="card-text ">
                                            <font style="margin-left: 3.2%;"> <input class="form-check-input" type="checkbox" name="check_leave" value="ป่วย" id="sick_1">ป่วย
                                            </font>
                                            <font style="margin-left: 11%;">
                                                เนื่องจาก <input type="text" class="input_show2" disabled="true" id="to_leave1" name="To_leave_1" disabled="true" style="width: 50%;font-size: 20px;padding-left: 25px;">
                                            </font>
                                        </p>
                                        <p class="card-text ">
                                            <font style="margin-left: 3.2%;"> <input class="form-check-input" type="checkbox" disabled="true" name="check_leave" value="ขอลากิจส่วนตัว" id="sick_2">ขอลากิจส่วนตัว
                                            </font>
                                            <font style="margin-left: 1%;">
                                                เนื่องจาก <input type="text" class="input_show2" disabled="true" id="to_leave2" name="To_leave" style="width: 50%;font-size: 20px;padding-left: 25px;">
                                            </font>
                                        </p>
                                        <p class="card-text ">
                                            <font style="margin-left: 3.2%;"> <input class="form-check-input" type="checkbox" name="check_leave" value="คลอดบุตร" id="sick_3" disabled="true">คลอดบุตร
                                            </font>
                                        </p>
                                    </div>
                                    <?php
                                    $totalday = $featchselect["sick_totalday"];
                                    $totalhour = $featchselect["sick_totalhour"];

                                    if ($featchselect["sick_totalhour"] == 4) {
                                        // echo 'fuck';
                                        $totalhour = 0.5;
                                    }
                                    if ($featchselect["sick_totalday"] == "0") {
                                        // echo 'iftotal_day';
                                        $totalday = 0;
                                    }
                                    if ($featchselect["sick_totalhour"] == "0") {
                                        // echo 'iftotal_hour';
                                        $totalhour = 0;
                                    }
                                    $totalsumsick = $totalday + $totalhour;

                                    ?>
                                    <p class="card-text " style="margin-left: 1.5%;margin-bottom: -10px;margin-top: -10px;">
                                        ขอลาตั้งแต่วันที่..<?= thainumDigit(DateThai($featchselect["sick_startdate"])) ?>..เวลา..<?= thainumDigit(substr($featchselect["sick_startime"], 0, 5)), " น" ?>..
                                        ถึงวันที่
                                        ..<?= thainumDigit(DateThai($featchselect["sick_enddate"])) ?>..เวลา..<?= thainumDigit(substr($featchselect["sick_endtime"], 0, 5)), " น" ?>..<br>มีกำหนด<?= ".....", thainumDigit($totalsumsick), "....." ?>วัน
                                    </p>
                                    <p class="card-text " style="margin-left:1.5%;margin-bottom: -10px;margin-top: -10px;">
                                        <font>ข้าพเจ้าได้ลา</font>
                                        <font style="margin-left: 3.2%;"> <input class="form-check-input" type="checkbox" disabled="true"  name="check_leave" value="ป่วย" id="sick_1_1">ป่วย
                                        </font>
                                        <font style="margin-left: 3.2%;"> <input class="form-check-input" type="checkbox" disabled="true" name="check_leave" value="ขอลากิจส่วนตัว" id="sick_2_1">ขอลากิจส่วนตัว
                                        </font>
                                        <font style="margin-left: 3.2%;"> <input class="form-check-input" type="checkbox" disabled="true" name="check_leave" value="ขอลากิจส่วนตัว" id="sick_3_1">คลอดบุตร
                                        </font>
                                    </p>
                                    <p class="card-text " style="margin-left:10px;margin-bottom: -10px;margin-top: -10px;">
                                        <font>ครั้งสุดท้ายวันที่</font>
                                        <font class="">
                                            <?php if ($featchnum_old["sick_startdate"] == "") { ?>...................................
                                        <?php } else {
                                                echo '....' . thainumDigit(DateThai($featchnum_old["sick_startdate"])) . '....';
                                            } ?>
                                        </font>ถึงวันที่
                                        <font class="">
                                            <?php if ($featchnum_old["sick_startdate"] == "") { ?>
                                                ...................................
                                                มีกำหนด
                                                <font class=""> .............<?= thainumDigit(0) ?>.............</font>
                                                วัน<?php  } else {
                                                    echo  '....' . thainumDigit(DateThai($featchnum_old["sick_enddate"])) . '....'; ?>.............
                                        </font> มีกำหนด
                                        <font class=""> .............<?= thainumDigit($count_old) ?>.............</font>
                                        วัน (<?php echo thainumDigit($featchnum_old["sick_type"]), ")";
                                                } ?>
                                    </p>
                                    <p align="right" class="mb-3" style="margin-right: 23%;">
                                        <font>ขอแสดงความนับถือ</font>
                                    </p>
                                    <p align="right" style="margin-right: 20.9%;margin-bottom: -10px;margin-top: 50px;">
                                        <font>(...<?= $signatle_name ?>...)</font>
                                    </p>
                                    <p align="right" style="margin-right: 20%; ">ตำแหน่ง....<font>
                                            <?= $featchselect["sick_positon"] ?></font>....
                                    <p>
                                    <div class="container-fluid">
                                        <div class="row" style="margin-bottom: -30px;margin-top: -20px;">
                                            <div class="col-6">
                                                <font><u>สถิติการลาในปีงบประมาณนี้</u></font>
                                                <table class="table">
                                                    <thead class="table table-hover thead-dark">
                                                        <tr align="center">
                                                            <td>ประเภทการลา</th>
                                                            <td>ลามาแล้ว</th>
                                                            <td>ลาครั้งนี้</th>
                                                            <td>รวมเป็น</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr align="center" class="table-light">
                                                            <td>ป่วย</td>
                                                            <td><?= thainumDigit($old_daysick) ?></td>
                                                            <td>
                                                                <?php if ($featchselect["sick_type"] == "ป่วย") {
                                                                    echo thainumDigit($totalsumsick);
                                                                } else {
                                                                    echo thainumDigit(0);
                                                                } ?>
                                                            </td>
                                                            <td><?php if ($featchselect["sick_type"] == "ป่วย") {
                                                                    echo thainumDigit($totalsumsick + $old_daysick);
                                                                } else {
                                                                    echo thainumDigit($old_daysick);
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr align="center" class="table-secondary">
                                                            <td>กิจส่วนตัว</td>
                                                            <td><?= thainumDigit($counttype_2) ?></td>
                                                            <td>
                                                                <?php if ($featchselect["sick_type"] == "ขอลากิจส่วนตัว") {
                                                                    echo thainumDigit($totalsumsick);
                                                                } else {
                                                                    echo thainumDigit(0);
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($featchselect["sick_type"] == "ขอลากิจส่วนตัว") {
                                                                    echo thainumDigit($totalsumsick + $counttype_2);
                                                                } else {
                                                                    echo thainumDigit($counttype_2);
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                        <tr align="center" class="table-info">
                                                            <td>คลอดบุตร</td>
                                                            <td><?= thainumDigit($counttype_3) ?></td>
                                                            <td>
                                                                <?php if ($featchselect == "ขอลากิจส่วนตัว") {
                                                                    echo thainumDigit($totalsumsick);
                                                                } else {
                                                                    echo thainumDigit(0);
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($featchselect["sick_type"] == "คลอตบุตร") {
                                                                    echo thainumDigit($totalsumsick + $counttype_3);
                                                                } else {
                                                                    echo thainumDigit($counttype_3);
                                                                }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                            <div class="col-6">
                                                <p>
                                                    <font><b><u>ความเห็นผู้บังคับบัญชา</u></b>
                                                        <font style=" border-bottom: 1px dashed #000 ;margin-bottom: 10px;margin-top: 10px;">
                                                            <p><?= $featchselect["approve_comment"] ?></p>
                                                        </font>
                                                        <?php if (isset($featchselect["approve_by"])) { ?>
                                                            <font>
                                                                (ลงชื่อ).........<?= $featchselect["approve_by"] ?>..........
                                                            </font>
                                                        <?php } else { ?> <font>
                                                                (ลงชื่อ)................................................
                                                            </font>
                                                        <?php } ?>
                                                        <?php if (isset($featchselect["approve_section_by"])) { ?>
                                                            <p>(ตำแหน่ง).....<?= $featchselect["approve_section_by"] ?>....
                                                            </p>
                                                        <?php  } else { ?>
                                                            <p>(ตำแหน่ง)...........................................</p>
                                                        <?php  } ?>
                                                        <?php
                                                        if ($featchselect["status_leave"] != 1) {
                                                            echo   "<p>วันที่........................" . $newDate . "......................... </font>";
                                                        } else {
                                                            echo   "<p>วันที่...................................................</p>";
                                                        } ?>
                                                    </font>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="container-fluid ">
                                        <div class="row mt-3">
                                            <div class="col-6">
                                                <?php if (isset($featchselect["approve_by"])) { ?>
                                                    <p>(ลงชื่อ)..........<?= $featchselect["approve_by"] ?>...........ผู้ตรวจสอบ
                                                    </p>
                                                <?php   } else { ?>
                                                    <p>(ลงชื่อ)..................................ผู้ตรวจสอบ</p>
                                                <?php } ?>
                                                <?php if (isset($featchselect["approve_section_by"])) { ?>
                                                    <p style="margin-top: -20px;">
                                                        (ตำแหน่ง).....<?= $featchselect["approve_section_by"] ?>......</p>
                                                <?php } else { ?>
                                                    <p style="margin-top: -20px; ">
                                                        (ตำแหน่ง).......................................</p>
                                                <?php } ?>
                                                <?php if ($featchselect["status_leave"] == -1 or $featchselect["status_leave"] == 1) {
                                                    echo   "<font style=\"margin-top: -20px;\">วันที่..................................................</p>";
                                                } else {
                                                    echo   "<p style=\"margin-top: -20px;\">วันที่......................." . $newDate . "......................</font>";
                                                } ?>
                                            </div>
                                            <div class="col-6">
                                                <p style="margin-bottom: -20px;">คำสั่ง</p>
                                                <p style="width: 100%">
                                                    <input type="checkbox" id="approve" disabled="true"> อนุญาต
                                                    <font style="margin-left:1%;">
                                                        <input type="checkbox" disabled="true" id="disapporve">
                                                        ไม่อนุญาต
                                                    </font>
                                                </p>
                                                <font style=" border-bottom: 1px dashed #000 ;">
                                                    <?= $featchselect["approve_comment_susecc"] ?>
                                                </font>
                                                <?php if (isset($featchselect["approve_sussecc"])) { ?>
                                                    <p class="mt-3">
                                                        (ลงชื่อ)..............<?= $featchselect["approve_sussecc"] ?>..............
                                                    </p>
                                                <?php } else { ?>
                                                    <p style=" ">
                                                        (ลงชื่อ)..................................................................
                                                    </p>
                                                <?php } ?>

                                                <?php if (isset($featchselect["approve_section_seccess"])) { ?>
                                                    <p style="margin-top: -20px;">
                                                        (ตำแหน่ง).....<?= $featchselect["approve_section_seccess"] ?>....</p>
                                                <?php } ?>

                                                <?php if ($check_status == 3 or $check_status == 3) {
                                                    echo   "<p style=\"margin-top: -20px;\">วันที่......................" . thainumDigit(DateThai($featchselect["dateapprove_sussecc"])) . "......................</font>";
                                                } else {
                                                    echo   "<p style=\"margin-top: -20px;\">วันที่.......................................................................</font>";
                                                } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<script src="css_and_js/bootstrap-4.3.1-dist/js/jquery-3.3.1.slim.min.js"></script>
<script src="css_and_js/javascript/popper.min.js"></script>
<script src="css_and_js/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
<script>
    function del(x, y, z) {

        if (confirm("ต้องการลบข้อมูลนี้หรือไม่")) {
            location.href = 'delete_decription.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
        } else {
            console.log('g');
        }

    }

    function edit(x, y, z, year) {

        if (confirm("ต้องการแก้ไขหรือไม่")) {
            location.href = 'editdecription_leave.php?id=' + x + '&&emp=' + y + '&&type_id=' + z + '&&year=' + year;
        } else {
            console.log('g');
        }

    }
</script>
<script>
    //type checkbox 1
    var num_type = "<?= $num_type ?>";
    var checkboxtype = "checkboxtype";
    var check = "<?= $featchselect["group_id"] ?>";
    var checkbox = document.getElementById("checkboxtype");

    for (var i = 1; i <= num_type; i++) {
        var x = checkbox + i;
        // console.log(x);
        if (x == check) {

            document.getElementById("checkboxtype" + i).checked = true;

            // console.log(document.getElementById("checkboxtype"+i))
        }
    }
</script>

<!-- <script> //type checkbox 2
  var num_type_1="<?= $num_type_1 ?>";
  var checkboxtype_1="checkboxtype_1";
   var check_1 ="<?= $featchselect["type_id"] ?>";
   var checkbox_1 = document.getElementById("checkboxtype_1");

for (var i =1 ;i<= num_type_1; i++) {
 var x_1 =checkbox+i;
 // console.log(x);
  if (x_1==check_1) {
    document.getElementById("checkboxtype_1"+i).checked = true;
    // console.log(document.getElementById("checkboxtype_1"+i))
  }
}
</script> -->
<script>
    //เช็ค detail
    var type_old = "<?= $featchnum_old["sick_type"] ?>"; //เช็ควันในอดีต
    var type = "<?= $featchselect["sick_type"] ?>"; //ปัจจุบัน
    var sickdetail = "<?= $featchselect["sick_detail"] ?>";
    var sick_1 = "sick_1";
    var to_leave1 = "to_leave1";
    var sick_2 = "sick_2";
    var to_leave2 = "to_leave2";
    var sick_3 = "sick_3";

    console.log(type_old, type)

    if (type == "ป่วย") {

        document.getElementById(sick_1).checked = true;
        document.getElementById(to_leave1).value = sickdetail;

    } else if (type == "ขอลากิจส่วนตัว") {
        document.getElementById(sick_2).checked = true;
        document.getElementById(to_leave2).value = sickdetail;

    } else {
        document.getElementById(sick_3).checked = true;

    }

    if (type_old != '') {
        if (type_old == "ป่วย") {
            document.getElementById("sick_1_1").checked = true;

        } else if (type_old == "ขอลากิจส่วนตัว") {


            document.getElementById("sick_2_1").checked = true;

        } else {

            document.getElementById("sick_3_1").checked = true;
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