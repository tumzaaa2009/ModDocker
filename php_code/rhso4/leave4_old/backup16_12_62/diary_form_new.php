<?php
	include 'connect/connect.php';
 
  require 'head/headder.php';




//////////////////////////////////////////////////////////////////////ดึงชื่อผู้ลาพักร้น
$selectuse="SELECT emp.Emp_id as id,emp.Emp_name as empname ,emp.Emp_lastname as emplastname,pgroup.name as emppositionname,af.affiliate_name as affname ,pgroup.name As name FROM tbl_employment AS emp  
INNER JOIN affiliation AS af ON emp.affiliate_id = af.affiliate_id  
INNER JOIN tbl_group_type gtype ON emp.group_id=gtype.group_id
INNER JOIN tbl_position_group pgroup ON emp.Position_id=pgroup.Position_id
INNER JOIN tbl_total_limit_holiday total_limit on total_limit.Emp_id = emp.Emp_id
where emp.Emp_id='". $_SESSION["Emp_id"]."'";
$queryuserfrom = mysqli_query($objconnect,$selectuse);
$featchuserfrom = mysqli_fetch_array($queryuserfrom);

$startvar = "ประกาศตัวแปร";
$featchuserfrom["empname"];
$featchuserfrom["emplastname"];
$featchuserfrom["emppositionname"];
$featchuserfrom["affname"];
$featchuserfrom["name"];
$featchuserfrom["name"];
$endvar ="จบ";
//////////////////////////////////////////////////////////////////////ENDดึงชื่อผู้ลาพักร้น


///////////////////////////////เช็คเอามาบวกลบแล้วนำเข้า
 $selectchecklimitday="SELECT * FROM tbl_total_limit_holiday WHERE 
Emp_id = '". $_SESSION["Emp_id"]."' AND status_limit = 1";
$querychcklimitday = mysqli_query($objconnect,$selectchecklimitday);
$numchecklimitday = mysqli_num_rows($querychcklimitday);
$featchchecklimitday=mysqli_fetch_array($querychcklimitday);

$fix_total = 10+$featchchecklimitday["fix_count_oldyear_holiday"];
/////////////////////////////////จบ นำเข้า////////////////



// ///////////////////////////////isset/////////////////////////////
if (isset($_POST["btnsub"])) {
  // echo '<pre>';
  // echo  print_r($_POST);
  // echo '</pre>';
  $selectholidaynum = "SELECT MAX(convert(hoilday_detail_id,SIGNED)) AS hoilday_detail_id FROM tbl_holiday_detail";
  $queryholidaynum= mysqli_query($objconnect,$selectholidaynum);
  $featchholidaynum = mysqli_fetch_array($queryholidaynum);
  $numholidaynum=$featchholidaynum["hoilday_detail_id"]+1;

if ($_POST["Total_Day"] !='' || $_POST["Total_Hour"]!='') {
  
  if ($_POST["Total_Hour"]==4) {
 $totalhour= $_POST["Total_Hour"]=0.5;   
  
  }else{
  $totalhour=$_POST["Total_Hour"];

  }

if ($_POST["hdncount"] > $_POST["totalholiday"]) { //เกินของเก่า
  $sumold = $_POST["totalholiday"]-$_POST["hdncount"] ; //5-6 = -1; 
  $thisyear = $featchchecklimitday["count_year_holiday"]+$sumold;//9
   $totalsum = $thisyear;
  // echo "if",$totalsum;

}else { //ไม่เกินของเก่า
  $sumold = ($_POST["totalholiday"] - $_POST["hdncount"]); //5-3=2
  $thisyear = $featchchecklimitday["count_year_holiday"];//ของปีนี้ 10วัน
  $totalsum = $thisyear+$sumold;
  // echo 'sum=',$totalsum;

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
 ,status_leave)
    VALUES 
    ('".$numholidaynum."'
    ,'".$_POST["type_id"]."' 
    ,'".$_POST["id"]."' 
    ,'".$_SESSION["groupid"]."'
    ,NOW()
    ,'".$_POST["uname"]."'  
    ,'".$_POST["position"]."' 
    ,'".$_POST["section"]."'  
    ,'".$_POST["totalholiday"]."'  
    ,'".$_POST["totalday"]."'    
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
  ) " ;
$query_horidayinsert= mysqli_query($objconnect,$holiday_detail_insert);
 if($sumold<0){
  $sumold=0; 
 }else {
  $sumold=$sumold;
 }
  $updateholiday = "UPDATE tbl_total_limit_holiday
  SET  count_oldyear_hoilday='".$sumold."' ,count_year_holiday='".$thisyear."',sum_thisyear_holiday='".$totalsum."'
  WHERE Emp_id = '". $_SESSION["Emp_id"]."' AND status_limit = 1" ;
  mysqli_query($objconnect,$updateholiday);
} //isset tottalday,hour
  echo "<script>alert(\"บันทึกข้อมูลเรียบแล้ว\");window.location.href = \"index.php?st=add\";</script>";
 } //isset btn
