<?php 
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include_once 'header_css.php';
include_once '../connect/connect.php';

$selectdecrip="SELECT *,tyhd.type_name  typename FROM tbl_employment emp INNER JOIN tbl_holiday_detail detail ON emp.Emp_id = detail.Emp_id
INNER JOIN tbl_total_limit_holiday limithol ON emp.Emp_id = limithol.Emp_id 
INNER JOIN tbl_type_hoilday tyhd ON tyhd.type_id = detail.type_id
where emp.Emp_id = '".$_GET["emp"]."' and detail.hoilday_detail_id = '".$_GET["id"]."'AND limithol.status_limit = 1 and  detail.type_id='".$_GET["type_id"]."'";
$queryselect = mysqli_query($objconnect,$selectdecrip);
$num_row = mysqli_num_rows($queryselect);
$featchselect = mysqli_fetch_array($queryselect);
$check_status=$featchselect["status_leave"];
 $newDate = date("d/m/Y", strtotime($featchselect["dateapprove_by"]));
$signatle_name=$featchselect["Titile_name"]." ".$featchselect["Emp_name"]." ".$featchselect["Emp_lastname"];
//////////////////////////////////////////////////// ลาครั้งหลังสุด//////////
$select_dayold = "SELECT sick_startdate,sick_enddate,sick_totalday,sick_totalhour,sick_type
FROM tbl_holiday_detail
WHERE Emp_id = '".$_GET["emp"]."'
 AND Type_id = '".$_GET["type_id"]."' 
 and status_leave>0
 and sick_startdate < '".$featchselect["sick_startdate"]." '
 ORDER BY sick_startdate DESC ";
$query_dayold = mysqli_query($objconnect,$select_dayold);
$numdayold = mysqli_num_rows($query_dayold);
$featchnum_old = mysqli_fetch_array($query_dayold);
$count_old = $featchnum_old["sick_totalday"] +$featchnum_old["sick_totalhour"] ;
 $check_status=$featchselect["status_leave"];
//////////////////////////////////////////////////// ลาครั้งหลังสุด//////////
//////////////////////////////////////////////////// typeป่วย //////////
$select_countsick="SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr  
FROM tbl_holiday_detail
WHERE Emp_id = '".$_GET["emp"]."' AND Type_id = '".$_GET["type_id"]."' 
AND sick_type='ป่วย' and status_leave>0
and sick_startdate< '".$featchselect["sick_startdate"]."' ";
$query_countsick = mysqli_query($objconnect,$select_countsick);
$mynum = mysqli_num_rows($query_countsick);

//ป่วย
$old_countsick=mysqli_fetch_array($query_countsick);
$old_daysick= $old_countsick["dy"]+$old_countsick["hr"];

/////////////////////////////type ลากิจส่วนตัว///////////////////////
$select_typename2="SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr     
FROM tbl_holiday_detail  WHERE Emp_id = '".$_GET["emp"]."' AND Type_id = '".$_GET["type_id"]."' AND sick_type='ขอลากิจส่วนตัว' and status_leave>0
AND sick_startdate < '".$featchselect["sick_startdate"]."'" ;

$query_typename2 = mysqli_query($objconnect,$select_typename2);
$num_typename2 = mysqli_num_rows($query_typename2);
// echo 'string',$num_typename2;
$featch_typename2 =mysqli_fetch_array($query_typename2);
$counttype_2 = $featch_typename2["dy"]+$featch_typename2["hr"];

//////////////////////////////////////////////////////////////////////
/////////////////////////////type ลาคลอด///////////////////////
$select_typename3="SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr     
FROM tbl_holiday_detail  WHERE Emp_id = '".$_GET["emp"]."' AND Type_id = '".$_GET["type_id"]."' AND sick_type='คลอตบุตร' and status_leave>0
AND sick_startdate < '".$featchselect["sick_startdate"]."'" ;

$query_typename3 = mysqli_query($objconnect,$select_typename3);
$num_typename3 = mysqli_num_rows($query_typename3);
// echo 'string',$num_typename3;
$featch_typename3 =mysqli_fetch_array($query_typename3);
$counttype_3 = $featch_typename3["dy"]+$featch_typename3["hr"];

