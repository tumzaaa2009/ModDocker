<?php
session_start();
	include 'connect/connect.php';
 
  require 'head/headder.php';



//////////////////////////////////////////////////////////////////////ดึงชื่อผู้ลาพักร้น
$selectuse="SELECT total_limit.sum_thisyear_holiday,emp.Emp_id as emp_id,hddetail.hoilday_detail_id AS hd_id,emp.Emp_name as empname ,emp.Emp_lastname 
as emplastname,pgroup.name as emppositionname,af.affiliate_name as affname ,pgroup.name As NAME 
,hddetail.holiday_startdate hdstdate , hddetail.holiday_startime hdsttime ,hddetail.holiday_enddate hdendate
,hddetail.holiday_endtime hdentime
,hddetail.hoilday_totalday hdtotalday
,hddetail.holiday_totalhour hdtotalhr
,hddetail.holiday_contract hdcon
,hddetail.holiday_contractobject hdconobj
,hddetail.group_id g_id
,hddetail.type_id type_id
,hddetail.hoilday_detail_id id
FROM tbl_employment AS emp  
INNER JOIN affiliation AS af ON emp.affiliate_id = af.affiliate_id  
INNER JOIN tbl_group_type gtype ON emp.group_id=gtype.group_id
INNER JOIN tbl_position_group pgroup ON emp.Position_id=pgroup.Position_id
INNER JOIN tbl_total_limit_holiday total_limit on total_limit.Emp_id = emp.Emp_id
INNER JOIN tbl_holiday_detail hddetail ON hddetail.Emp_id = emp.Emp_id


where emp.Emp_id='". $_GET["emp"]."' AND hddetail.hoilday_detail_id='".$_GET["id"]."' AND status_limit ='1' ";
$queryuserfrom = mysqli_query($objconnect,$selectuse);
$featchuserfrom = mysqli_fetch_array($queryuserfrom);

$startvar = "ประกาศตัวแปร";
$featchuserfrom["empname"];
$featchuserfrom["emplastname"];
$featchuserfrom["emppositionname"];
$featchuserfrom["affname"];
$featchuserfrom["NAME"];
$g_id=$featchuserfrom["g_id"];
$endvar ="จบ";
//////////////////////////////////////////////////////////////////////ENDดึงชื่อผู้ลาพักร้น

///////////////////////////////เช็คเอามาบวกลบแล้วนำเข้า
 $selectchecklimitday="SELECT * FROM tbl_total_limit_holiday WHERE 
Emp_id = '". $_SESSION["Emp_id"]."' AND status_limit = 1";
$querychcklimitday = mysqli_query($objconnect,$selectchecklimitday);
$numchecklimitday = mysqli_num_rows($querychcklimitday);
$featchchecklimitday=mysqli_fetch_array($querychcklimitday);
/////////////////////////////////จบ นำเข้า////////////////



