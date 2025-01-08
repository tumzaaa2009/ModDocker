<?php
include 'connect/connect.php';

require 'head/headder.php';




//////////////////////////////////////////////////////////////////////ดึงชื่อผู้ลาพักร้น
$selectuse = "SELECT total_limit.Financial_year,emp.Emp_id as id,emp.Emp_name as empname ,emp.Emp_lastname as emplastname,pgroup.name as emppositionname,af.affiliate_name as affname ,gtype.group_id as groupid , gtype.name as typename
FROM tbl_employment AS emp 
INNER JOIN affiliation AS af ON emp.affiliate_id = af.affiliate_id  
INNER JOIN tbl_group_type gtype ON emp.group_id=gtype.group_id
INNER JOIN tbl_position_group pgroup ON emp.Position_id=pgroup.Position_id
INNER JOIN tbl_total_limit_holiday total_limit on total_limit.Emp_id = emp.Emp_id
where emp.Emp_id='" . $_SESSION["Emp_id"] . "' AND total_limit.status_limit='1' ";
$queryuserfrom = mysqli_query($objconnect, $selectuse);
$featchuserfrom = mysqli_fetch_array($queryuserfrom);

$startvar = "ประกาศตัวแปร";
$featchuserfrom["empname"];
$featchuserfrom["emplastname"];
$featchuserfrom["emppositionname"];
$featchuserfrom["affname"];
$featchuserfrom["Financial_year"];
$endvar = "จบ";

//////////////////////////////////////////////////////////////////////ENDดึงชื่อผู้ลาพักร้น

///////////////////////////////เช็คเอามาบวกลบแล้วนำเข้า
$selectchecklimitday = "SELECT * FROM tbl_total_limit_holiday WHERE 
Emp_id = '" . $_SESSION["Emp_id"] . "' AND status_limit = 1";
$querychcklimitday = mysqli_query($objconnect, $selectchecklimitday);
$numchecklimitday = mysqli_num_rows($querychcklimitday);
$featchchecklimitday = mysqli_fetch_array($querychcklimitday);
/////////////////////////////////จบ นำเข้า////////////////