//////////////////////////////////////////////////////////////////////
$approve_by= $_SESSION["name"]." ".$_SESSION["lastname"];
if (isset($_POST["confrim"]) ) {
   if ($_POST["confrim"]=="confrim") {
 


     $update = "Update tbl_holiday_detail set status_leave =2,approve_by='".$approve_by."',dateapprove_by=NOW()
      where Emp_id='".$_GET["emp"]."' and hoilday_detail_id='".$_GET["id"]."' and type_id='".$_GET["type_id"]."'";
     mysqli_query($objconnect,$update);




     ?>
  <script>
 alert("ยืนยันเรียบร้อย");
   x =<?=$_GET["id"] ?>;
   y =<?=$_GET["emp"]?>;
   z=<?=$_GET["type_id"]?>;
   location.href ='index_admin.php?ap=approve.php?id='+x+'&&emp='+y+'&&type_id='+z;
 </script>";  
<?php 
   }
 }else if (isset($_POST["cancel"])) {
    if ($_POST["cancel"]="cancel") {
     // $update = "Update tbl_holiday_detail set status_leave =-2 ,approve_by='".$approve_by."',dateapprove_by=NOW()
     //  where Emp_id='".$_GET["emp"]."' and hoilday_detail_id='".$_GET["id"]."' and type_id='".$_GET["type_id"]."'";
     // mysqli_query($objconnect,$update);
     }?>

<script>

   x =<?=$_GET["id"] ?>;
   y =<?=$_GET["emp"]?>;
   z=<?=$_GET["type_id"]?>;
   location.href ='delete_admin.php?id='+x+'&&emp='+y+'&&type_id='+z;
 </script>";  
 <?php 
 }

//////////////////////////////////////////////////////////////

