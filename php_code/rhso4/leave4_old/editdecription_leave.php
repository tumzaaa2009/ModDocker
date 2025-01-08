<?php
session_start();
	include 'connect/connect.php';
 
  require 'head/headder.php';




//////////////////////////////////////////////////////////////////////ดึงชื่อผู้ลาพักร้น
$selectuse="SELECT emp.Emp_id as id,emp.Emp_name as empname ,emp.Emp_lastname as emplastname,pgroup.name as emppositionname,af.affiliate_name as affname 
,gtype.group_id as groupid 
,gtype.name as typename
,hddetail.sick_type as stype
,hddetail.sick_detail as sdetail
,hddetail.sick_startdate as stdate
,hddetail.sick_startime as sttime
,hddetail.sick_enddate as endate
,hddetail.sick_endtime as entime
,hddetail.sick_totalday as ttdate
,hddetail.sick_totalhour as tthr
FROM tbl_employment AS emp 
INNER JOIN affiliation AS af ON emp.affiliate_id = af.affiliate_id  
INNER JOIN tbl_group_type gtype ON emp.group_id=gtype.group_id
INNER JOIN tbl_position_group pgroup ON emp.Position_id=pgroup.Position_id
INNER JOIN tbl_total_limit_holiday total_limit on total_limit.Emp_id = emp.Emp_id
INNER join tbl_holiday_detail hddetail on hddetail.Emp_id = emp.Emp_id
where emp.Emp_id='". $_SESSION["Emp_id"]."'
AND  hddetail.hoilday_detail_id='".$_GET["id"]."'
AND  hddetail.type_id='".$_GET["type_id"]."'
AND  hddetail.status_leave=1 ";
$queryuserfrom = mysqli_query($objconnect,$selectuse);
$featchuserfrom = mysqli_fetch_array($queryuserfrom);

$startvar = "ประกาศตัวแปร";
$featchuserfrom["empname"];
$featchuserfrom["emplastname"];
$featchuserfrom["emppositionname"];
$featchuserfrom["affname"];
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
  // echo '<pre>';
  // echo  print_r($_POST);
  // echo '</pre>';
 
$delete = "DELETE FROM tbl_holiday_detail WHERE  hoilday_detail_id='".$_GET["id"]."' ";
mysqli_query($objconnect,$delete);


if ($_POST["Total_Day"] !='' || $_POST["Total_Hour"]!='') {
  $totalday=$_POST["Total_Day"] ;
  $totalhour=$_POST["Total_Hour"];
  if ($_POST["Total_Hour"]==4) {
    // echo 'fuck';
    $totalhour=0.5;
  }
  if ($_POST["Total_Day"] == "-") {
     // echo 'iftotal_day';
  $totalday=0;
  
  }
  if($_POST["Total_Hour"] == "-"){
    // echo 'iftotal_hour';
  $totalhour=0;
  }

   $totalsumsick=$totalday+$totalhour+$featchchecklimitday["sum_thisyear_leave"];

// echo $totalday;

$holiday_detail_insert= "INSERT INTO tbl_holiday_detail
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
 ,update_editby
 ,dateedit_by
 ,Financial_year)
    VALUES 
    ('".$_GET["id"]."'
    ,'".$_POST["type_id"]."' 
    ,'".$_POST["id"]."' 
    ,'".$_POST["group_id"]."'
    ,NOW()
    ,'".$_POST["uname"]."'
    ,'"."-"."'
    ,'"."-"."'
    ,'".'0000-00-00'."'
    ,'".'00:00:00'."'
    ,'".'0000-00-00'."'
    ,'".'00:00:00'."' 
    ,'"."-"."'
    ,'"."-"."'
    ,'"."-"."'
    ,'"."-"."'
    ,'"."-"."'
    ,'"."-"."'
    ,'".$_POST["position"]."' 
    ,'".$_POST["section"]."'
    ,'".$_POST["check_leave"]."'  
    ,'".$_POST["To_leave"]."'  
    ,'".$_POST["Start_date"]."'  
    ,'".$_POST["Start_time"]."' 
    ,'".$_POST["End_date"]."'  
    ,'".$_POST["End_time"]."'  
    ,'".$_POST["Total_Day"]."' 
    ,'".$totalhour."' 
    ,1
    ,'".$_POST["uname"]."'
    ,NOW()
    ,'".$_GET["year"]."'
  ) " ;