// ///////////////////////////////isset/////////////////////////////
if (isset($_POST["btnsub"])) {
  // echo '<pre>';
  // echo  print_r($_POST);
  // echo '</pre>';
  $selectholidaynum = "SELECT MAX(convert(hoilday_detail_id,SIGNED)) AS hoilday_detail_id FROM tbl_holiday_detail";
  $queryholidaynum = mysqli_query($objconnect, $selectholidaynum);
  $featchholidaynum = mysqli_fetch_array($queryholidaynum);
  $numholidaynum = $featchholidaynum["hoilday_detail_id"] + 1;

  if ($_POST["Total_Day"] != '' || $_POST["Total_Hour"] != '') {
    $totalday = $_POST["Total_Day"];
    $totalhour = $_POST["Total_Hour"];
    if ($_POST["Total_Hour"] == 4) {
      // echo 'fuck';
      $totalhour = 0.5;
    }
    if ($_POST["Total_Day"] == "-") {
      // echo 'iftotal_day';
      $totalday = 0;
    }
    if ($_POST["Total_Hour"] == "-") {
      // echo 'iftotal_hour';
      $totalhour = 0;
    }

    $totalsumsick = $totalday + $totalhour + $featchchecklimitday["sum_thisyear_leave"];

    // echo $totalday;

    $holiday_detail_insert = "INSERT INTO tbl_holiday_detail
 (
 hoilday_detail_id
 ,type_id
 ,Emp_id
 ,group_id
 ,register_date
 ,hoilday_detail_name
 ,holiday_count_year_old
 ,holiday_count_total_year
 ,holiday_startdate 
 ,holiday_startime
 ,holiday_enddate 
 ,holiday_endtime
 ,hoilday_totalday
 ,holiday_totalhour
 ,holiday_positon
 ,holiday_section
 ,holiday_contract
 ,holiday_contractobject
 ,sick_positon
 ,sick_section
 ,sick_type
 ,sick_detail
 ,sick_startdate
 ,sick_startime
 ,sick_enddate
 ,sick_endtime
 ,sick_totalday
 ,sick_totalhour
 ,status_leave
 ,Financial_year
 )
    VALUES 
    ('" . $numholidaynum . "'
    ,'" . $_POST["type_id"] . "' 
    ,'" . $_POST["id"] . "' 
    ,'" . $_POST["group_id"] . "'
    ,NOW()
    ,'" . $_POST["uname"] . "'
    ,'" . "-" . "'
    ,'" . "-" . "'
    ,'" . '0000-00-00' . "'
    ,'" . '00:00:00' . "'
    ,'" . '0000-00-00' . "'
    ,'" . '00:00:00' . "' 
    ,'" . "-" . "'
    ,'" . "-" . "'
    ,'" . "-" . "'
    ,'" . "-" . "'
    ,'" . "-" . "'
    ,'" . "-" . "'
    ,'" . $_POST["position"] . "' 
    ,'" . $_POST["section"] . "'
    ,'" . $_POST["check_leave"] . "'  
    ,'" . $_POST["To_leave"] . "'  
    ,'" . $_POST["Start_date"] . "'  
    ,'" . $_POST["Start_time"] . "' 
    ,'" . $_POST["End_date"] . "'  
    ,'" . $_POST["End_time"] . "'  
    ,'" . $_POST["Total_Day"] . "' 
    ,'" . $totalhour . "' 
    ,1
    ,'" . $_POST["Financial_year"] . "'
  ) ";
    $query_horidayinsert = mysqli_query($objconnect, $holiday_detail_insert);

    $updateholiday = "UPDATE tbl_total_limit_holiday
  SET  sum_thisyear_leave='" . $totalsumsick . "'
  WHERE Emp_id = '" . $_SESSION["Emp_id"] . "' AND status_limit = 1";
    mysqli_query($objconnect, $updateholiday);
 $selectToken = "SELECT Line_token FROM tbl_employment WHERE Section_Command ='".$_SESSION["work_group"]."'";
 $queryTokenId = mysqli_query($objconnect, $selectToken);
$featchTokenId = mysqli_fetch_array($queryTokenId);
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://notify-api.line.me/api/notify',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => 'message='.$_POST["uname"] .'  ลาป่วย '.$_POST["Start_date"].'ถึงวันที่ '.$_POST['End_date'].'',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    'Authorization: Bearer '.$featchTokenId['Line_token'].' '
  ),
));
$response = curl_exec($curl);


  } //isset tottalday,hour
  $idEmp = $_SESSION["Emp_id"] ;
  $fsYear= $_POST["Financial_year"];
  echo "<script>alert(\"บันทึกข้อมูลเรียบแล้ว\");window.location.href = \"view_decription_leave.php?id=$numholidaynum&&emp=$idEmp&&type_id=2&&finyear=$fsYear\";</script>";
} //isset btn
?>
<!-- clockpicker -->
<link rel="stylesheet" href="css_and_js/dist/bootstrap-clockpicker.css">
<!--  -->
<style type="text/css" media="screen">
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

