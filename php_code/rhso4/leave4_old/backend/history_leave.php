<?php
include_once '../connect/connect.php';
include_once 'header_css.php';

 ?>
 <!DOCTYPE html>
 <html>
 <body>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
				<div class="main" >
					<div class="card text-center">
							<div class="card-header">Featured</div>
								<div class="card-body">
								  <div class="row">
								 
				
					<!-- 					<div class="col-1">
											  <label >รายชื่อพนักงาน</label>
										</div>
										<div class="col-2" >
										
							<?php
							$empname ="SELECT * FROM tbl_employment";
							$queryempname= mysqli_query($objconnect,$empname);
							$num= mysqli_num_rows($queryempname);

							 ?>			  
										<select name="empname_search" id="empname_search" >
											<option value="0" selected="true">----โปรดระบุพนักงาน----</option>
	<?php  while ($featchempname=mysqli_fetch_array($queryempname)) {?><option value="<?=$featchempname["Emp_id"]?>"><?=$featchempname["Emp_name"]."   ".$featchempname["Emp_lastname"]?></option><?php }?>			
										</select>
										</div> -->
	<div class="col-1">
		<label for="inputEmail4">ประเภทการลา</label>
	</div>
							<div class="col-2">
										  
							<?php
							$type_holiday ="SELECT * FROM tbl_type_hoilday";
							$query_typeholiday= mysqli_query($objconnect,$type_holiday);
							$num= mysqli_num_rows($query_typeholiday);

							 ?>			  
										<select name="type_leave" id="type_leave" >
											<option value="0" selected="true">----ประเภทการลา----</option>
	<?php  while ($featch_typeleave=mysqli_fetch_array($query_typeholiday)) {?><option value="<?=$featch_typeleave["type_id"]?>"><?=$featch_typeleave["type_name"]?></option><?php }?>			
										</select>

									
							</div>	
<div class="1">
<label>สถานะการลา</label>
</div>
			<div class="col-2" >
				
	   					  <select  id="statusleave">
                                        <option value="0">------เลือกสถานะ------</option>
                                        <option value="1">1.ส่งเรื่องการลา</option>
                                        <option value="-1">2.ยกเลิกการลา</option>
                                        <option value="2">3.อนุมัติจากหัวหน้า</option>
                                        <option value="-2">4.ไม่อนุมัตจากหัวหน้า</option>
                                        <option value="3">5.เสร็จสิน</option>
                                        <option value="-3">6.ไม่อนุมัติการลานี้</option>
                            </select>

									
							</div>									
							<div class="col-2" >
									
							<button type="button" id="btnsearch_1"  value="1" style="width: 100%;"><i class="fas fa-search"></i> ค้นหา</button>
							 </div>			
										


						</div>
				

					</div>
					
				</div>	
		</div>
	</div>
</div>
</div>

<div class="container-fluid">
	<div class="form-row">
		<div class="col-12" style="border: solid;width: 100%;height: 100%">
			 		<div id="list-data1"></div>
			
		</div>
		
	</div>
	
</div>


 </body>
 </html>

<script >
	 
 $("#btnsearch_1").click(function () { 
 if ( $("#type_leave").val()==0 || $("#statusleave").val()==0  ) {
alert("เช็คการค้นหาใหม่อีกครั้ง");
return false;
}else{
    jQuery.ajax({
      url: 'show_history_admin.php',
      type: 'POST',
      data: {typeleave:$("#type_leave").val(),statusleave:$("#statusleave").val(),btn:$("#btnsearch_1").val()},
      complete: function(xhr, textStatus) {
        $(".loading").hide();
      },
      success: function(data, textStatus, xhr) {
        $("#list-data1").html(data);
      },
      error: function(xhr, textStatus, errorThrown) {
    
      }
  });
    
 }
    })     

</script>

