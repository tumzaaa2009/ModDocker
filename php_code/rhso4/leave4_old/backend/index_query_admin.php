<!DOCTYPE html>
<html>

<head>
    <?php include_once '../connect/connect.php';
include_once 'header_css.php';
 
 $select_fiscalyear = "SELECT Financial_year FROM tbl_total_limit_holiday GROUP BY Financial_year";
 $query_fiscalyear = mysqli_query($objconnect,$select_fiscalyear);

 ?>
</head>

<!-- UPDATE-STATUS -->
<? 
if (isset($_POST['submit'])) {
//อัพเดทสถานะพนักงาน
$update_editemp_nameorpassword	 ="UPDATE tbl_userlogin
SET user_pass = '".sha1($_POST['user_pass'])."',user_pass_detail = '".$_POST['user_pass']."'
WHERE Emp_id = '".$_POST['id_emp']."'";
mysqli_query($objconnect,$update_editemp_nameorpassword);

$update_emp_limitholiday  = "UPDATE tbl_total_limit_holiday
SET fix_count_oldyear_holiday='".$_POST['count_old']."', count_year_holiday=10,sum_thisyear_holiday = '".$_POST['sum_limit']."' 
,Financial_year='".$_POST['Financial_year']."'
,start_holiday='".$_POST['Start_Financial_year']."'
,end_holiday ='".$_POST['End_Financial_year']."'
WHERE Emp_id = '".$_POST['id_emp']."'";
mysqli_query($objconnect,$update_emp_limitholiday);
// echo $update_emp_limitholiday;
// // status_emp
$update_status_emp	 ="UPDATE tbl_employment
SET Emp_Status = '".$_POST['value_type']."'
WHERE Emp_id = '".$_POST['id_emp']."'";
mysqli_query($objconnect,$update_status_emp);



}



 ?>