<body>
    <div class="page-header" align="center">
        <h1 style="color: black"> แบบใบลาป่วย ลาคลอดบุตร ลากิจส่วนตัว </h1>

    </div><!-- /.page-header -->

    <div class="container-fulid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <form method="post">
                    <table style="margin: auto;" border="0">
                        <tbody>
                            <tr>

                                <td colspan="12" width="50%;" style=" padding-left: 75%">
                                    <lable>เขียน </lable>
                                    <lable
                                        style="BORDER-BOTTOM: #0C0C0C 1px  dotted;text-align: center; padding-right: 10%">
                                        ที่สำนักงานเขตสุขภาพที่ 4</lable>
                                </td>
                            </tr>
                            <tr>

                                <td colspan="12" style=" padding-left: 75%">
                                    <lable>วันที่ </lable>
                                    <lable
                                        style="BORDER-BOTTOM: #0C0C0C 1px  dotted; padding-left: 3%;text-align: center; ">
                                        <?= date("d/m/Y "); ?></lable>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="12" align="left">
                                    <div class="form-inline">
                                        <div class="col-1.5">
                                            <lable>เรื่อง ขออนุญาติลา </lable>
                                        </div>
                                        <div class="col-1">
                                            <input type="text" class="form-control" id="text_type_sick" readonly="true">
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="12" align="left">
                                    <p class="text-left">เรียน ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="1" align="right" width="15%"> ข้าพเจ้า </td>
                                <td colspan="3">
                                    <!-- เก็บค่า typeของวันลา -->
                                    <input type="hidden" name="id" value="<?= $featchuserfrom["id"] ?>">
                                    <!--   <input type="hidden" name="hdncount" id="hdncount"> -->
                                    <!-- type_id ประเภทใบลา 2 ลาป่วย-->
                                    <input type="hidden" name="type_id" value="2">
                                    <!-- END -->
                                    <input type="text" name="uname" class="form-control input-sm"
                                        value="<?= $featchuserfrom["empname"], "  ", $featchuserfrom["emplastname"];; ?>"
                                        readonly>
                                    <!--   <input class="form-control input-sm"  type="text" name="uname" placeholder="ชื่อ"  value="<?= $featchuserfrom["empname"], "  ", $featchuserfrom["emplastname"];; ?>" readonly> -->
                                </td>
                                <td colspan="1"> ตำแหน่ง</td>
                                <td colspan="3"> <input class="form-control" type="text" style="text-align: center;"
                                        readonly name="position" value="<?= $featchuserfrom["emppositionname"] ?>"
                                        placeholder="ตำแหน่ง"></td>

                            </tr>
                            <tr style="text-align: center; ">
                                <td colspan="" rowspan="" headers=""> ประเภทพนักงาน</td>
                                <td width="15%"><input type="hidden" name="group_id"
                                        value="<?= $featchuserfrom["groupid"] ?>">
                                    <input type="text" align="left" readonly="true" class="form-control input_show2"
                                        value="<?= $featchuserfrom["typename"] ?> ">
                                </td>
                                <td>สังกัด</td>
                                <td colspan="2"><input class="form-control" type="text" style="text-align: center;"
                                        readonly value="<?= $featchuserfrom["affname"] ?>" name="section"
                                        placeholder="สังกัด"></td>
                                <td colspan="2"></td>

                            <tr>
                                <td colspan="1" style="padding-left:3%"><input class="form-check-input" type="checkbox"
                                        name="check_leave" value="ป่วย" id="sick" onclick="onsick()">ป่วย</td>
                                <td colspan="11"> เนื่องจาก <input type="text" class="input_show2" disabled="true"
                                        id="to_leave1" required name="To_leave"> </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="padding-left:3%;width: 30%">
                                    <input class="form-check-input" type="checkbox" value="ขอลากิจส่วนตัว"
                                        name="check_leave" id="sick_2" onclick="onsick()">ขอลากิจส่วนตัว
                                </td>
                                <td colspan="11"> เนื่องจาก <input type="text" class="input_show2" disabled="true"
                                        id="to_leave2" required name="To_leave"> </td>
                            </tr>
                            <tr>
                                <td colspan="1" style="padding-left:3%"><input class="form-check-input" type="checkbox"
                                        value="คลอตบุตร" name="check_leave" id="sick_3" onclick="onsick()">คลอดบุตร</td>
                                <td colspan="11" rowspan="" headers=""></td>
                            </tr>

                            <tr>
                                <td headers=""><input type="hidden" class="form-control" id="Financial_year"
                                        name="Financial_year" readonly>(**เลือกวันจากปฏิทินอย่าพิมจ้าา***)</td>

                                <td colspan="1">ตั้งแต่วันที่ </td>
                                <td colspan="1"> <input id="Start_date" name="Start_date" type="date"
                                        class="form-control" required="required" onchange="caltime()"></td>
                                <td colspan="1" width="10%"> ตั้งแต่เวลา </td>
                                <td colspan="1">
                                    <input class="input-group clockpicker" readonly data-placement="top"
                                        data-align="left" data-donetext="Done" id="Start_time" name="Start_time"
                                        type="text" class="form-control" required="required" style="color:#FF0000;"
                                        placeholder="Ex.08:30 " onchange="caltime()">
                                </td>
                                <td colspan="1">ถึงวันที่ </td>
                                <td colspan="1"><input id="End_date" name="End_date" onchange="caltime()" type="date"
                                        class="form-control" required="required"></td>
                                <td colspan="1"> ถึงเวลา </td>
                                <td colspan="1" width="20%"> <input class="input-group clockpicker" readonly
                                        data-placement="left" data-align="top" data-donetext="Done" id="End_time"
                                        name="End_time" type="text" class="form-control" onchange="caltime()"
                                        required="required" style="color:#FF0000;" placeholder="Ex.16:30 "> </td>
                            </tr>
                            <tr>
                                <td colspan="1">รวมทั้งสิ้น</td>
                                <td colspan="1"><input class="form-control" type="text" name="Total_Day" id="Total_Day"
                                        required="true" readonly="true">
                                    <font>วัน</font>
                                </td>
                                <td colspan="1"><input class="form-control" type="text" name="Total_Hour"
                                        id="Total_Hour" required="true" readonly="true">ชั่วโมง</td>
                                <td colspan="5" rowspan="" headers=""></td>
                                <!--  <td colspan="1"  width="15%">ในระหว่างลาติดต่อขัาพเจ้าได้ที่</td>
        <td colspan="4"><input class="form-control" type="text" name="contract" placeholder="ติดต่อได้ที่"></td>
      </tr> -->
                            <tr>
                                <td>ข้าพเจ้าได้ลา </td>
                                <td>
                                    <input class="form-check-input" type="checkbox" name="check_leave" readonly="true"
                                        value="ป่วย" disabled id="sick1_1">ป่วย
                                </td>

                                <td>
                                    <input class="form-check-input" type="checkbox" value="ขอลากิจส่วนตัว" disabled
                                        name="check_leave" id="sick1_2">ขอลากิจส่วนตัว
                                </td>

                                <td colspan="">
                                    <input class="form-check-input" type="checkbox" value="คลอดบุตร" disabled
                                        name="check_leave" id="sick1_3">คลอดบุตร
                                </td>
                                <td colspan="4" rowspan="" headers=""></td>
                            </tr>

                            <?php
              $date = date("Y-m-d");
              $text = "'" . $date . "'";
              // echo 'string'.$text;

               $selcet_oldday = "SELECT tth.Financial_year,sick.sick_type,sick.sick_detail,sick.sick_startdate,sick.sick_startime,sick.sick_enddate,sick_endtime,sick.sick_totalday,sick.sick_totalhour 