$query_horidayinsert= mysqli_query($objconnect,$holiday_detail_insert);

  $updateholiday = "UPDATE tbl_total_limit_holiday
  SET  sum_thisyear_leave='".$totalsumsick."'
  WHERE Emp_id = '". $_SESSION["Emp_id"]."' AND status_limit = 1" ;
  mysqli_query($objconnect,$updateholiday);
} //isset tottalday,hour ?>
<script>
  var x = "<?=$_GET["id"]?>";
  var y = "<?=$_GET["emp"]?>";
  var z = "<?=$_GET["type_id"]?>";
  var year = "<?=$_GET["year"]?>";
if (confirm("ยืนยันการบันทึก")) {
   location.href ='view_decription_leave.php?id='+x+'&&emp='+y+'&&type_id='+z+'&&finyear='+year;
  } else {
  
  }
   
</script>
<?php  } //isset btn
?>
  <!-- clockpicker -->
    <link rel="stylesheet" href="css_and_js/dist/bootstrap-clockpicker.css">
 <!--  -->
<style type="text/css" media="screen">
    .input_show {
          border: 0;
          outline: 0;
          border-bottom: 1px solid black;
          text-align: center;
          margin-top: -5px;
          vertical-align: top;
       }
       .input_show1 {
          border: 0;
          outline: 0;
          border-bottom: 1px solid black;
          padding-left: 20px;
       }
       .input_show2 {
          border: 0;
          outline: 0;
          border-bottom: 0px solid black;
          text-align: center;
       }
</style>
<body>
<div class="container-fulid" >
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
     <div class="container-fluid" style="margin-top: 5%">
      <div class="row">
        <div class="col-12">
 <div class="card">
  <div class="card-header">   <h1 style="color: black" > แบบใบลาป่วย ลาคลอดบุตร ลากิจส่วนตัว (แก้ไข) </h1> </div>
  <div class="card-body">
    <form method="post">


      <table  style="margin: auto;width: 100%;"   >
 <tbody>
      <tr >
  
        <td colspan="12"   style=" padding-left: 75%" ><strong>เขียน </strong><strong style="BORDER-BOTTOM: #0C0C0C 1px  dotted;text-align: center; padding-right: 10%"  > ที่สำนักงานเขตสุขภาพที่ 4</strong> </td>
      </tr>
        <tr >
    
        <td colspan="12" style=" padding-left: 75%" ><strong>วันที่ </strong>
        <strong  style="BORDER-BOTTOM: #0C0C0C 1px  dotted; padding-left: 3%;text-align: center; " ><?=date("d/m/Y ");?></strong> </td>
      </tr>
     
  </tbody>
</table>


<div class="form-group row">
<div class="col-3">
   <p>เรื่อง ขออนุญาติ................</p>
</div> 
</div>
<div class="form-group row">
  <div class="col-3">
  <p> เรียน ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4</p> 
  </div>
</div>

  <div class="form-group row">
    <div class="col-12">
          <div class="form-group row">
  <div class="col-1">
    <p>ข้าพเจ้า </p>
  </div>
  <div class="col-4"> <!-- เก็บค่า typeของวันลา -->
    <input type="hidden" name="id" value="<?=$featchuserfrom["id"]?>" >
    <!--   <input type="hidden" name="hdncount" id="hdncount"> -->
    <!-- type_id ประเภทใบลา 2 ลาป่วย-->
          <input type="hidden" name="type_id" value="2" > 
          <!-- END -->
           <input type="text" name="uname"  class="form-control input-sm" value="<?= $featchuserfrom["empname"],"  ",$featchuserfrom["emplastname"];;?>" readonly >
        <!--   <input class="form-control input-sm"  type="text" name="uname" placeholder="ชื่อ"  value="<?= $featchuserfrom["empname"],"  ",$featchuserfrom["emplastname"];;?>" readonly> -->
  </div>
  <div class="col-1"><p>ตำแหน่ง</p></div>
  <div class="col-4"><input class="form-control" type="text" style="text-align: center;" readonly name="position" value="<?=$featchuserfrom["emppositionname"]?>" placeholder="ตำแหน่ง"></div>