if (isset($_POST["confrim_sus"]) ) {
   if ($_POST["confrim_sus"]=="confrim_sus") {
 $approve_by= $_SESSION["name"]." ".$_SESSION["lastname"];


     $update = "Update tbl_holiday_detail set status_leave =3,approve_sussecc='".$approve_by."',dateapprove_sussecc=NOW()
      where Emp_id='".$_GET["emp"]."' and hoilday_detail_id='".$_GET["id"]."' and type_id='".$_GET["type_id"]."'";
     mysqli_query($objconnect,$update);




     ?>
  <script>
 alert("ยืนยันเรียบร้อย");
   x =<?=$_GET["id"] ?>;
   y =<?=$_GET["emp"]?>;
   z=<?=$_GET["type_id"]?>;
   location.href ='index_admin.php?ap=approve.php?id='+x+'&&emp='+y+'&&type_id='+z;
 </script>";  
<?php 
   }
 }else if (isset($_POST["cancel_sus"])) {
    if ($_POST["cancel_sus"]="cancel_sus") {
     // $update = "Update tbl_holiday_detail set status_leave =-3,approve_sussecc='".$approve_by."',dateapprove_sussecc=NOW()
     //  where Emp_id='".$_GET["emp"]."' and hoilday_detail_id='".$_GET["id"]."' and type_id='".$_GET["type_id"]."'";
     // mysqli_query($objconnect,$update);
    }?>

<script>

   x =<?=$_GET["id"] ?>;
   y =<?=$_GET["emp"]?>;
   z=<?=$_GET["type_id"]?>;
   c="cancel_sus";
   location.href ='delete_admin.php?id='+x+'&&emp='+y+'&&type_id='+z+'&&cancel_sus='+c;
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

<style type="text/css">
   .input_show {
          border: 0;
          outline: 0;
          BORDER-BOTTOM: #0C0C0C 1px  dotted;
          text-align: center;
          margin-top: -5px;
          vertical-align: top;
       }
       .input_show1 {
          border: 0;
          outline: 0;
          border-bottom: 0px solid black;
          padding-left: 20px;
       }
       .input_show2 {
          border: 0;
          outline: 0;
          border-bottom: 0px solid black;
          text-align: center;
       }
          .input_show3 {
          border: 0;
          outline: 0;
         /* border-bottom: 1px solid black;*/
          text-align: center;
          margin-top: -5px;
          vertical-align: top;
          
       }

@media print
{
#non-printable { display: none; }
 #container_div
          {

               margin-top: 50px;
             text-align: justify;
             text-justify: inter-word;
             margin-left: 35px;
             font-size: 20px;
            
          }
}
</style>
  </head>
  <body >
  <!-- Breadcome -->
      <nav aria-label="breadcrumb" id="non-printable">
       <div class="container-fluid"> 
        <div class="row"> 
          <div class="col-12" >
              <ol class="breadcrumb">
              <li class="breadcrumb-item "> <a href="../backend/index_admin.php" class="btn btn-primary">HOME</a></li>

<!--      <?php  if($featchselect["status_leave"]==1) { ?>
    <div class="box" style="width: 450px;margin-left: 15%; ">
  
      <div class="row">
      <form method="POST" accept-charset="utf-8"> 
       <div id="box1" style="width: 550px;" >   
             <div class="row">
            <div class="form-group col-3">
             <?php if ($featchselect["status_leave"]==2){?>
                <i class="fas fa-check"></i>
           <?php     } ?>
                 <button class="btn btn-success" name="confrim"  id="confrim" value="confrim" >
               <i class="fas fa-check"></i>ยืนยัน</button> 
            </div>
            <div class="form-group col-3"  style="width: 450px;">
                            <?php if ($featchselect["status_leave"]==-2){?>
                <i class="fas fa-check"></i>
           <?php     } ?>
                   <button class="btn btn-danger" name="cancel" id="cancle" value="cancle"><i class="fas fa-ban"></i>ไม่อนุมัติ</button>   
            </div>

            </div>
        </div> 
   

         </form> 

        </div>
   </div>
<?php }?>
     <?php  if($featchselect["status_leave"]==2) { ?>
    <div class="box" style="width: 450px;margin-left: 15%; ">
  
      <div class="row">
      <form method="POST" accept-charset="utf-8"> 
       <div id="box1" style="width: 900px;" >   
             <div class="row">
            <div class="form-group col-3">
             <?php if ($featchselect["status_leave"]==3){?>
                <i class="fas fa-check"></i>
           <?php     } ?>
                 <button class="btn btn-success" name="confrim_sus"  id="confrim_sus" value="confrim_sus" >
               <i class="fas fa-check"></i>ยืนยันการลาครั้งสุดท้าย</button> 
            </div>
            <div class="form-group col-3"  style="width: 450px;">
                            <?php if ($featchselect["status_leave"]==3){?>
                <i class="fas fa-check"></i>
           <?php     } ?>
                   <button class="btn btn-danger" name="cancel_sus" id="cancel_sus" value="cancel_sus"><i class="fas fa-ban"></i>ยกเลิกการลา</button>   
            </div>

            </div>
        </div> 
   

         </form> 

        </div>
   </div>
<?php }?>
 -->



            <div class="col-11" align="right" >
                <button type="button" class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
            </div>
          
            </ol>
            </div>
            </div></div>   
      </nav>
     
<div id="container_div">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12" >
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
                   <div class="card-body">
             <h5 class="card-title input_show3" align="center">แบบใบลาป่วย ลาคลอดบุตร ลากิจส่วนตัว 
              <?php if ($featchselect["status_leave"] ==-1 || $featchselect["status_leave"] ==-2|| $featchselect["status_leave"] ==-3 || $featchselect["status_leave"] ==0) { ?> 
             <h1 style="color: red;text-align: center;">ยกเลิก <?php if (($featchselect["status_leave"] ==0)) {
               echo "(เนื่องจาก".$featchselect["cancle_approvesusecc_comment"].")";
             }?></h1>
                <?php }?>
            </h5>
             <p class="card-text" align="right" style="margin-right: 5%;"> เขียน......<?=$featchselect["sick_section"]?>.......</p>
             <p class="card-text" align="right" style="margin-right: 5%;"><font>วันที่.........................<?=date("d/m/y",strtotime($featchselect["register_date"]))?></font>......................... </p>
            <p class="card-text input_show1" style="margin-right: 31%;"><font>เรื่อง ขออนุญาตลา <?=$featchselect["sick_type"]?></font> </p>          
            <p class="card-text input_show1" style="margin-right: 31%;"><font>เรียน  ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4</font> </p>
           <p class="card-text input_show1" style="margin-left: 5%"><font > ข้าพเจ้า ......<font class=""> <?=$featchselect["hoilday_detail_name"]?>......</font> 
           ตำแหน่ง ......<font class=""> <?=$featchselect["sick_positon"]?></font>.....</font></p>
  <?php 
$type_group = "select * from tbl_group_type";
$query_group =mysqli_query($objconnect,$type_group);
$num_type = mysqli_num_rows($query_group);

    ?>
           <p class="card-text " style="margin-left:1.5%">
       <?php $num_query_group= 1;
            while ($featch_query_group=mysqli_fetch_array($query_group)) {
      $checkboxtype="checkboxtype".$num_query_group;
             $num_query_group++;
             ?>
         <font style="margin-left: 1%"><input type="checkbox" name="checkboxtype" id="<?=$checkboxtype?>" disabled  value="<?=$featch_query_group["group_id"]?>">
              <?=$featch_query_group["name"]?></font>
          <?php }?>
            </p>

         <p class="card-text " >
             <font style="margin-left: 3.2%;" >  <input class="form-check-input" type="checkbox" name="check_leave" value="ป่วย" id="sick_1" >ป่วย
            </font>
           <font style="margin-left: 11%;" >
            เนื่องจาก <input type="text" class="input_show2" disabled="true" id="to_leave1"  name="To_leave_1" disabled="true" style="width: 50%"> 
          </font> 
        </p>
        <p class="card-text " >
             <font style="margin-left: 3.2%;" >  <input class="form-check-input" type="checkbox" disabled="true"name="check_leave" value="ขอลากิจส่วนตัว" id="sick_2" >ขอลากิจส่วนตัว
            </font>
           <font style="margin-left: 1%;" >
            เนื่องจาก <input type="text" class="input_show2" disabled="true" id="to_leave2"  name="To_leave"  style="width: 50%"> 
          </font> 
        </p>
       <p class="card-text " >
             <font style="margin-left: 3.2%;" >  <input class="form-check-input" type="checkbox" name="check_leave" value="คลอดบุตร" id="sick_3"disabled="true" >คลอดบุตร
            </font>
        </p> 

          <p class="card-text "  style="margin-left: 1.5%;">
            <font > ขอลาตั้งแต่วันที่ <font class=""> .............<?=date("d/m/y",strtotime($featchselect["sick_startdate"]))?>.............</font>  เวลา
             <font class=""> .............<?=substr($featchselect["sick_startime"],0,5)," น"?>.............</font> ถึงวันที่
             <font class="" >.............<?=date("d/m/y",strtotime($featchselect["sick_enddate"]))?>............. </font> <br>เวลา
             <font class="" >.............<?=substr($featchselect["sick_endtime"],0,5)," น"?>............. </font> มีกำหนด
             <font class="" ><?php
$totalday= $featchselect["sick_totalday"];
$totalhour=$featchselect["sick_totalhour"];
  if ($featchselect["sick_totalhour"]==4) {
    // echo 'fuck';
    $totalhour=0.5;
  }
  if ($featchselect["sick_totalday"] == "0") {
     // echo 'iftotal_day';
  $totalday=0;
  }
  if($featchselect["sick_totalhour"] == "0"){
    // echo 'iftotal_hour';
  $totalhour=0;
  }
   $totalsumsick=$totalday+$totalhour;
    echo ".........................",$totalsumsick,".........................";
             ?> </font> วัน
            </font> 
          </p>
  <p class="card-text " style="margin-left:1.5%">
    <font>ข้าพเจ้าได้ลา</font>
             <font style="margin-left: 3.2%;" >  <input class="form-check-input" type="checkbox" name="check_leave" value="ป่วย" id="sick_1_1" >ป่วย
            </font>
             <font style="margin-left: 3.2%;" >  <input class="form-check-input" type="checkbox" disabled="true"name="check_leave" value="ขอลากิจส่วนตัว" id="sick_2_1" >ขอลากิจส่วนตัว
            </font>
             <font style="margin-left: 3.2%;" >  <input class="form-check-input" type="checkbox" disabled="true"name="check_leave" value="ขอลากิจส่วนตัว" id="sick_3_1" >คลอดบุตร
            </font>
      
    </p>

  
<p class="card-text " style="margin-left:1.5%">

    <font>ครั้งสุดท้ายวันที่</font>
     <font class=""> .............<? if ($featchnum_old ["sick_startdate"]=="") {
       
     }else{
    echo date("d/m/y",strtotime($featchnum_old ["sick_startdate"]));

  }
     ?>.............</font>ถึงวันที่
          <font class=""> .............<? if ($featchnum_old ["sick_startdate"]=="") { }else{
            echo  date("d/m/y",strtotime($featchnum_old ["sick_enddate"]));}?>.............</font> มีกำหนด
   <font class=""> .............<?=$count_old?>.............</font> วัน (<?=$featchnum_old["sick_type"]?>)
    </p>
 

              <p class="card-text input_show1" >
           </p>
       
              <p  align="right" class="mb-5" style="margin-right: 23%;"><font >ขอแสดงความนับถือ</font></p>
              <p align="right" style="margin-right: 20.9%;"><font>(...<?=$signatle_name?>...)</font></p>
              <p align="right" style="margin-right: 20%; ">ตำแหน่ง....<font><?=$featchselect["sick_positon"]?></font>....<p>
       <div class="container-fluid">
              <div class="row">
              <div class="col-6" >
                <font><u>สถิติการลาในปีงบประมาณนี้</u></font>
                <table class="table" >
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
                        <td><?=$old_daysick?></td>
                        <td><?php if($featchselect["sick_type"]=="ป่วย"){
                          echo $totalsumsick;
                        }else{echo 0 ;
                        }?></td>
                        <td><?php if($featchselect["sick_type"]=="ป่วย"){
                          echo $totalsumsick+$old_daysick;
                         }else {
                          echo $old_daysick;
                         }
                        ?></td>
                      </tr>
                     <tr align="center"  class="table-secondary">
                        <td>กิจส่วนตัว</td>
                        <td><?=$counttype_2 ?></td>
                        <td>
                      <?php if($featchselect["sick_type"]=="ขอลากิจส่วนตัว"){
                          echo $totalsumsick;
                        }else{echo 0 ;
                        }?>
                          
                        </td>
                        <td><?php if($featchselect["sick_type"]=="ขอลากิจส่วนตัว"){
                          echo $totalsumsick+$counttype_2;
                         }else {
                          echo $counttype_2 ;
                         }
                        ?></td>
                     </tr> 
                    <tr  align="center" class="table-info">
                        <td>คลอดบุตร</td>
                        <td><?=$counttype_3?></td>
                        <td> <?php if($featchselect=="ขอลากิจส่วนตัว"){
                          echo $totalsumsick;
                        }else{echo 0 ;
                        }?>
                          </td>
                        <td><?php if($featchselect["sick_type"]=="คลอตบุตร"){
                          echo $totalsumsick+$counttype_3;
                         }else {
                           echo $counttype_3;
                         }
                        ?></td>
                     </tr> 
                    </tbody>
                  </table>
          
                </div>
         <div class="col-6" >
        <p >
        <font   ><b ><u>ความเห็นผู้บังคับบัญชา</u></b>
        <font style=" border-bottom: 1px dashed #000 ">
      <p class="mb-5"><?=$featchselect["approve_comment"]?></p>
     </font> 
    <?php if (isset($featchselect["approve_by"])   ) {?>
      <font >(ลงชื่อ).........<?=$featchselect["approve_by"]?>..........</font>
   <?php }else{?> <font >(ลงชื่อ)................................................</font>
   <?php } ?>
        
        <?php if (isset($featchselect["approve_section_by"])) {?>
            <p>(ตำแหน่ง).....<?=$featchselect["approve_section_by"]?>....</p>
       <?php  }else{?>
              <p>(ตำแหน่ง)...........................................</p>
      <?php  } ?>
      
  

<?php if ($featchselect["status_leave"]!=1) {echo   "<p>วันที่........................".$newDate."......................... </font>";} else{

echo   "<p>วันที่...................................................</p>";

}?>

           </font>
        </p>


        <div>
      
        </div>
                </div>
           
              </div>
            </div>
<br>
<div class="container-fluid">
    <div class="row">
      <div class="col-6">
        <?php if (isset($featchselect["approve_by"])) {?>
           <p>(ลงชื่อ)..........<?=$featchselect["approve_by"]?>...........ผู้ตรวจสอบ</p>
      <?php   }else{?>
             <p>(ลงชื่อ)..................................ผู้ตรวจสอบ</p>
      <?php } ?>
 <?php  if (isset($featchselect["approve_section_by"])) {?>
   <p>(ตำแหน่ง).....<?=$featchselect["approve_section_by"]?>......</p>
 <?php }else{?>
    <p>(ตำแหน่ง).......................................</p>
 <?php } ?>
  
<?php if ($featchselect["status_leave"]==-1 or $featchselect["status_leave"]== 1) {
echo   "<font>วันที่..................................................</p>";

  } else{


echo   "<p>วันที่.......................".$newDate."......................</font>";
}?>
        </div>  
        <div class="col-6" >
        <p >คำสั่ง</p>

           <p style="width: 100%;">

   <input type="checkbox" id="approve" disabled="true" style="width: 20px; height: 20px; "> อนุญาต 
           <font style="margin-left:1%;">
            <input type="checkbox" disabled="true" id="disapporve" style="width: 20px; height: 20px; "> ไม่อนุญาต</font></p>
        <font style=" border-bottom: 1px dashed #000 ">
      <?=$featchselect["approve_comment_susecc"]?>
     </font> 
     <?php if (isset($featchselect["approve_sussecc"])) {?>
             <p class="mt-5">(ลงชื่อ)..............<?=$featchselect["approve_sussecc"]?>..............</p>
    <?php }else{?>
        <p class="mt-5">(ลงชื่อ)..................................................................</p>
    <?php } ?>

<?php  if (isset($featchselect["approve_section_seccess"])) { ?>
        <p>(ตำแหน่ง).....<?=$featchselect["approve_section_seccess"]?>....</p>
<?php } ?>
         
       <?php if ($check_status==3 or $check_status==3 ) {
         
     
        echo   "<p>วันที่......................".date("d/m/y",strtotime($featchselect["dateapprove_sussecc"]))."......................</font>";  } else{

echo   "<p>วันที่.......................................................................</font>";
}?>
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

  <script>
    function del(x,y,z) {

  if (confirm("ต้องการลบข้อมูลนี้หรือไม่")) {
   location.href ='delete_decription.php?id='+x+'&&emp='+y+'&&type_id='+z;
  } else {
    console.log('g');
  }

}
function edit(x,y,z) {

  if (confirm("ต้องการแก้ไขหรือไม่")) {
   location.href ='editdecription_leave.php?id='+x+'&&emp='+y+'&&type_id='+z;
  } else {
    console.log('g');
  }

}
  </script>
<script> //type checkbox 1
  var num_type="<?=$num_type?>";
  var checkboxtype="checkboxtype";
   var check ="<?=$featchselect["type_id"] ?>";
   var checkbox = document.getElementById("checkboxtype");

for (var i =1 ;i<= num_type; i++) {
 var x =checkbox+i;
 // console.log(x);
  if (x==check) {
    document.getElementById("checkboxtype"+i).checked = true;

  // console.log(document.getElementById("checkboxtype"+i))
  }
}
</script>


<script> //เช็ค detail
  var type = "<?=$featchselect["sick_type"]?>";
  var sickdetail="<?=$featchselect["sick_detail"]?>";
  var sick_1 = "sick_1";
  var to_leave1 = "to_leave1";
  var sick_2 ="sick_2";
  var to_leave2 = "to_leave2";
  var sick_3 ="sick_3";
if (type=="ป่วย") {
  document.getElementById(sick_1).checked = true;
   document.getElementById(to_leave1).value=sickdetail;
   console.log(document.getElementById(sick_1));
   document.getElementById("sick_1_1").checked = true;

}else if(type=="ขอลากิจส่วนตัว"){
  document.getElementById(sick_2).checked = true;
   document.getElementById(to_leave2).value=sickdetail;
    document.getElementById("sick_2_1").checked = true;

}else{
   document.getElementById(sick_3).checked = true;
  document.getElementById("sick_3_1").checked = true;
}
</script>


<script type="text/javascript">
  var box1 =document.getElementById("box1");
  var box2 =document.getElementById("box2");
  var status_leave = "<?=$featchselect["status_leave"]?>";
 
 // if (status_leave==1) {
 //   box2.style.visibility = "hidden";

 // }else if (status_leave==2) {
 //   box1.style.visibility = "hidden";
 //   box2.style.visibility = "show";
 // }else{
 //  box1.style.visibility = "hidden";
 //   box2.style.visibility = "hidden";
 // }


</script>

</html>


<script >
var status= <?=$check_status?>;
if (status==2 ||status == 3) {
  document.getElementById("approve").checked = true;
}else if (status==-2||status==-3||status==0) {
   document.getElementById("disapporve").checked = true;
}
</script>