FROM tbl_holiday_detail sick INNER JOIN tbl_total_limit_holiday tth ON tth.Emp_id = sick.Emp_id WHERE sick.Emp_id='" . $_SESSION["Emp_id"] . "' AND sick.status_leave = 3 AND tth.status_limit =1 AND sick.type_id=2 And sick.sick_startdate < $text AND sick.Financial_year ='" . $featchuserfrom["Financial_year"] . "' ORDER BY sick.sick_startdate DESC ";
              $query_sicktotal = mysqli_query($objconnect, $selcet_oldday);
              $num_sick = mysqli_num_rows($query_sicktotal);
              $featch_sick = mysqli_fetch_array($query_sicktotal);
              $featch_sick["sick_startdate"];
              $featch_sick["Financial_year"];

              if ($num_sick == 0) {
                $x = "ยังไม่มีวันลา";
                $x1 = "ยังไม่มีวันลา";
                $x2 = '0' . 'วัน';
              } else {
                $x = $featch_sick["sick_startdate"];
                $x1 = $featch_sick["sick_enddate"];
                $x2 = $featch_sick["sick_totalday"] + $featch_sick["sick_totalhour"];
              }
              ?>
                            <tr>

                                <td>ลาครั้งสุดท้ายตั้งแต่วันที่</td>

                                <td><input type="text" class="form-control" name="Startdate_old" value="<?= $x ?>"
                                        readonly> </td>
                                <td style="padding-left: 3%;">ถึงวันที่</td>
                                <td><input type="text" class="form-control" name="End_old" value="<?= $x1 ?>" readonly>
                                </td>
                                <td style="padding-left: 2%;">มีกำหนด</td>
                                <td headers=""><input type="text" class="form-control" name="End_date_Old"
                                        value="<?= $x2 ?>" readonly></td>

                                <td>วัน</td>
                                <td></td>
                            </tr>

                            <!--  <tr>
        <td colspan="4"></td>
        <td  colspan="4"  align="center" ><label class="form-check-label" for="materialInline1" > ขอแสดงความนับถือ </label>  
        </td>
        </tr>
  <tr>
        <td colspan="4"></td>
        <td  colspan="4"  ><label class="form-check-label" for="materialInline1" > ลงขื่อ </label>  
        </td>
        </tr>
   <tr>
        <td colspan="4"></td>
        <td  colspan="4" align="center"  ><label class="form-check-label" for="materialInline1" > (........................................................................................) </label>  
        </td>
        </tr>
      <tr>
        <td colspan="4"></td>
        <td  colspan="1" width="5" ><label class="form-check-label" for="materialInline1" > ตำแหน่ง </label>   </td>
        <td colspan="2"  style="width:15%;text-align: center;">
         <label  class="form-check-label"  for="materialInline1" style="BORDER-BOTTOM: #0C0C0C 1px  dotted; text-align: center;margin: auto;"> <?= "ยุทธ์ศาสตร์สารสนเทศ" ?> </label></td>
        <td colspan="1"  ></td>
        </tr> -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><input type="submit" value="submit" name="btnsub" id="btnsub"
                                        class="btn btn-primary"></td>
                                <td><input type="reset" name="btnsub" class="btn btn-warning"></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>

                    </table>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="css_and_js/dist/jquery-clockpicker.min.js"></script>