</div>
<div class="form-group row">
  <div class="col-2">
    <p>ประเภทพนักงาน</p>
  </div>
  <div class="col-2" >
    <input type="hidden"  name="group_id" value="<?=$featchuserfrom["groupid"]?>">
    <input type="text" align="left" readonly="true" class="form-control input_show2"  value="<?=$featchuserfrom["typename"]?> ">
  </div>
  <div class="col-1">
    <p>สังกัด</p>
  </div>
  <div class="col-3">
    <input class="form-control" type="text"  style="text-align: center;" readonly value="<?=$featchuserfrom["affname"]?>" name="section" placeholder="สังกัด">
  </div>
</div>
<div class="form-group row">
  <div class="col-1" style="margin-left: 1.5%">
    <p>
    <input class="form-check-input" type="checkbox" name="check_leave" value="ป่วย" id="sick" onclick="onsick()">ป่วย
    </p>
  </div>
  <div class="col-1"style="margin-left: 3%"><p>เนื่องจาก</p> </div>
  <div class="col-8">
    <p><input type="text" class="form-control" disabled="true" id="to_leave1" required name="To_leave"></p> 
  </div>
 </div>
 <div class="form-group row">
  <div class="col-2"style="margin-left: 1.5%" >
      <input class="form-check-input" type="checkbox" value="ขอลากิจส่วนตัว" name="check_leave" id="sick_2" onclick="onsick()">ขอลากิจส่วนตัว
  </div>
  <div class="col-1"style="margin-left: -5.5%;"><p>เนื่องจาก</p></div>
  <div class="col-8"><input type="text" class="form-control"  disabled="true" id="to_leave2" required name="To_leave"> 
  </div>
 </div>
 <div class="form-group row">
   <div class="col-1"style="margin-left: 1.5%" ><input class="form-check-input" type="checkbox" value="คลอตบุตร" name="check_leave" id="sick_3" onclick="onsick()" >คลอดบุตร</div>

 </div>
  
  </div>
</div>
<div class="row">
<div class="col-12" >
    <div class="form-group row">
    <p class="col-1">ตั้งแต่วันที่  </p> 
    <p class="col-2"><input id="Start_date" name="Start_date" type="date" class="form-control"  required="required" onchange="caltime()" value="<?=$featchuserfrom["stdate"]?>" > </p>
    <p class="col-1">ตั้งแต่เวลา </p> 
    <p class="col-2"><input class="input-group clockpicker" data-placement="top" data-align="left" data-donetext="Done"  id="Start_time" name="Start_time" type="text" class="form-control"   required="required" style="color:#FF0000;" placeholder="Ex.08:30 " onchange="caltime()" value="<?=$featchuserfrom["sttime"]?>" ></p>
    <p class="col-1">ถึงวันที่ </p> 
    <p class="col-2"> <input id="End_date" name="End_date" onchange="caltime()"type="date" class="form-control"  required="required" value="<?=$featchuserfrom["endate"]?>" ></p>
    <p class="col-1">ถึงเวลา</p>
    <p class="col-2"> 
     <input class="input-group clockpicker" data-placement="left" data-align="top"  data-donetext="Done"
            id="End_time" name="End_time" type="text" class="form-control" onchange="caltime()"  required="required" style="color:#FF0000;" placeholder="Ex.16:30 "value="<?=$featchuserfrom["entime"]?>">
    </p>
    </div>
    <div class="form-group row">
       <p class="col-1">รวมทั้งสิ้น </p> 
      <p class="col-2"> <input class="form-control" type="text" name="Total_Day" id="Total_Day" required="true" readonly="true" value="<?=$featchuserfrom["ttdate"]?>"></p>
      <p class="col-1">วัน</p>
      <p class="col-2"><input class="form-control" type="text" name="Total_Hour" id="Total_Hour" required="true" readonly="true" value="<?=$featchuserfrom["tthr"]?>"></p>
      <p class="col-1">ชั่วโมง</p>
    </div>
    <div class="form-group row">
       <p class="col-1">ข้าพเจ้าได้ลา</p>
       <p class="col-1" style="margin-left: 1%" ><input class="form-check-input" type="checkbox" name="check_leave" readonly="true" value="ป่วย" disabled id="sick1_1" >ป่วย</p>
       <p class="col-2"><input class="form-check-input" type="checkbox" value="ป่วย" disabled name="check_leave" id="sick1_2">ขอลากิจส่วนตัว</p>
       <p class="col-2"><input class="form-check-input" type="checkbox" value="ป่วย" disabled name="check_leave" id="sick1_3">คลอดบุตร</p>
    </div>

