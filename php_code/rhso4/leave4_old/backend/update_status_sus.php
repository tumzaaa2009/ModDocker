<?php 
 if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include_once '../connect/connect.php';
// echo $_POST["param1"];
//ยืนยัน status 3
    if (isset($_GET["confrimsecon"])) {
    	

    $approve_by= $_SESSION["name"]." ".$_SESSION["lastname"];
     $update = "Update tbl_holiday_detail set status_leave =3,approve_sussecc='".$approve_by."',dateapprove_sussecc=NOW(),approve_section_seccess='".$_SESSION["secname"]."',approve_comment_susecc='".$_POST['param1']."'
      where Emp_id='".$_GET["emp"]."' and hoilday_detail_id='".$_GET["id"]."' and type_id='".$_GET["type_id"]."'";
     mysqli_query($objconnect,$update);

    }    if (isset($_GET["confrim"])) {
    	

    $approve_by= $_SESSION["name"]." ".$_SESSION["lastname"];
     $update = "Update tbl_holiday_detail set status_leave =2,approve_by='".$approve_by."',dateapprove_by=NOW(),approve_section_by='".$_SESSION["secname"]."',approve_comment='".$_POST['param1']."'
      where Emp_id='".$_GET["emp"]."' and hoilday_detail_id='".$_GET["id"]."' and type_id='".$_GET["type_id"]."'";
     mysqli_query($objconnect,$update);

    }
?>
<script type="text/javascript">
	alert("ยืนยันการลาเรียบร้อย");
	location.href='index_admin.php';
</script>