<script type="text/javascript">
$('.clockpicker').clockpicker().find('input').change(function() {

});
</script>
<script src="css_and_js/javascript/jquery-3.4.1.min.js"></script>
<script src="css_and_js/javascript/popper.min.js"></script>
<script src="css_and_js/javascript/bootstrap.min.js"></script>

<script>
function caltime() {
    var ST = document.getElementById('Start_time').value;
    var ET = document.getElementById('End_time').value;
    var SD = document.getElementById('Start_date').value;
    var ED = document.getElementById('End_date').value;
    var x = SD + ' ' + ST;
    var z = ED + ' ' + ET;
    var SD_new = new Date(x);
    var ED_new = new Date(z);
    console.log("datestat+timestart" + SD_new);
    var diffDays = parseInt((new Date(document.getElementById('End_date').value) - new Date(document.getElementById(
        'Start_date').value)) / (1000 * 60 * 60 * 24));

    var diffTimes = parseInt((ED_new - SD_new) / (1000 * 60 * 60)); //คำนวณชม
    console.log('fuck =', diffTimes);




    if (ST != '' && ET != '' && SD != '' && ED != '') {
        // console.log('x =',SD_new);
        // console.log('z =',ED_new);
        console.log('diffDays =', diffDays);
        // console.log(diffTimes);
        if (diffDays > 0) {
            var day = diffDays * 24;

            if (diffTimes > day) {
                var diffT = diffTimes - day;
                if (diffT >= 8) { //ถ้ามากกว่า หรือ เท่ากับ 8 ให้หาร เป็นวัน
                    console.log("// มากกว่าเท่ากับ8 เป็น 1วัน");
                    console.log('diffDays =', diffT);
                    diffT = (diffT / 8).toFixed(0); //
                    console.log('วัน =', parseInt(diffT) + parseInt(diffDays));
                    document.getElementById('Total_Day').value = parseInt(diffT) + parseInt(diffDays);
                    // document.getElementById('Total_Hour').value = diffDays;
                    document.getElementById('Total_Hour').value = '0';
                } else {
                    console.log("elsetum");
                    // diffT = diffT*(diffDays+1);

                    console.log('ชมกวย =', diffT);
                    if (difft = 8) {
                        document.getElementById('Total_Day').value = diffDays;
                        if (diffT > 4) {
                            alert("ตรวจสอบข้อมูลอีกครั้ง");
                            document.getElementById("End_time").focus();

                            return false;
                        }
                        document.getElementById('Total_Hour').value = diffT;

                    }

                }
            } else {
                var diffT = diffTimes;
                console.log('cat =', diffT);
                if (diffT >= 8) {
                    var day1 = (diffDays - 1) * 24;
                    console.log('fax =', day1);
                    // diffT = (diffT/8).toFixed(0);
                    var yy = parseInt(diffT) - parseInt(day1);
                    console.log('yy =', yy);
                    console.log('dog =', parseInt(diffDays));
                    if (yy == 8) {
                        console.log('88888888888888');
                        yy = (parseInt(yy) / 8).toFixed(0);
                        document.getElementById('Total_Day').value = parseInt(yy) * parseInt(diffDays);
                        document.getElementById('Total_Hour').value = '0';
                    } else {
                        console.log('44444444444444');
                        document.getElementById('Total_Day').value = '0';
                        document.getElementById('Total_Hour').value = parseInt(yy) * parseInt(diffDays);
                        if (day1 == 0) {
                            console.log('8-8-8-8-8-8-');
                        } else {
                            console.log('4-4-4-4-4-4-');
                        }
                    }

                } else {
                    document.getElementById('Total_Day').value = '0';
                    document.getElementById('Total_Hour').value = diffT;

                }
            }




        } else {

            console.log('ELSE');
            if (diffDays < 0 || diffTimes < 0 || document.getElementById("Start_time").value == document.getElementById(
                    "End_time").value) {
                alert("โปรดตรวจสอบวันอีกครั้ง");
                document.getElementById("End_date").focus();
                return false;
            }

            if (diffTimes >= 8) {
                diffTimes = (diffTimes / 8).toFixed(0);
                console.log('วัน =', diffTimes);
                document.getElementById('Total_Day').value = diffTimes;
                document.getElementById('Total_Hour').value = '0';
            } else {
                console.log('ssssssssssss');
                diffTimes = diffTimes;
                // alert("กรณุระบุเวลาใหม่หรือวันที่จบใหม่"); 
                // document.getElementById("End_time").focus();
                // return false;
                if (diffTimes < 0) {
                    document.getElementById('Total_Day').value = '';
                    document.getElementById('Total_Hour').value = '';
                } else {
                    console.log("else");
                    document.getElementById('Total_Day').value = '0';
                    document.getElementById('Total_Hour').value = diffTimes
                }
            }




        }

        // var totalday = document.getElementById("totalday").value;
        var checkday = document.getElementById("Total_Day").value;
        var checkhour = document.getElementById("Total_Hour").value;
        var cal = parseFloat(checkday) + parseFloat(checkhour);
        console.log('checkday', checkday);
        if (checkday && checkhour) {
            if (checkday == 0) {
                if (checkhour == 4) {
                    console.log('iftummmmmmmmmmmmmmmm');
                    checkhour = 0.5;
                    cal = parseFloat(checkday) + parseFloat(checkhour);

                }
            } else { //checkday!=0

                if (checkhour == 4) {
                    checkhour = 0.5;
                    console.log('elsetttttttttttt', checkhour);
                    cal = parseFloat(checkday) + parseFloat(checkhour);

                }
            }
            console.log('final', cal);
            if (checkday == 0 && cal > 4) {
                alert("ตรวจสอบชั่วโมงการลาด้วยครับ");
                document.getElementById("End_time").focus();
                return false;
            }
            //  document.getElementById("hdncount").value=cal;          
            // if (totalday<cal) {
            //    alert("วันไม่พอเว้ย");

            //     document.getElementById("btnsub").disabled = true;
            //    return false;

            //   }else{
            //     document.getElementById("btnsub").disabled = false;
            //   }  

            // console.log(checkday);



        } //check day


    } // if(ST != '' && ET != '' && SD != '' && ED != '' )

    $.ajax({
        type: "POST",
        url: "cal_holidayandleave.php",
        data: {
            Start_date: SD,
            End_date: ED
        },
        dataType: "json",
        success: function(response) {


            document.getElementById("Total_Day").value = (document.getElementById("Total_Day").value -
                response.result)

        }
    });
}
</script>


