<?php 
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include_once '../connect/connect.php';
include_once 'header_css.php';

$typeleave=$_GET["type"];
$statusleave=$_GET["status"];
//  $btn=$_GET["btn"];

if (isset($_GET["approve"])) {
    
if ($typeleave==1  and $statusleave==1) {
    $typeleave = "detail.type_id=1";
    $statusleave="detail.status_leave=1";
}else if($typeleave ==1 and $statusleave==-1) {
    $typeleave = "detail.type_id=1";
    $statusleave="detail.status_leave=-1";
}else if($typeleave==2 and $statusleave==1) {
    $typeleave = "detail.type_id=2";
    $statusleave="detail.status_leave=1";
}else if ($typeleave ==2 and $statusleave==-1) {
 $typeleave = "detail.type_id=2";
    $statusleave="detail.status_leave=-1";

}else if($typeleave==1 and $statusleave==2) {
    $typeleave = "detail.type_id=1";
    $statusleave="detail.status_leave=2";
}else if ($typeleave ==1 and $statusleave==-2) {
 $typeleave = "detail.type_id=1";
    $statusleave="detail.status_leave=-2";
}else if($typeleave==2 and $statusleave==2) {
    $typeleave = "detail.type_id=2";
    $statusleave="detail.status_leave=2";
}else if ($typeleave ==2 and $statusleave==-2) {
 $typeleave = "detail.type_id=2";
    $statusleave="detail.status_leave=-2";
}else if($typeleave==1 and $statusleave==3) {
    $typeleave = "detail.type_id=1";
    $statusleave="detail.status_leave=3";
}else if ($typeleave ==1 and $statusleave==-3) {
 $typeleave = "detail.type_id=1";
    $statusleave="detail.status_leave=-3";
}else if($typeleave==2 and $statusleave==3) {
    $typeleave = "detail.type_id=2";
    $statusleave="detail.status_leave=3";
}else if ($typeleave ==2 and $statusleave==-3) {
 $typeleave = "detail.type_id=2";
    $statusleave="detail.status_leave=-3";}

}

$select = "SELECT *,gtype.name AS gname,psgroup.name AS pname ,thoildiay.type_name FROM tbl_employment AS emp INNER JOIN tbl_holiday_detail as detail  ON emp.Emp_id = detail.Emp_id
INNER JOIN tbl_group_type AS gtype ON gtype.group_id=emp.group_id
INNER JOIN tbl_position_group As psgroup ON psgroup.Position_id = emp.Position_id 
INNER JOIN tbl_type_hoilday AS thoildiay ON thoildiay.type_id = detail.type_id
WHERE $statusleave AND  $typeleave " ;
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

<div class="container-fluid"  >
    <div class="row">
        <div class="col-md-12" >

  <div class="card-header" >
    <div class="form-row">
      
    
  <div>