?>
  <!-- clockpicker -->
    <link rel="stylesheet" href="css_and_js/dist/bootstrap-clockpicker.css">
 
<body>
  <div class="page-header" align="center">
  <h1 style="color: black" > แบบฟอร์มลาพักร้อน </h1>

</div><!-- /.page-header -->

<div class="container-fulid" >


      <form method="post" class=" border border-light p-5">
  <div class="form-row mb-4">
    <div class="col" align="right">
         <label>เขียน </label> <label style="BORDER-BOTTOM: #0C0C0C 1px  dotted;text-align: center;">ที่สำนักงานเขตสุขภาพที่ 4</label>
    </div>
  </div>
     
  <div class="form-row mb-4">
    <div class="col" align="right">
      <label>วันที่</label> <label style="BORDER-BOTTOM: #0C0C0C 1px  dotted;text-align: center;"><?=date("d/m/Y ");?></label>
    </div>
  </div>
  <div class="form-row mb-4">
    <div class="col">
       <label>เรื่อง ขอลาพักผ่อน</label>
    </div>
   
  </div>
  <div class="form-row mb-4">
    <div class="col">
      <label>เรียน ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4 </label>
    </div>
  </div>
  <div class="form-row mb-4">
    <div class="col-1"style="margin-left:10%; ">
      <label>ข้าพเจ้า</label> 
    </div>
    <div class="col-4">
      <!-- เก็บค่า typeของวันลา บวกลบกับวันลาปีเก่า-->
          <input type="hidden" name="id" value="<?=$featchuserfrom["id"]?>" >
          <input type="hidden" name="hdncount" id="hdncount">

          <input type="hidden" name="type_id" value="1" >
          <!-- END -->
          <input type="text" name="uname"  class="form-control " value="<?= $featchuserfrom["empname"],"  ",$featchuserfrom["emplastname"];;?>" readonly >
        <!--   <input class="form-control input-sm"  type="text" name="uname" placeholder="ชื่อ"  value="<?= $featchuserfrom["empname"],"  ",$featchuserfrom["emplastname"];;?>" readonly> -->
    </div>
    <div class="col-1">
    <p>ตำแหน่ง</p>
    </div>
    <div  class="col-4">
   <input class="form-control" type="text" style="text-align: center;" readonly name="position" value="<?=$featchuserfrom["emppositionname"]?>" placeholder="ตำแหน่ง">
    </div>
   <div class="col-2">
     
   </div>
</div> 
<div class="form-row mb-4">
  <div class="col-1">
    <label>สังกัด</label>
  </div>
  <div class="col-10 ">
    <input class="form-control" type="text"  style="text-align: center;" readonly value="<?=$featchuserfrom["affname"]?>" name="section" placeholder="สังกัด">
  </div>
</div>
<div class="form-row mb-4">
  
    <?php 