<?php
$date =date("Y-m-d") ;
$text = "'".$date."'";

$selcet_oldday = "SELECT sick_type,sick_detail,sick_startdate,sick_startime,sick_enddate,sick_endtime,
 sick_totalday,sick_totalhour FROM tbl_holiday_detail
WHERE Emp_id='". $_SESSION["Emp_id"]."' AND status_leave = 1 AND type_id=2 And sick_startdate< $text  ORDER BY sick_startdate DESC " ;
$query_sicktotal = mysqli_query($objconnect,$selcet_oldday);
$num_sick = mysqli_num_rows($query_sicktotal);
$featch_sick =mysqli_fetch_array($query_sicktotal);
$featch_sick ["sick_startdate"];

if ($num_sick==0) {
  $x ="ยังไม่มีวันลา";
  $x1="ยังไม่มีวันลา";
  $x2='0'.'วัน';
}else {
  $x =$featch_sick["sick_startdate"];
  $x1=$featch_sick["sick_enddate"];
  $x2=$featch_sick["sick_totalday"]+$featch_sick["sick_totalhour"];

}


?>
  <div class="row">
     <div class="col-2">
       <p>ครั้งสุดท้ายตั้งแต่วันที่</p>
     </div>
     <div class="col-2">
      <input type="text" class="form-control" name="Startdate_old" value="<?=$x?>" readonly> 
    </div>
    <div class="col-1">
      <p>ถึงวันที่</p>
    </div>
    <div class="2">
      <input type="text" class="form-control" name="End_old" value="<?=$x1?>"  readonly >
    </div>
    <div class="col-1">มีกำหนด</div>
    <div class="col-2">
      <input type="text" class="form-control" name="End_date_Old" value="<?=$x2?>" >
    </div>
    <div class="col-1">
      <p>วัน</p>
    </div>
  </div>
















  </div>  
</div>
  </div><!-- endbody -->
   <div class="card-footer text-muted" align="center">
    <input type="submit" value="submit" name="btnsub" id="btnsub" class="btn btn-primary" >
    <input type="reset"  name="btnsub" class="btn btn-warning" style="margin-left: 2%;">
  </div>
 </form> 
</div>
        </div>
      </div>
     </div>
    </div>
  </div>
</div>
</body>
<script src="css_and_js/dist/jquery-clockpicker.min.js"></script>
    <script type="text/javascript">
$('.clockpicker').clockpicker().find('input').change(function(){

  });</script>
<script src="css_and_js/javascript/jquery-3.4.1.min.js"></script>
<script src="css_and_js/javascript/popper.min.js"></script>
<script src="css_and_js/javascript/bootstrap.min.js" ></script>

