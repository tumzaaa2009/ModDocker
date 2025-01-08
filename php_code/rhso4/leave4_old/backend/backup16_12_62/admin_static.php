<?php 
  if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
include_once '../connect/connect.php';
include_once 'header_css.php';
?>
<!DOCTYPE html>
<html>

<body>
<div class="container"> 
<div class="row">
	<div class="col-12" >
<div class="card"id="static_type">
  <div class="card-header" >สถิติการลา</div>
  <div class="card-body">

<!--   <label class="my-1 mr-2" for="inlineFormCustomSelectPref">ระบุรายชื่อพนักงาน</label>
 --><!--   <select class="custom-select my-1 mr-sm-2 col-2" id="inlineFormCustomSelectPref">
 <?php $select_emp="SELECT Emp_id,Titile_name,Emp_name,Emp_lastname FROM tbl_employment";
 	$query_emp  =mysqli_query($objconnect,$select_emp);	
 	 $num_emp=mysqli_num_rows($query_emp);?>  

    <option value="0">-เลือกทั้งหมด-</option>
<?php while ($featch_emp=mysqli_fetch_array($query_emp)) {
   echo "<option value=\"'".$featch_emp["Emp_id"]."'\">".$featch_emp["Titile_name"]." ".$featch_emp["Emp_name"]." ".$featch_emp["Emp_lastname"]."</option>";

 }?>
  </select>
 -->

<label style="text-align: center" class="my-1 mr-2 col-3"  for="inlineFormCustomSelectPref">ระบุประเภทการลา</label>
      <select class="custom-select my-1 mr-sm-2 col-2" id="type">
    <option value="0">-ระบุการลา-</option>
	<option value="1">ลาพักร้อน</option>
	<option value="2">ลาป่วย/ลากิจ/ลาคลอดบุตร</option>
  </select>


  <button type="submit" class="btn btn-primary my-1 col-2" id="search" ><span data-feather="search"></span> ค้นหา</button>

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
	$( "#search" ).click(function() {
if ($("#type").val()==0) {
	alert("กรุณาระบุประเภทการลา");
	return false;
}else{
 jQuery.ajax({
   url: 'admin_static_summary.php',
   type: 'POST',
   data: {type: $("#type").val()},
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
 }
});
</script>
