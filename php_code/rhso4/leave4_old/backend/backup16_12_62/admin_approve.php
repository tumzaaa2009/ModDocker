<?php 
include_once '../connect/connect.php';
include_once 'header_css.php';

?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="../css_and_js/fontawesome-free-5.10.1-web/css/all.min.css">
<script type="text/javascript" src="../css_and_js/fontawesome-free-5.10.1-web/js/all.min.js"></script>

<body>
<div class="container-fluid" style="width: 100%;">
	<div class="row">
		<div class="col-12" >
			<div class="card">
  <div class="card-header">รอการอนุมัติใบลา</div>
  <div class="card-body">
  		<div class="row" > 
			<div class="col-3">
				<?php $select_type="SELECT * FROM tbl_type_hoilday" ;
				$query_type = mysqli_query($objconnect,$select_type);
				$num =mysqli_num_rows($query_type);
				 ?>
				<p>ประเภทใบลา</p>
				<select name="leave_type" id="leave_type"  >
					<option value="0">--ระบุประเภทการลา--</option>
				<?php 
				while ($featch_type=mysqli_fetch_array($query_type)) {?>	
					<option value="<?=$featch_type["type_id"] ;?> " ><?=$featch_type["type_name"]?></option>
				<?php }?>
				</select>
			</div>
			<div class="col-3" >
				
				<p>สถานะใบลา</p>
				<select name="status_leave" id="status_leave">
					<option value="0">--ระบุสถานะใบลา--</option>
					<option value="1">ส่งเรื่องการลา</option>
					<option value="2">รอการอนุมัติจากหมอ</option>
				</select>
			</div>
			<div class="col-3" >
				<P>กดเพื่อยืนยัน</P>
				<button type="button" id="btnsearch_1" value="1" style="width: 50%;"><i class="fas fa-search"></i> ค้นหา</button>
			</div>
	
	<div class="col-3"></div>
  		</div>
	</div>


		</div>
	</div>
</div>
</div>
<!-- ENDCOUNTENINER -->
<hr>
<div class="main" >
	<div class="container-fluid">
		<div class="row">
			<div class="col-12" >
				<div id="list-data1"> </div>
			</div>
		</div>
	</div>
</div>



</body>
</html>

<script>
  $(document).ready(function() {
$(function () {

 $("#btnsearch_1").click(function () { 

if ($("#leave_type").val()==0 || $("#status_leave").val()==0 ) {
alert("เช็คการค้นหาใหม่อีกครั้ง");
return false;
}
else{

    jQuery.ajax({
      url: 'show_approve.php',
      type: 'POST',
      data: {type:$("#leave_type").val(),status:$("#status_leave").val(),btn:$("#btnsearch_1").val()},
      complete: function(xhr, textStatus) {
        $(".loading").hide();
      },
      success: function(data, textStatus, xhr) {
        $("#list-data1").html(data);
      },
      error: function(xhr, textStatus, errorThrown) {
    
      }
    })};
    
   })        

});

});
</script>