<a class="col-md-1" href="#" onclick="index();"><img src="../icon/logo2.png" alt="" width="65px" height="65px"></a>
    </div>
  <div class="col-md-1.5" style="margin-top: 0.5%;">
   <a onclick="index()"><h3>หน้ารอการอนุมัติ</h3></a> 
  </div></div>

  </div>
  <div class="card-body">
    
            <table id="myTable"  class="table table-striped table-bordered" style="width: 100%;" >

        <thead>
            <tr>
                <th width="3%">ชื่อ</th>
                <th width="4%">ตำแหน่ง</th>
                <th width="3%">ประเภทการลา</th>
                <th width="2%">วันที่ลงลา</th>
                <th width="2%">ตั้งแต่วันที่</th>
                <th width="2%">เวลา</th>
                <th width="2%">ถึงวันที่</th>
                <th width="2%">เวลา</th>
                <th width="2%">จำนวนวันลา</th>
                <th width="3%" >สถานะ</th>
                <th width="1%">VIEW</th>
                <th width="5%">ระบบความคิดเห็นของผู้บังคับบัญชา</th>
                <th align="center" width="1%">อนุมัติ</th>
                <th align="center"  width="1%">ไม่อนุมัติ</th>
            </tr>
        </thead>
        <tbody>
                <?php $numid= 1;
                while ($featchselect=mysqli_fetch_array($select_query)) {
                $id="hd_id".$numid ;
                ?>
            <tr >
                <td ><?=$featchselect["hoilday_detail_name"]; ?></td>
            <!--     <td ><?=$featchselect["gname"]; ?></td> -->
                <td><?=$featchselect["pname"]; ?></td>
                <td  ><?php 
                if($featchselect["type_id"]==1){
                    echo $featchselect["type_name"];
                ; }else{
                   echo $featchselect["sick_type"];
                }?></td>
                <td><?=date("d-m-y", strtotime($featchselect["register_date"])); ?></td>
                <td  ><?php 
                if($_GET["type"]==1){
                echo date("d-m-y", strtotime($featchselect["holiday_startdate"]));}else {
                echo date("d-m-y", strtotime($featchselect["sick_startdate"]));    
                } ?></td>
                <td ><?php 
                if ($_GET["type"]==1) {
                    echo $featchselect["holiday_startime"];  
                }else {
                    echo $featchselect["sick_startime"]; 
                }
                ?></td>
                <td ><?php
                if($_GET["type"]==1){
                echo date("d-m-y", strtotime($featchselect["holiday_enddate"])); 
                    }else{
                        echo date("d-m-y", strtotime($featchselect["sick_enddate"])); 
                    }
                ?></td>
                <td ><?php 
                if($_GET["type"]==1){
                echo $featchselect["holiday_endtime"]; 
                }else{
                          echo $featchselect["sick_endtime"]; 
                }
                ?></td>
                <td  ><?php 
                if($_GET["type"]==1){
                
             echo $featchselect["hoilday_totalday"]+$featchselect["holiday_totalhour"];
            }else{
              echo  $featchselect["sick_totalday"]+$featchselect["sick_totalhour"];
            }
             ?></td>
                <td ><?php 
                if($featchselect["status_leave"]==1){
                    echo 'ส่งเรื่องการลา';} 
                    else if ($featchselect["status_leave"]==-1) {
                        echo 'ยกเลิกการลา';
                    }  else if ($featchselect["status_leave"]==2) {
                        echo 'อนุมัติจากหัวหน้า';
                    }  else if ($featchselect["status_leave"]==-2) {
                        echo 'ไม่อนุมัติจากหัวหน้า';
                    }  else if ($featchselect["status_leave"]==3) {
                        echo 'เสร็จสิน';
                    }  else if ($featchselect["status_leave"]==-3) {
                        echo 'ไม่อนุมัติการลาครั้งนี้';
                    }
                    ?></td>

          <td ><input type="hidden" id=emp_id value="<?=$featchselect["Emp_id"]?>" >
                    <?php if($featchselect["type_id"]==1) {?>
                         <input type="hidden" id="<?=$id?>" value="<?=$featchselect["hoilday_detail_id"]?>"  ><input type="button" class="btn btn btn-primary " id="view" onclick="viewid(<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,<?=$featchselect["type_id"]?>)" value="VIEW" >
                   <?php  }else{?>
                            <input type="hidden" id="<?=$id?>" value="<?=$featchselect["hoilday_detail_id"]?>"  ><input type="button" class="btn btn btn-primary " id="view" onclick="viewid2(<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,
                            type_id = <?=$featchselect["type_id"]?>)" value="VIEW" >
                   <?php } ?>
                   
              </td>
             <td>
               <textarea  id="comment_command<?=$numid?>" name="comment_command<?=$numid?>" style="width: 100%;"></textarea>
             </td> 
              <?php if($featchselect["status_leave"]==1){?>
            <td align="center"><i class="fas fa-check" style="color: green;" onclick="update1(<?=$numid?>,<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,<?=$featchselect["type_id"]?>)" ></i></td>
<?php }if($featchselect["status_leave"]==2){ ?>
            <td align="center"><i class="fas fa-check" style="color: green;" onclick="update(<?=$numid?>,<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,<?=$featchselect["type_id"]?>)" ></i></td>
<?php }?>

<?php if($featchselect["status_leave"]==1){?>

          <td align="center"><i class="fas fa-ban"style="color: red;" onclick="del1(<?=$numid?>,<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,<?=$featchselect["type_id"]?>)"></i></td>
<?php } if($featchselect["status_leave"]==2){?>
            <td align="center"><i class="fas fa-ban"style="color: red;" onclick="del(<?=$numid?>,<?=$featchselect["hoilday_detail_id"]?>,<?=$featchselect["Emp_id"]?>,<?=$featchselect["type_id"]?>)"></i></td>
<?php }?>
            </tr>
        <?php $numid++;}?>
         </tbody>
       
    </table>
  </div>