<script type="text/javascript">
$x = 1;

function checktype() {
    var mytypecheck1 = document.getElementById("mytypecheck1").checked;
    var mytypecheck2 = document.getElementById("mytypecheck2").checked;
    var mytypecheck3 = document.getElementById("mytypecheck3").checked;
    var mytypecheck4 = document.getElementById("mytypecheck4").checked;
    if (mytypecheck1 == true) {
        console.log("if");
        document.getElementById("mytypecheck2").disabled = true;
        document.getElementById("mytypecheck3").disabled = true;
        document.getElementById("mytypecheck4").disabled = true;


    } else if (mytypecheck2 == true) {
        console.log("if");
        document.getElementById("mytypecheck1").disabled = true;
        document.getElementById("mytypecheck3").disabled = true;
        document.getElementById("mytypecheck4").disabled = true;

    } else if (mytypecheck3 == true) {
        console.log("if");
        document.getElementById("mytypecheck2").disabled = true;
        document.getElementById("mytypecheck1").disabled = true;
        document.getElementById("mytypecheck4").disabled = true;

    } else if (mytypecheck4 == true) {
        console.log("if");
        document.getElementById("mytypecheck2").disabled = true;
        document.getElementById("mytypecheck3").disabled = true;
        document.getElementById("mytypecheck1").disabled = true;

    } else {

        document.getElementById("mytypecheck1").disabled = false;
        document.getElementById("mytypecheck2").disabled = false;
        document.getElementById("mytypecheck3").disabled = false;
        document.getElementById("mytypecheck4").disabled = false;
    }
}
</script>
<script>
function onsick() {
    var sick = document.getElementById("sick").checked;
    var sick2 = document.getElementById("sick_2").checked;
    var sick3 = document.getElementById("sick_3").checked;
    var to_leave1 = document.getElementById("to_leave1");
    var to_leave2 = document.getElementById("to_leave2");
    if (sick == true) {
        console.log("if1");
        to_leave1.disabled = false;
        document.getElementById('sick1_1').checked = true;
        document.getElementById("sick_2").disabled = true;
        document.getElementById("sick_3").disabled = true;
        document.getElementById('text_type_sick').value = document.getElementById('sick1_1').value;

    } else if (sick2 == true) {

        to_leave2.disabled = false;
        document.getElementById('sick1_2').checked = true;
        document.getElementById("sick").disabled = true;
        document.getElementById("sick_3").disabled = true;
        document.getElementById('text_type_sick').value = document.getElementById('sick1_2').value;
    } else if (sick3 == true) {

        document.getElementById('sick1_3').checked = true;
        document.getElementById("sick").disabled = true;
        document.getElementById("sick_2").disabled = true;
        document.getElementById('text_type_sick').value = document.getElementById('sick1_3').value;
    } else {

        document.getElementById("sick").disabled = false;
        document.getElementById("sick_2").disabled = false;
        document.getElementById("sick1_1").checked = false;
        to_leave1.value = '';
        to_leave2.value = '';
        to_leave1.disabled = true;
        to_leave2.disabled = true;
        document.getElementById("sick_3").disabled = false;
        document.getElementById("sick1_2").checked = false;
        document.getElementById("sick1_3").checked = false;
        document.getElementById('text_type_sick').value = "";
    }


}
const Financial_year = "<?php echo $featchuserfrom["Financial_year"]; ?>";
document.getElementById('Financial_year').value = Financial_year;
</script>