<body>
    <div class="container-fluid">
        <div class="col-12" align="center">
            <h1>แก้ไขปรับปรุงวันลา/และยกเลิกพนักงาน</h1>
        </div>
        <form method="post">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">ประเภทพนักงาน</label>
                        <select class="form-control" name="select_type">
                            <option value="0">ทั้งหมด</option>
                            <option value="1">ข้าราชการ</option>
                            <option value="2">พนักงานราชการ</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="">ปีงบประมาณ</label>
                        <select class="form-control" name="select_fiscal">
                            <option value="0">ทั้งหมด</option>
                            <?php while ($row_fical = mysqli_fetch_array($query_fiscalyear)) { ?>
                            <option value="<?php echo $row_fical['Financial_year'] ?>">
                                <?php echo $row_fical['Financial_year'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="form-group">
                        <label class="mt-3"></label>
                        <button type="submit" class="btn btn-primary form-control" name="filter_type">ยืนยัน</button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row">
            <?php 
			if(isset($_POST['filter_type'])){
				$filter_type = '';
				if($_POST['select_type']==0){
				$filter_type = '';
					}else if ($_POST['select_type']==1){
				$filter_type = 'WHERE emp.group_id = 1';	
					}else {
				$filter_type = 'WHERE emp.group_id = 2';	
				}
				$filter_fiscal = '' ;
				if($_POST['select_fiscal']==0){
					$filter_fiscal = '' ;
				}else {
					 $filter_fiscal = "AND limithol.Financial_year='".$_POST['select_fiscal']."' " ;
				}
			}
 $select_emp = "SELECT emp.emp_Status,emp.group_id,limithol.Financial_year,emp.Emp_id,gtype.name,emp.Emp_name,emp.Emp_lastname
  ,login.user_name,login.user_pass_detail,limithol.fix_count_oldyear_holiday
  ,limithol.count_year_holiday,limithol.sum_thisyear_holiday FROM tbl_employment emp 
  INNER JOIN tbl_total_limit_holiday limithol ON emp.Emp_id = limithol.Emp_id 
  INNER JOIN tbl_userlogin login on login.Emp_id=emp.Emp_id 
  INNER JOIN tbl_group_type gtype ON gtype.group_id=emp.group_id
     $filter_type  $filter_fiscal  where 
limithol.Financial_year IS NULL and limithol.status_limit='1' GROUP BY emp.Emp_id   ORDER BY limithol.Financial_year ASC ,emp.Emp_Status DESC";
 $query_empedit = mysqli_query($objconnect,$select_emp);
 $num =mysqli_num_rows($query_empedit);

 ?>
            <div class="col-12 ">
                <table class="table ">
                    <thead>
                        <tr>
                            <th>อันดับ</th>
                            <th>ประเภท</th>
                            <th colspan='2'> ชื่อ-นามสกุล</th>
                            <th>ชื่อผู้ใช้</th>
                            <th width="10px;">รหัสผู้ใช้</th>
                            <th>ยอดยกมา</th>
                            <th>ปีงบประมาณใหม่</th>
                            <th>รวม</th>
                            <th>คงเหลือ</th>
                            <th>สถานะพนักงาน</th>
                            <th>ปีงบประมาณ</th>
                            <th>วันเริ่มต้นปีงบ</th>
                            <th>วันเริ่มต้นปีงบ</th>
                            <th>ยืนยัน</th>
                        </tr>
                    </thead>
                    <tbody>

                        <? $count= 0;  
			while ($row_empedit = mysqli_fetch_array($query_empedit) ) {
    $count++;
    $sum_limit = $row_empedit["fix_count_oldyear_holiday"]+$row_empedit["count_year_holiday"];
?>
                        <form method="POST">
                            <tr align="center">
                                <input type="hidden" name="id_emp" value="<?= $row_empedit["Emp_id"]?>" placeholder="">
                                <td>
                                    <?=$count?>
                                </td>
                                <td><?=$row_empedit["name"]?>
                                </td>
                                <td colspan="2" style="width: 150px;">
                                    <?= $row_empedit["Emp_name"]." ".$row_empedit["Emp_lastname"];?>
                                </td>
                                <td><?=$row_empedit["user_name"]?>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="user_pass"
                                        value="<?=$row_empedit["user_pass_detail"]?>" style="width: 150px;">
                                </td>
                                <td><input type="text" class="form-control" id="count_old<?=$count?>" name="count_old"
                                        onchange="sum(<?=$count?>)"
                                        value="<?=$row_empedit["fix_count_oldyear_holiday"]?>">
                                </td>
                                <td> <input type="text" class="form-control" name="count_year"
                                        id="count_year<?=$count?>" onchange="sum(<?=$count?>)"
                                        value="<?=$row_empedit["count_year_holiday"]?>" >
                                </td>
                                <td><input type="text" name="sum_limit" readonly="true" id="sum_limit<?=$count?>"
                                        class="form-control" value="<?=$sum_limit?>">
                                </td>
                                <td>
                                    <input type="text" name="sum_limit" readonly="true" id="sum_limit<?=$count?>"
                                        class="form-control" readonly  value="0">
                                </td>
                                <td>
                                    <div class="form-group" align="center">
                                        <select class="form-control" name="value_type" style="width: 120px;"
                                            id="exampleFormControlSelect<?=$count?>">

                                            <option value="1"
                                                <?php if ($row_empedit["emp_Status"]==1){echo "selected" ;} ?>>ปฏิบัตงาน
                                            </option>
                                            <option value="-1"
                                                <?php if ($row_empedit["emp_Status"]==-1){echo "selected" ;} ?>>ลาออก
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="Financial_year" class="form-control"
                                        value="<?=$row_empedit['Financial_year']?>">
                                </td>
                                <td>
                                    <input type="text" name="Start_Financial_year" class="form-control" value="20201001">
                                </td>
                                <td>
                                    <input type="text" name="End_Financial_year" class="form-control" value="20210930">
                                </td>
                                <td><input type="submit" name="submit" value="SUBMIT" class="btn btn-primary">
                                </td>
                            </tr>
                        </form>
                        <? }?>
                    </tbody>
                </table>
            </div>


        </div>


    </div>







</body>

</html>
<script>
function sum(num) {
    let count_old = document.getElementById("count_old" + num).value;
    let count_year = document.getElementById("count_year" + num).value;

    let sum = parseFloat(count_old) + parseFloat(count_year);
    document.getElementById("sum_limit" + num).value = sum;
}
</script>