</div>
    </div>
    </div>
</div>
<div id="list-data1">
  
</div>
</body>


</html>

<script>
$(document).ready( function () {
    $('#myTable').DataTable();
} );    
</script>
<script>
    function viewid(x,y,z){
  
         location.href="view_decription_admin.php?id="+x+"&&emp="+y+"&&type_id="+z; 
    }  
     function viewid2(x,y,z){
        location.href="view_decription_leave_admin.php?id="+x+"&&emp="+y+"&&type_id="+z; 
    }
  

 function del1(count,x,y,z){

        swal({
  title: "คุณต้องการยกเลิกใช่หรือไม่?",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
   var num = "#comment_command"+count;

  if (willDelete) {
           
  if ( $(num).val()!="") {
      // statement
   
     jQuery.ajax({
  url: 'delete_admin.php?cancle=CANCLE&&id='+x+'&&emp='+y+'&&type_id='+z,
  type: 'POST',
 data: {param1: $(num).val()},
  complete: function(xhr, textStatus) {
    //called when complete
  },
  success: function(data, textStatus, xhr) {
        $("#list-data1").html(data);
  },
  error: function(xhr, textStatus, errorThrown) {
    //called when there is an error
  }
});
  
 }else {
   alert("โปรดระบุความเห็น");
   return false;
 }
  } 
});

    }

    



function del(count,x,y,z){

        swal({
  title: "คุณต้องการยกเลิกใช่หรือไม่?",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
         var c="cancel_sus";
 var num = "#comment_command"+count;
  if ( $(num).val()!="") {
      // statement
   
     jQuery.ajax({
  url: 'delete_admin.php?id='+x+'&&emp='+y+'&&type_id='+z+'&&cancel_sus='+c,
  type: 'POST',
  data: {param1: $(num).val()},
  complete: function(xhr, textStatus) {
    //called when complete
  },
  success: function(data, textStatus, xhr) {
        $("#list-data1").html(data);
  },
  error: function(xhr, textStatus, errorThrown) {
    //called when there is an error
  }
});
  
 }else {
   alert("โปรดระบุความเห็น");
   return false;
 }
});

    }

function update (count,x,y,z){
  var num = "#comment_command"+count;
        swal({
  title: "คุณต้องการยืนยันใบลานี้ใช่หรือไม่?",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
  if ( $(num).val()!="") {
      // statement
   
     jQuery.ajax({
  url: 'update_status_sus.php?id='+x+'&&emp='+y+'&&type_id='+z+'&&confrimsecon',
  type: 'POST',
  data: {param1: $(num).val()},
  complete: function(xhr, textStatus) {
    //called when complete
  },
  success: function(data, textStatus, xhr) {
        $("#list-data1").html(data);
  },
  error: function(xhr, textStatus, errorThrown) {
    //called when there is an error
  }
});
  
 }else {
   alert("โปรดระบุความเห็น");
   return false;
 }
  } 
});


}
function update1 (count,x,y,z){

var num = "#comment_command"+count;


        swal({
  title: "คุณต้องการยืนยันใบลานี้ใช่หรือไม่?",
  icon: "warning",
  buttons: true,
  dangerMode: true,
})
.then((willDelete) => {
  if (willDelete) {
    if ( $(num).val()!="") {
      // statement
   
     jQuery.ajax({
  url: 'update_status_sus.php?id='+x+'&&emp='+y+'&&type_id='+z+'&&confrim',
  type: 'POST',
  data: {param1: $(num).val()},
  complete: function(xhr, textStatus) {
    //called when complete
  },
  success: function(data, textStatus, xhr) {
        $("#list-data1").html(data);
  },
  error: function(xhr, textStatus, errorThrown) {
    //called when there is an error
  }
});
  
 }else {
   alert("โปรดระบุความเห็น");
   return false;
 }
  } 
});


}
</script>

<script type="text/javascript">
  function index(){
 location.href="index_admin.php";
  }
</script>
