<?php

?>

<body>
<?php 
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include_once '../connect/connect.php';


$select = "SELECT *,gtype.name AS gname,psgroup.name AS pname ,thoildiay.type_name FROM tbl_employment AS emp INNER JOIN tbl_holiday_detail as detail  ON emp.Emp_id = detail.Emp_id
INNER JOIN tbl_group_type AS gtype ON gtype.group_id=emp.group_id
INNER JOIN tbl_position_group As psgroup ON psgroup.Position_id = emp.Position_id 
INNER JOIN tbl_type_hoilday AS thoildiay ON thoildiay.type_id = detail.type_id
" ;
$select_query=mysqli_query($objconnect,$select);
$num=mysqli_num_rows($select_query);

// echo $select;
?>

<!DOCTYPE html>
<html>
<head>
<!--  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"> -->


  <link rel="stylesheet" type="text/css" href="../css_and_js/js_datatable/css/dataTables.bootstrap4.min.css"> 
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap4.min.css">
</head>
<body>
    <div class="container-fluid">
    <div class="row">
         <div class="col-12">
             <table id="myTable"  class="table table-striped table-bordered" >
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th >ชื่อ</th>
                <th>ตำแหน่ง</th>
                <th>ประเภทการลา</th>
                <th>วันที่ลงลา</th>
                <th>ตั้งแต่วันที่</th>
                <th>เวลา</th>
                <th>ถึงวันที่</th>
                <th>เวลา</th>
                <th>จำนวนวันลา</th>
                <th >สถานะ</th>
                <th>VIEW</th>
                
            </tr>
        </thead>
        <tbody>
                <?php $numid= 1;
                while ($featchselect=mysqli_fetch_array($select_query)) {
                $id="hd_id".$numid ;
           
                ?>
            <tr >
                <td><?=$numid?></td>
                <td width="10%"><?=$featchselect["hoilday_detail_name"]; ?></td>
            <!--     <td ><?=$featchselect["gname"]; ?></td> -->
                <td><?=$featchselect["pname"]; ?></td>
                <td width="10%;" ><?php 
                if($featchselect["type_id"]==1){
                    echo $featchselect["type_name"];
                ; }else{
                   echo $featchselect["sick_type"];
                }?></td>
                <td width="10%;" ><?=date("d-m-y", strtotime($featchselect["register_date"])); ?></td>
                <td width="10%;" ><?php 
                if($featchselect["type_id"]==1){
                echo date("d-m-y", strtotime($featchselect["holiday_startdate"]));}else {
                echo date("d-m-y", strtotime($featchselect["sick_startdate"]));    
                } ?></td>
                <td ><?php 
                if ($featchselect["type_id"]==1) {
                    echo $featchselect["holiday_startime"];  
                }else {
                    echo $featchselect["sick_startime"]; 
                }
                ?></td>
                <td width="10%;"><?php
                if($featchselect["type_id"]==1){
                echo date("d-m-y", strtotime($featchselect["holiday_enddate"])); 
                    }else{
                        echo date("d-m-y", strtotime($featchselect["sick_enddate"])); 
                    }
                ?></td>
                <td ><?php 
                if($featchselect["type_id"]==1){
                echo $featchselect["holiday_endtime"]; 
                }else{
                          echo $featchselect["sick_endtime"]; 
                }
                ?></td>
                <td  width="5%;"><?php 
                if($featchselect["type_id"]==1){
                // $featchselect["holiday_totalhour"]=0.5;
             echo $featchselect["hoilday_totalday"]+$featchselect["holiday_totalhour"];
            }else{
                // $featchselect["sick_totalhour"]=0.5;
              echo  $featchselect["sick_totalday"]+$featchselect["sick_totalhour"];
            }
             ?></td>
                <td ><?php 
                if($featchselect["status_leave"]==1){
                    echo 'ส่งเรื่องการลา';} 
                    else if ($featchselect["status_leave"]==-1) {
                        echo "<font color=\"red\" >".'ยกเลิกการลา'."<font>";
                    }  else if ($featchselect["status_leave"]==2) {
                        echo "<font color=\"blue\" >".'อนุมัติจากหัวหน้า'."<font>";
                    }  else if ($featchselect["status_leave"]==-2) {
                        echo"<font color=\"red\" >".'ไม่อนุมัติจากหัวหน้า'."<font>";
                    }  else if ($featchselect["status_leave"]==3) {
                        echo "<font color=\"green\" >".'เสร็จสิน'."<font>";
                    }  else if ($featchselect["status_leave"]==-3) {
                        echo "<font color=\"red\" >".'ไม่อนุมัติการลาครั้งนี้'."<font>";
                    }else if ($featchselect["status_leave"]==0) {
                        echo "<font color=\"red\" >".'ยกเลิกการลากรณีพิเศษ'."<font>";
                    }
                    ?></td>

                <td ><input type="hidden" id=emp_id value="<?=$featchselect["Emp_id"]?>" >
                    <?php if($featchselect["type_id"]==1) {?>
                         <input type="hidden" id="<?=$id?>" value="<?=$featchselect["hoilday_detail_id"]?>"  ><input type="button" class="btn btn btn-primary " id="view" onclick="viewid(<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,type_id = <?=$featchselect["type_id"]?>)" value="VIEW" >
                   <?php  }else{?>
                            <input type="hidden" id="<?=$id?>" value="<?=$featchselect["hoilday_detail_id"]?>"  ><input type="button" class="btn btn btn-primary " id="view" onclick="viewid2(<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,type_id = <?=$featchselect["type_id"]?>)" value="VIEW" >
                   <?php } ?>
                   
              </td>
            </tr>
        <?php $numid++;}?>
         </tbody>
        <tfoot>
            <tr>
                <th>ลำดับ</th>
                <th>ชื่อ</th>
                <th>ตำแหน่ง</th>
                <th>ประเภทการลา</th>
                <th>วันที่ลงลา</th>
                <th>ตั้งแต่วันที่</th>
                <th>เวลา</th>
                <th>ถึงวันที่</th>
                <th>เวลา</th>
                <th>จำนวนวันลา</th>
                <th>สถานะ</th>
                <th >VIEW</th>
            </tr>
        </tfoot>
    </table>
         </div>
     </div> 
 </div>

</body>
<script src="../css_and_js/javascript/jquery-3.4.1.min.js"></script>
<script src="../css_and_js/js_datatable/js/jquery.dataTables.min.js" ></script>
<script src="../css_and_js/js_datatable/js/dataTables.bootstrap4.js" ></script>

</html>

<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );    
</script>

<script>
    function viewid(x,y,z){
  
         location.href="view_decription_history_admin.php?id="+x+"&&emp="+y+"&&type_id="+z; 
    }  
     function viewid2(x,y,z){
        location.href="view_decription_history_leave_admin.php?id="+x+"&&emp="+y+"&&type_id="+z; 
    }
</script>

</body>
</html>