<script>
function caltime (){
      var ST = document.getElementById('Start_time').value;
      var ET = document.getElementById('End_time').value;
      var SD = document.getElementById('Start_date').value;
      var ED = document.getElementById('End_date').value;
      var x = SD+' '+ST;
      var z = ED+' '+ET;
      var SD_new = new Date(x);
      var ED_new = new Date(z);
console.log ("datestat+timestart"+SD_new);
var diffDays = parseInt((new Date(document.getElementById('End_date').value) - new Date(document.getElementById('Start_date').value)) / (1000 * 60 * 60 * 24));

      var diffTimes = parseInt((ED_new - SD_new) / (1000 * 60 * 60));  //คำนวณชม
      console.log('fuck =',diffTimes);
      // var diffTimes = parseInt(ET_new - ST_new);
      //   console.log(diffTimes);




     if(ST != '' && ET != '' && SD != '' && ED != '' ){
        // console.log('x =',SD_new);
        // console.log('z =',ED_new);
        console.log('diffDays =',diffDays);
        // console.log(diffTimes);
        if(diffDays > 0){
          var day = diffDays*24;
          
          if(diffTimes > day){
            var diffT = diffTimes-day;
            if(diffT >= 8){  //ถ้ามากกว่า หรือ เท่ากับ 8 ให้หาร เป็นวัน
              console.log("// มากกว่าเท่ากับ8 เป็น 1วัน");
                     console.log('diffDays =',diffT);
              diffT = (diffT/8).toFixed(0); //
              console.log('วัน =',parseInt(diffT)+parseInt(diffDays));
              document.getElementById('Total_Day').value = parseInt(diffT)+parseInt(diffDays);
              // document.getElementById('Total_Hour').value = diffDays;
              document.getElementById('Total_Hour').value = '0';
            }else{
              console.log("elsetum");
              // diffT = diffT*(diffDays+1);
              
                console.log('ชม =',diffT);
              if (difft=8) {
                document.getElementById('Total_Day').value = diffDays;
                document.getElementById('Total_Hour').value = diffT;
           
              }

            }
          }else{
            var diffT = diffTimes;
            console.log('cat =',diffT);
            if(diffT >= 8){
              var day1 = (diffDays-1)*24;
              console.log('fax =',day1);
              // diffT = (diffT/8).toFixed(0);
              var yy = parseInt(diffT)-parseInt(day1);
              console.log('yy =',yy);
              console.log('dog =',parseInt(diffDays));
              if(yy == 8){
                console.log('88888888888888');
                yy = (parseInt(yy)/8).toFixed(0);
                document.getElementById('Total_Day').value = parseInt(yy)*parseInt(diffDays);
                document.getElementById('Total_Hour').value = '0';
              }else{
                console.log('44444444444444');
                document.getElementById('Total_Day').value = '0';
                document.getElementById('Total_Hour').value = parseInt(yy)*parseInt(diffDays);
                if(day1 == 0){
                  console.log('8-8-8-8-8-8-');
                }else{
                  console.log('4-4-4-4-4-4-');
                }
              }
              
            }else{
                document.getElementById('Total_Day').value = '0';
                document.getElementById('Total_Hour').value = diffT;
              
            }
          }
          
          
          

        }else{ 

          console.log('ELSE');
          if (diffDays<0 || diffTimes<0 || document.getElementById("Start_time").value == document.getElementById("End_time").value) {
            alert("โปรดตรวจสอบวันอีกครั้ง");
            document.getElementById("End_date").focus();
            return false;
          }

          if(diffTimes >= 8){
            diffTimes = (diffTimes/8).toFixed(0);
            console.log('วัน =',diffTimes);
            document.getElementById('Total_Day').value = diffTimes;
              document.getElementById('Total_Hour').value = '0';
          }
          else{
            console.log('ssssssssssss');
            diffTimes = diffTimes;
            // alert("กรณุระบุเวลาใหม่หรือวันที่จบใหม่"); 
            // document.getElementById("End_time").focus();
            // return false;
              if(diffTimes < 0){
                document.getElementById('Total_Day').value = '';
                document.getElementById('Total_Hour').value = ''; 
              }else{
                console.log("else");
                document.getElementById('Total_Day').value = '0';
                document.getElementById('Total_Hour').value = diffTimes
                 }
          }




      }

      var totalday = document.getElementById("totalday").value;
      var checkday = document.getElementById("Total_Day").value;
      var checkhour= document.getElementById("Total_Hour").value;

      console.log('checkday',checkday);
      if (checkday && checkhour && totalday ) {
        var x =parseFloat(0.5) ;
         if (checkday =='0'  ) {
           checkday = 0+x;
           console.log("if",checkday);
         } else if (checkhour =='0'){
           checkday=parseFloat(checkday);
           console.log("elseifsss",checkday);
         }
         else if  (checkhour==4) {
          console.log("elseif444")
              checkhour=x;
          // checkday=parseFloat(checkday)+x;
           // console.log("กุงง",checkday);
         }

        var cal = parseFloat(checkday)+parseFloat(checkhour);
                  console.log("final",cal);
          document.getElementById("hdncount").value=cal;          
         if (totalday<cal) {
            alert("วันไม่พอเว้ย");
           
             document.getElementById("btnsub").disabled = true;
            return false;
           
           }else{
             document.getElementById("btnsub").disabled = false;
           }

          // console.log(checkday);
       
 

      } //check day


    }// if(ST != '' && ET != '' && SD != '' && ED != '' )

}
</script>

<script type="text/javascript" >
  $x=1;
