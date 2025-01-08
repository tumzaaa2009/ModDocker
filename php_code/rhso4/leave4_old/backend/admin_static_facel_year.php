<?php
if (!isset($_SESSION)) {
  session_start();
}
include_once '../connect/connect.php';
include_once 'header_css.php';
$select_fiscalyear = "SELECT Financial_year FROM tbl_total_limit_holiday GROUP BY Financial_year";
$query_fiscalyear = mysqli_query($objconnect,$select_fiscalyear);
?>
<!DOCTYPE html>
<html>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" id="static_type">
                    <div class="card-header">รวมสถิติปีงบ</div>
                    <div class="card-body">

                        <!--   <label class="my-1 mr-2" for="inlineFormCustomSelectPref">ระบุรายชื่อพนักงาน</label>
 -->
                        <!--   <select class="custom-select my-1 mr-sm-2 col-2" id="inlineFormCustomSelectPref">
 <?php $select_emp = "SELECT Emp_id,Titile_name,Emp_name,Emp_lastname FROM tbl_employment";
  $query_emp  = mysqli_query($objconnect, $select_emp);
  $num_emp = mysqli_num_rows($query_emp); ?>  

    <option value="0">-เลือกทั้งหมด-</option>
<?php while ($featch_emp = mysqli_fetch_array($query_emp)) {
  echo "<option value=\"'" . $featch_emp["Emp_id"] . "'\">" . $featch_emp["Titile_name"] . " " . $featch_emp["Emp_name"] . " " . $featch_emp["Emp_lastname"] . "</option>";
} ?>
  </select>
 -->

                        <select class="custom-select my-1 mr-sm-2 col-2" id="type">
                            <option value="0">-ประเภทพนักงาน-</option>
                            <option value="1">ข้าราชการ</option>
                            <option value="2">พนักงานราชการ</option>
                        </select>
                        <select class="custom-select my-1 mr-sm-2 col-2" id="type_Financial_year">
                        <?php
                             while ($row_fical = mysqli_fetch_array($query_fiscalyear)) { ?>
                            <option value="<?php echo $row_fical['Financial_year'] ?>">
                                <?php echo $row_fical['Financial_year'] ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="btn btn-primary my-1 col-2" id="search"><span
                                data-feather="search"></span> ค้นหา </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="list-data1"></div>
            </div>
        </div>
    </div>

</body>

</html>
<script type="text/javascript">
$("#search").click(function() {
    const fs_y = $("#type_Financial_year").val();
    if ($("#type").val() == 0 || $("#type_Financial_year").val() == 0) {
        alert('ตรวจสอบก่อนค้นหาครับ');
        return false;
    } else {
        if ($("#type").val() == 1) {
            location.href = "admin_facelyear.php?fs_y=" + fs_y;
        } else {
            location.href = "admin_facelyear_type2.php?fs_y=" + fs_y;
        }
    }
});
</script>