// ///////////////////////////////isset/////////////////////////////
if (isset($_POST["btnsub"])) {

if ($_POST["Total_Day"] !='' || $_POST["Total_Hour"]!='') {

$featchttday_old =$_POST["featchttday"];
$featchtthr_old =$_POST["featchtthr"];
$sum_old = $featchttday_old+$featchtthr_old;
  

// end if ส่วนต่าง ////
 $selectcount = "SELECT sum_thisyear_holiday,detail.hoilday_totalday today,detail.holiday_totalhour tohour,limithd.count_year_holiday countyear,limithd.sum_thisyear_holiday sumyear,emp.group_id gid
FROM tbl_employment emp 
INNER JOIN tbl_holiday_detail detail ON emp.Emp_id=detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithd ON limithd.Emp_id = emp.Emp_id
 where  detail.hoilday_detail_id ='".$_GET["id"]."' and emp.Emp_id ='".$_GET["emp"]."'  and limithd.status_limit='1' " ; 
$querycount = mysqli_query($objconnect,$selectcount);
$numcount = mysqli_num_rows($querycount);
$featchcount = mysqli_fetch_array($querycount);


//บวกวันเพื่ออัพเดทกลับ
$day =$featchcount["today"] ;
$hour=$featchcount["tohour"];
$sum = $day+$hour;  

//update tbl_limit
$countold = $featchcount["countold"];
$countyear =  $featchcount["countyear"];
$sumtotalcount =$countold+$countyear;
// echo 'string'.$sumyearhoilday;
$g_id =$featchcount["gid"];
// ///////////////////////////////////////////////////////////////////////////////////
$text ="";

$delete = "DELETE FROM tbl_holiday_detail WHERE hoilday_detail_id='".$_GET["id"]."' and Emp_id='".$_GET["emp"]."' ";
mysqli_query($objconnect,$delete);

//////insert เข้าใหม่
if (isset($_POST["Total_Hour"])) {
    if ($_POST["Total_Hour"]==4) {
 $totalhour= $_POST["Total_Hour"]=0.5;   
  
  }else{
  $totalhour=$_POST["Total_Hour"];

  }
}


$holiday_detail_insert= "INSERT INTO tbl_holiday_detail
 (
 hoilday_detail_id
 ,type_id
 ,Emp_id
 ,group_id
 ,register_date
 ,hoilday_detail_name
 ,holiday_positon
 ,holiday_section
 ,holiday_count_year_old
 ,holiday_count_total_year
 ,holiday_startdate
 ,holiday_startime
 ,holiday_enddate
 ,holiday_endtime
 ,hoilday_totalday
 ,holiday_totalhour
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
 ,update_editby
 ,dateedit_by
 ,Financial_year)
     VALUES 
    ('".$_GET["id"]."'
    ,'".$_POST["type_id"]."' 
    ,'".$_GET["emp"]."' 
    ,'".$_SESSION["groupid"]."'
    ,NOW()
    ,'".$_POST["uname"]."'  
    ,'".$_POST["position"]."' 
    ,'".$_POST["section"]."'  
    ,'".$_POST['totalholiday']."'  
    ,'".$_POST['totalday']."'  
    ,'".$_POST["Start_date"]."'  
    ,'".$_POST["Start_time"]."' 
    ,'".$_POST["End_date"]."'  
    ,'".$_POST["End_time"]."'  
    ,'".$_POST["Total_Day"]."' 
    ,'".$totalhour."'
    ,'".$_POST["contract"]."'
    ,'".$_POST["objcontract"]."'
    ,'"."-"."'
    ,'"."-"."'
    ,'"."-"."'
    ,'"."-"."'
    ,'".'0000-00-00'."'
    ,'".'00:00:00'."'
    ,'".'0000-00-00'."'
    ,'".'00:00:00'."' 
    ,'"."-"."'
    ,'"."-"."'
    ,1
    ,'".$_POST["uname"]."'
    ,NOW()
    ,'".$_POST['Financial_year']."'
  ) " ;
$query_horidayinsert= mysqli_query($objconnect,$holiday_detail_insert);

////////////////////////update วันเข้าออก
$day_count=$featchcount['today']+$featchcount['tohour'];
$day_diff = number_format($_POST['Total_Day']+$totalhour,2);
if($_POST['Total_Day']+$_POST['Total_Hour']>$day_count){
 $holiday_plus = $featchcount['sum_thisyear_holiday']-($day_diff-$day_count);
 $diff =$holiday_plus;
}else if ($_POST['Total_Day']+$_POST['Total_Hour']<$day_count) {
 $holiday_plus = $featchcount['sum_thisyear_holiday']+($day_count-$day_diff);
       $diff =$holiday_plus;
}
else{
    $diff = $featchcount['sum_thisyear_holiday'];
}

 $text = "set sum_thisyear_holiday ='".$diff."' " ;  
  $updateholiday = "UPDATE tbl_total_limit_holiday
  $text
  WHERE Emp_id = '". $_GET['emp']."' AND status_limit = 1" ;
  mysqli_query($objconnect,$updateholiday);
/////////////////////////////////////////

} //isset tottalday,hour ?>
<script>
alert("บันทึกข้อมูลเรียบแล้ว");
var x = "<?=$_GET["id"]?>";
var y = "<?=$_GET["emp"]?>";
var z = "<?=$featchuserfrom["type_id"]?>"
location.href = 'view_decription.php?id=' + x + '&&emp=' + y + '&&type_id=' + z;
</script>";
<?php  } //isset btn
?>
<!-- clockpicker -->
<link rel="stylesheet" href="css_and_js/dist/bootstrap-clockpicker.css">

