<?php
	include '../check_session_login.php';
	//$report_type = $_POST['report_type'];
	$report_type = "pdf";
	if ($report_type == "pdf") 
	{
  		require_once('../mpdf/mpdf.php');
  		ob_start(); //ทำการเก็บค่า html
	}

?>



<meta charset="utf-8" />
<table cellspacing="0" cellpadding="3" width="100%">
<tr>
	<td align="center" style="font-family: THSaraban; font-size: 20px">
		แบบใบลาพักผ่อน
	</td>
</tr>
<tr>
	<td style="height: 20px">
		
	</td>
</tr>
<tr>
	<td align="right">
		เขียน......ที่สำนักงานเขตสุขภาพที่ 4.....
	</td>
</tr>
<tr>
	<td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่........................................................
	</td>
</tr>
<tr>
	<td style="height: 50px"></td>
</tr>
<tr>
	<td>
		เรื่อง&nbsp;&nbsp;&nbsp;ขอลาพักผ่อน
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		เรียน&nbsp;&nbsp;ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้าพเจ้า.....................................................................ตำแหน่ง.........................................................................
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		สังกัด...............................................................................................................................................................................
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		มีวันลาพักผ่อนสะสม.............วันทำการ มีสิทธิลาพักผ่อนประจำปีนี้อีก 10 วันทำการ รวมเป็น ....... วัน
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		ขอลาพักผ่อนตั้งแต่วันที่....................................ถึงวันที่...................................... มีกำหนด...............วัน
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		ในระหว่างลาจะติดต่อข้าพเจ้าได้ที่..................................................................................................................................................
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		......................................................................................................................................................................................................
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		ในระหว่างลานี้ ได้มอบหมายให้ .....................................................................................................................................................
	</td>
</tr>
<tr>
	<td style="height: 30px">
	</td>
</tr>
<tr>
	<td align="center">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ขอแสดงความนับถือ
	</td>
</tr>
<tr>
	<td style="height: 30px">
	</td>
</tr>
<tr>
	<td align="center">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td align="center">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(........................................................)
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td align="center">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง..........................................................
	</td>
</tr>
<tr>
	<td style="height: 30px">
	</td>
</tr>
<tr>
	<td align="center" style="font-size: 20px">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>ความเห็นผู้บังคับบัญชา
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>
<tr>
	<td>
		<table>
			<tr>
				<td style="font-size: 18px">
					<u>สถิติการลาในปีงบประมาณนี้</u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;............................................................
				</td>
				
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td style="height: 10px">
	</td>
</tr>

</table>

<table border="1" cellspacing="0">
	<tr>
		<td>
			ลามาแล้ว<br />(วันทำการ)
		</td>
		<td>
			ลาครั้งนี้<br />(วันทำการ)
		</td>
		<td>
			รวมเป็น<br />(วันทำการ)
		</td>
	</tr>
	<tr>
		<td style="height: 30px">
			
		</td>
		<td style="height: 30px">
			
		</td>
		<td style="height: 30px">
			
		</td>
	</tr>
</table>


<?Php
if ($report_type == "pdf") {
  $html = ob_get_contents();
  ob_end_clean();
  $pdf = new mPDF('th', 'A4', '0', ''); //การตั้งค่ากระดาษถ้าต้องการแนวตั้ง ก็ A4 เฉยๆครับ ถ้าต้องการแนวนอนเท่ากับ A4-L
  $pdf->SetAutoFont();
  $pdf->SetDisplayMode('fullpage');
  $pdf->WriteHTML($html, 2);
  $pdf->Output();
}
?>