function checktype(){
  var mytypecheck1 = document.getElementById("mytypecheck1").checked;
  var mytypecheck2 = document.getElementById("mytypecheck2").checked;
  var mytypecheck3 = document.getElementById("mytypecheck3").checked;
  var mytypecheck4 = document.getElementById("mytypecheck4").checked;
if (mytypecheck1==true) {
  console.log("if");
  document.getElementById("mytypecheck2").disabled =true;
  document.getElementById("mytypecheck3").disabled =true;
  document.getElementById("mytypecheck4").disabled =true;

}
else if (mytypecheck2==true) {
  console.log("if");
  document.getElementById("mytypecheck1").disabled =true;
  document.getElementById("mytypecheck3").disabled =true;
  document.getElementById("mytypecheck4").disabled =true;

}
else if (mytypecheck3==true) {
  console.log("if");
  document.getElementById("mytypecheck2").disabled =true;
  document.getElementById("mytypecheck1").disabled =true;
  document.getElementById("mytypecheck4").disabled =true;

}
else if (mytypecheck4==true) {
  console.log("if");
  document.getElementById("mytypecheck2").disabled =true;
  document.getElementById("mytypecheck3").disabled =true;
  document.getElementById("mytypecheck1").disabled =true;

}else {

  document.getElementById("mytypecheck1").disabled =false;
  document.getElementById("mytypecheck2").disabled =false;
  document.getElementById("mytypecheck3").disabled =false;
  document.getElementById("mytypecheck4").disabled =false;
}
}
  
</script>
<script>
function onsick(){
var sick= document.getElementById("sick").checked;
var sick2=document.getElementById("sick_2").checked;
var sick3= document.getElementById("sick_3").checked;
var to_leave1 = document.getElementById("to_leave1");
var to_leave2 = document.getElementById("to_leave2");

if (sick==true) {
  console.log("if1");
  to_leave1.disabled=false;
  document.getElementById('sick1_1').checked=true;
  document.getElementById("sick_2").disabled=true;
  document.getElementById("sick_3").disabled=true;

}else if (sick2==true){
   
  to_leave2.disabled=false;
  document.getElementById('sick1_2').checked=true;
  document.getElementById("sick").disabled=true;
  document.getElementById("sick_3").disabled=true;
 }
 else if (sick3==true){
  
  document.getElementById('sick1_3').checked=true;
  document.getElementById("sick").disabled=true;
  document.getElementById("sick_2").disabled=true;
}else{

  document.getElementById("sick").disabled=false;
  document.getElementById("sick_2").disabled=false;
  document.getElementById("sick1_1").checked=false;
  to_leave1.value='';
  to_leave2.value='';
  to_leave1.disabled=true;
  to_leave2.disabled=true;
  document.getElementById("sick_3").disabled=false;
  document.getElementById("sick1_2").checked=false;
  document.getElementById("sick1_3").checked=false;
}
 }
</script>
<script>
  //เช็คค่า value
  var featchcheck = "<?=$featchuserfrom["stype"]?>";
  var feachdetail = "<?=$featchuserfrom["sdetail"]?>";

if(document.getElementById("sick").value==featchcheck){
  document.getElementById("sick").checked=true;
  to_leave1.disabled=false;
   to_leave1.value=feachdetail;
  document.getElementById('sick1_1').checked=true;
  document.getElementById("sick_2").disabled=true;
  document.getElementById("sick_3").disabled=true;
}else if (document.getElementById("sick_2").value==featchcheck) {
  document.getElementById("sick_2").checked=true;
  to_leave2.disabled=false;
  to_leave2.value=feachdetail;
  document.getElementById('sick1_2').checked=true;
  document.getElementById("sick").disabled=true;
  document.getElementById("sick_3").disabled=true;
}else if (document.getElementById("sick_3").value==featchcheck){
  document.getElementById("sick_3").checked=true;
  document.getElementById('sick1_3').checked=true;
  document.getElementById("sick").disabled=true;
  document.getElementById("sick_2").disabled=true;

}else{ 
  document.getElementById("sick").disabled=false;
  document.getElementById("sick_2").disabled=false;
  document.getElementById("sick1_1").checked=false;
  to_leave1.value='';
  to_leave2.value='';
  to_leave1.disabled=true;
  to_leave2.disabled=true;
  document.getElementById("sick_3").disabled=false;
  document.getElementById("sick1_2").checked=false;
  document.getElementById("sick1_3").checked=false;
}

</script>