<body>
    <div class="container-fulid">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <form method="post">
                    <div class="card">
                        <div class="card-header">
                            <h1 style="color: black"> แบบฟอร์มลาพักร้อน (แก้ไข) </h1>
                        </div>
                        <div class="card-body">
                            <table style="margin: auto;" border="0">
                                <tbody>
                                    <tr>
                                        <td align="center" style="color: red">
                                            วันลาคงเหลือ
                                            <input type="text" align="center" name="sum_thisyear_holiday"
                                                id="sum_thisyear_holiday"
                                                value="<?php echo $featchuserfrom["sum_thisyear_holiday"];?>"
                                                placeholder="" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" align="right" class="input_show"><strong>เขียน </strong><strong
                                                style="BORDER-BOTTOM: #0C0C0C 1px  dotted;text-align: center;">
                                                ที่สำนักงานเขตสุขภาพที่ 4</strong> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" align="right" class="input_show"><strong>วันที่ </strong><strong
                                                style="BORDER-BOTTOM: #0C0C0C 1px  dotted; text-align: center;"><?=date("d/m/Y ");?></strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" align="left">
                                            <p class="text-left">เรื่อง ขอลาพักผ่อน</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" align="left">
                                            <p class="text-left">เรียน ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="1" align="right"> ข้าพเจ้า </td>
                                        <td colspan="3">
                                            <!-- เก็บค่า total_feacthเก่า -->

                                            <!-- day   -->
                                            <input type="hidden" name="featchttday"
                                                value="<?=$featchuserfrom["hdtotalday"]?>" id="featchttday">
                                            <!-- hr  -->
                                            <input type="hidden" name="featchtthr"
                                                value="<?=$featchuserfrom["hdtotalhr"]?>" id="featchtthr">
                                            <!-- พักผ่อน   -->
                                            <input type="hidden" id="featchcklimit" name="featchcklimit"
                                                value="<?=$featchchecklimitday["count_oldyear_hoilday"]?>">

                                            <!-- เก็บค่าเพิม่วัน ถ้า ส่วนต่างมากกว่า 10 ให้ส่วนต่างมาลบ 10 แล้วมาบวกเข้า ปีเก่า -->
                                            <!-- รวมทั้งหมด  -->
                                            <input type="hidden" name="counttotalday" id="counttotalday"
                                                value="<?=$featchchecklimitday["sum_thisyear_holiday"]?>">

                                            <!-- สว่นต่างที่เป็นค่าvalue -->
                                            <input type="hidden" name="value_total" value="" id="value_total"
                                                placeholder="">

                                            <input type="hidden" id="Financial_year" name="Financial_year"
                                                value="<?=$featchchecklimitday["Financial_year"]?>">


                                            <!--จบ  -->

                                            <!-- เก็บค่า typeของวันลา บวกลบกับวันลาปีเก่า-->
                                            <input type="hidden" name="id" value="<?=$featchuserfrom["id"]?>">
                                            <input type="hidden" name="hdncount" id="hdncount">

                                            <input type="hidden" name="type_id" value="1">
                                            <!-- END -->
                                            <input type="text" name="uname" class="form-control input-sm"
                                                value="<?= $featchuserfrom["empname"],"  ",$featchuserfrom["emplastname"];;?>"
                                                readonly>
                                            <!--   <input class="form-control input-sm"  type="text" name="uname" placeholder="ชื่อ"  value="<?= $featchuserfrom["empname"],"  ",$featchuserfrom["emplastname"];;?>" readonly> -->
                                        </td>
                                        <td colspan="1"> ตำแหน่ง</td>
                                        <td colspan="3"> <input class="form-control" type="text"
                                                style="text-align: center;" readonly name="position"
                                                value="<?=$featchuserfrom["NAME"]?>" placeholder="ตำแหน่ง"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="1">สังกัด</td>
                                        <td colspan="7" align="left"><input class="form-control" type="text"
                                                style="text-align: center;" readonly
                                                value="<?=$featchuserfrom["affname"]?>" name="section"
                                                placeholder="สังกัด"></td>
                                    </tr>
                                    <tr>
                                        <?php 
                              if($numchecklimitday!=1){
                                echo 'checkตัดวันลาใหม่';
                              }else{
                                    ?>
                                        <td colspan="1">มีวันลาพักผ่อนสะสม</td>
                                        <td colspan="1"><input class="form-control" type="text" readonly="true"
                                                id="totalholiday" name="totalholiday"
                                                value="<?=$featchchecklimitday["fix_count_oldyear_holiday"]?>"
                                                placeholder="วันลาสะสมทั้งหมด"></td>
                                        <td colspan="1"> วันทำการ</td>
                                        <td colspan="3" align="center"> <label class="form-check-label"
                                                for="materialInline1"> มีสิทธิลาพักร้อนประจำปีนี้อีก 10 วันทำการ
                                                รวมเป็น</label></td>
                                        <td colspan="1"><input class="form-control" type="text" id="totalday"
                                                name="totalday" readonly="true"
                                                value="<?=$featchchecklimitday["count_year_holiday"]+$featchchecklimitday["fix_count_oldyear_holiday"]?>">


                                            <?php }?>
                                        <td colspan="1"><label>วัน</label></td>
                                    </tr>
                                    <tr>
                                        <td colspan="1">ขอลาพักผ่อนตั้งแต่วันที่ </td>
                                        <td colspan="1"> <input id="Start_date" name="Start_date" type="date"
                                                class="form-control" required="required" onchange="return caltime();"
                                                value="<?=$featchuserfrom["hdstdate"]?>"></td>
                                        <td colspan="1" width="10%"> ตั้งแต่เวลา </td>
                                        <td colspan="1">
                                            <input class="input-group clockpicker" data-placement="top"
                                                data-align="left" data-donetext="Done" id="Start_time" name="Start_time"
                                                type="text" value="<?=$featchuserfrom["hdsttime"]?>" required="required"
                                                style="color:#FF0000;" placeholder="Ex.08:30 "
                                                onchange="return caltime();">
                                        </td>
                                        <td colspan="1">ถึงวันที่ </td>
                                        <td colspan="1"><input id="End_date" name="End_date"
                                                onchange="return caltime();  " type="date" class="form-control"
                                                required="required" value="<?=$featchuserfrom["hdendate"]?>"></td>
                                        <td colspan="1"> ถึงเวลา </td>
                                        <td colspan="1" width="10%"> <input class="input-group clockpicker"
                                                data-placement="left" data-align="top" data-donetext="Done"
                                                id="End_time" name="End_time" type="text" onchange="return caltime();"
                                                required="required" style="color:#FF0000;" placeholder="Ex.16:30 "
                                                value="<?=$featchuserfrom["hdentime"]?>"> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="1">รวมทั้งสิ้น</td>
                                        <td colspan="1"><input class="form-control" type="text" name="Total_Day"
                                                id="Total_Day" required="true" readonly="true"
                                                value="<?=$featchuserfrom["hdtotalday"]?>">
                                            <font>วัน</font>
                                        </td>
                                        <td colspan="1"><input class="form-control" type="text" name="Total_Hour"
                                                id="Total_Hour" required="true" readonly="true"
                                                value="<?=$featchuserfrom["hdtotalhr"]?>">ชั่วโมง</td>
                                        <td colspan="1" width="15%">ในระหว่างลาติดต่อขัาพเจ้าได้ที่</td>
                                        <td colspan="4"><input class="form-control" type="text" name="contract"
                                                placeholder="ติดต่อได้ที่" required="true"
                                                value="<?=$featchuserfrom["hdcon"]?>"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="1">ในระหว่างลานี้ ได้มอบหมายให้ </td>
                                        <td colspan="7"><input class="form-control" type="text" name="objcontract"
                                                placeholder="ได้มอบหมายให้" required="true"
                                                value="<?=$featchuserfrom["hdconobj"]?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-muted">
                            <div class="row">
                                <div style="margin-left: 47%; ">
                                    <input type="submit" value="submit" name="btnsub" id="btnsub"
                                        class="btn btn-primary">
                                </div>
                                <div style="margin-left: 3%;">
                                    <input type="reset" name="btnsub" class="btn btn-warning">
                                </div>
                            </div>
                        </div>
                    </div>
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
    // var diffTimes = parseInt(ET_new - ST_new);
    //   console.log(diffTimes);




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

                    console.log('ชม =', diffT);
                    if (difft = 8) {
                        document.getElementById('Total_Day').value = diffDays;
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

            console.log(diffDays);
            // if (diffDays<0 || diffTimes<0 || document.getElementById("Start_time").value == document.getElementById("End_time").value) {
            //   alert("โปรดตรวจสอบวันอีกครั้ง");
            //   document.getElementById("btnsub").disabled="true";
            //   return false;

            // }
            if (diffDays < 0) {
                alert("โปรดตรวจสอบวันอีกครั้ง");
                document.getElementById("btnsub").disabled = true;

            } else {

                document.getElementById("btnsub").disabled = false;

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

        var totalday = document.getElementById("totalday").value;
        var checkday = document.getElementById("Total_Day").value;
        var checkhour = document.getElementById("Total_Hour").value;
        var cal = parseFloat(checkday) + parseFloat(checkhour);
        console.log('checkday', checkday);
        if (checkday && checkhour && totalday) {
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
            document.getElementById("hdncount").value = cal;

            ///////////////////////////////////////////คำนวณวันกลับ
            var featchttday = document.getElementById("featchttday").value;
            var sum_d_hr = parseFloat(checkday) + parseFloat(checkhour);
            var diff_count = parseFloat(featchttday) - parseFloat(sum_d_hr); //ค่ามากบวกเพิ่ม //ค่าลบ หัก total;

            var test = document.getElementById("counttotalday").value;
            // console.log("ggggggggggggggggggggggggggggggggggggggggggggggggggggg",test);
            var type_id = "<?=$_GET["id"]  ?>"
            var group_id = "<?=$g_id?>";
            var totalholiday = document.getElementById("totalholiday").value;
            var featchcklimit = document.getElementById("featchcklimit").value
            // var totalholiday_old = document.getElementById("totalholiday_old").value;

            if (group_id == 1) {
                if (totalholiday <= 10) { //วันหยุดเพิ่ม
                    featchcklimit //ลิมิตเก่าของปีก่อน
                    diff_count //ค่าผลต่าง
                    if (featchcklimit <= 10) {


                        document.getElementById("value_total").value =
                            parseFloat(document.getElementById("totalday").value) - parseFloat(document.getElementById(
                                "totalholiday").value);
                        // console.log("ssssssssssssssssssssssssssssssssssss",document.getElementById("totalholiday").value);
                        if (document.getElementById("totalholiday").value <= 0) {
                            document.getElementById("totalholiday").value = 0
                            document.getElementById("value_total").value = document.getElementById("totalday").value
                            document.getElementById("btnsub").disabled = false;
                            if (document.getElementById("totalday").value < 0) {
                                alert("เช็ควันลาใหม่");
                                document.getElementById("btnsub").disabled = true;
                                return false;
                            }
                        } else if (totalday <= 10) {

                            document.getElementById("totalholiday").value = 0
                            document.getElementById("value_total").value = document.getElementById("totalday").value
                            if (document.getElementById("value_total").value > 10) {

                                var x = 10;
                                var discount = parseFloat(document.getElementById("value_total").value) - x

                                document.getElementById("value_total").value = x;




                            }
                        }


                    }
                }


            } else {
                if (totalholiday <= 5) { //วันหยุดเพิ่ม
                    featchcklimit //ลิมิตเก่าของปีก่อน
                    diff_count //ค่าผลต่าง
                    if (featchcklimit <= 5) {
                        document.getElementById("value_total").value =
                            parseFloat(document.getElementById("totalday").value) - parseFloat(document.getElementById(
                                "totalholiday").value);
                        // console.log("ssssssssssssssssssssssssssssssssssss",document.getElementById("totalholiday").value);
                        if (document.getElementById("totalholiday").value <= 0) {
                            document.getElementById("totalholiday").value = 0
                            document.getElementById("value_total").value = document.getElementById("totalday").value
                            document.getElementById("btnsub").disabled = false;
                            if (document.getElementById("totalday").value < 0) {
                                alert("เช็ควันลาใหม่");
                                document.getElementById("btnsub").disabled = true;
                                return false;
                            }
                        } else if (totalday <= 10) {

                            document.getElementById("totalholiday").value = 0
                            document.getElementById("value_total").value = document.getElementById("totalday").value
                            if (document.getElementById("value_total").value > 10) {

                                var x = 10;
                                var discount = parseFloat(document.getElementById("value_total").value) - x
                                document.getElementById("totalholiday").value = discount;
                                document.getElementById("value_total").value = x;




                            }
                        }


                    }
                }

            }


        } //check day


    } // if(ST != '' && ET != '' && SD != '' && ED != '' )
    var limit_count = parseFloat(document.getElementById("counttotalday").value) + diff_count;

    if (limit_count < 0) {
        alert("วันลาไม่เพียงพอ");
        document.getElementById("btnsub").disabled = true;
        return false;
    } else {
        console.log("limit_count" + limit_count);
        document.getElementById("btnsub").disabled = false;
    }



}
</script>