if($numchecklimitday!=1){
  echo 'checkตัดวันลาใหม่';
}else{
?>
<div class="col-1">
  <label>มีวันลาพักผ่อนสะสม:</label>
</div>
    <div class="col-1">
        <input class="form-control" type="text"readonly="true"  name="totalholiday" 
          value="<?=$featchchecklimitday["fix_count_oldyear_holiday"]?>" placeholder="วันลาสะสมทั้งหมด">
  <input class="form-control" type="text"readonly="true" hidden="true"  name="totalholiday" 
          value="<?=$featchchecklimitday["count_oldyear_hoilday"]?>" placeholder="วันลาสะสมทั้งหมด">
    </div>
    <div class="col-1">
  <label>วันทำการ</label>
    </div>
  <div class="col-md-auto">
  <label class="form-check-label" for="materialInline1" > มีสิทธิลาพักร้อนประจำปีนี้อีก 10 วันทำการ รวมเป็น</label>
  </div>
  <div class="col-1"  >
    <input class="form-control" type="text" id="totalday"  name="totalday" hidden="true" readonly="true" value="<?=$featchchecklimitday["sum_thisyear_holiday"]?>" >
    <input class="form-control" type="text" id="totalday"  name="totalday" readonly="true" value="<?=$fix_total?>" >
  </div>
   <div class="col-1">
   <label>วัน</label>
 </div>

 </div>
<?php }?>
<div class="form-row mb-4">
  <div class="col-md-auto">
    <label>ขอลาพักผ่อนตั้งแต่วันที่</label>
  </div>
  <div class="col-md-auto">
    <input id="Start_date" name="Start_date" type="date" class="form-control"  required="required" onchange="caltime()" >
  </div>
  <div class="col-md-auto" align="center">
    <label>ตั้งแต่เวลา</label>
  </div>
  <div class="col-md-auto">
     <input class="input-group clockpicker" data-placement="top" data-align="left" data-donetext="Done"  id="Start_time" name="Start_time" type="text"    required="required" style="color:#FF0000;" placeholder="Ex.08:30 " onchange="caltime()" > 
  </div>
  <div class="col-md-auto" align="center">
   <label>ถึงวันที่</label>
  </div>
  <div class="col-md-auto">
    <input id="End_date" name="End_date" onchange="caltime()"type="date" class="form-control"  required="required" >
  </div>
  <div class="col-md-auto" align="center">
    <label>ถึงเวลา</label>
  </div>
  <div class="col-md-auto">
    <input class="input-group clockpicker" data-placement="left" data-align="top"  data-donetext="Done"
            id="End_time" name="End_time" type="text" onchange="caltime()"  required="required" style="color:#FF0000;" placeholder="Ex.16:30 " >
  </div>
  <div class="col-md-auto" align="center">
    <label>รวมทั้งสิ้น</label>
  </div>
  <div class="col-1">
    <input class="form-control" type="text" name="Total_Day" id="Total_Day" required="true" readonly="true" >
  </div>
  <div class="col-md-auto">
    <label>วัน</label>
  </div>
  <div class="col-md-auto">
    <input class="form-control" type="text" name="Total_Hour" id="Total_Hour" required="true" readonly="true">
  </div>
  <div class="col-md-auto">
    <label>ชั่วโมง</label>
  </div>
</div>
<div class="form-row mb-4">
  <div class="col-1.5">
    <label>ในระหว่างลาติดต่อขัาพเจ้าได้ที่</label>
  </div>
<div class="col">
  <input class="form-control" type="text" name="contract" required="true" placeholder="ติดต่อได้ที่">
</div>
</div>
<div class="form-row mb-4">
  <div class="col-1.5">
      <label>ในระหว่างลานี้ ได้มอบหมายให้</label>
  </div>
<div class="col">
  <input class="form-control" type="text" name="objcontract" required="true" placeholder="ได้มอบหมายให้">
</div>
</div>
<div>
  
</div>
<div class="row">
  <div class="col-6" align="right">
    <input type="submit" value="submit" name="btnsub" id="btnsub" class="btn btn-primary btn-md" >
  </div>
  <div class="col-6">
    <input type="reset"  name="btnsub" class="btn btn-warning btn-md">
  </div>
</div>


</form>

</div><!-- contenier-->
 
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
      var cal = parseFloat(checkday)+parseFloat(checkhour);
      console.log('checkday',checkday);
      if (checkday && checkhour && totalday ) {
                if (checkday==0) {
                        if (checkhour==4) {
                          console.log('iftummmmmmmmmmmmmmmm');
                          checkhour=0.5;
                          cal = parseFloat(checkday)+parseFloat(checkhour);

                          }  } else { //checkday!=0
                          
                          if (checkhour==4) {
                              checkhour=0.5;
                              console.log('elsetttttttttttt',checkhour);
                              cal = parseFloat(checkday)+parseFloat(checkhour);    
                                                
                          }
                         }
       console.log('final',cal);
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

