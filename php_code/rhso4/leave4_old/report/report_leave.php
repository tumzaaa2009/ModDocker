<?php
	require('../fpdf/write_html.php');
	include '../class/class_db.php';
	//include '../class/class_utility.php';

	session_start();
	//ปีงบประมาณ
	$fiscal_year = $_SESSION['fiscal_year'];

	class PDF extends FPDF {
	}

		//echo $_GET['leaveid'] . "<br>";

		//echo $_POST['emp_name'] . "<br>";


		 $conn = new class_db();
          $n = array(
            'leave_list_id' => $_GET['leaveid']
          );
          
          	$sql = " select ll.*,ld.*,CONCAT(t.title_name,' ',CONCAT(emp.emp_name,' ',emp.emp_lastname)) emp_name, emp_position_name ";

          	$sql .= " ,(select ld2.leave_start from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_start ";

			$sql .=" ,(select ld2.leave_end from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_end ";

			$sql .=" ,(select ld2.leave_total from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_total ";

			$sql .=" ,(select ld2.lea_type_id from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_leave_type ";

			//SUM
			$sql .=" ,(select SUM(A.leave_total) Total_Holiday from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			//$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " where ld3.leave_start >= REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 0 YEAR),'-','') and ld3.leave_end < REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 1 YEAR),'-','') ";

			$sql .= " and ld3.lea_type_id = '1' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Holiday ";


			$sql .= " ,(select SUM(A.leave_total) Total_Leave from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			//$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " where ld3.leave_start >= REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 0 YEAR),'-','') and ld3.leave_end < REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 1 YEAR),'-','') ";

			$sql .= " and ld3.lea_type_id = '2' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Leave ";


			$sql .= ",(select SUM(A.leave_total) Total_Errand from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			//$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " where ld3.leave_start >= REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 0 YEAR),'-','') and ld3.leave_end < REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 1 YEAR),'-','') ";

			$sql .= " and ld3.lea_type_id = '3' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Errand ";


			$sql .= " ,(select COALESCE(SUM(A.leave_total),'') Total_Manternity from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			//$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " where ld3.leave_start >= REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 0 YEAR),'-','') and ld3.leave_end < REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 1 YEAR),'-','') ";

			$sql .= " and ld3.lea_type_id = '4' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Manternity ";

          	
          	$sql .= " from leave_list ll , leave_detail ld , employee emp , title_master t";
			$sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_list_id = :leave_list_id ";
			$sql .= " and ll.leave_emp_id = emp.emp_id ";
			$sql .= " and emp.title_id = t.id ";

          $values = $conn->getOne($sql, $n);
          $conn = null;

 		  //print_r($sql);
          if(isset($values))
          {
          	

          	

		// Instanciation of inherited class
		$pdf = new PDF_HTML();
		//$pdf = new PDF('P','mm','A4');
		$pdf->AliasNbPages();
		$pdf->AddPage();

		$pdf->AddFont('THSarabunb', '', 'THSarabun Bold.php');
		$pdf->SetFont('THSarabunb', '', 20);//18
		
		$pdf->Cell(55);
		$pdf->Cell(0,20,iconv('UTF-8', 'TIS-620', 'แบบใบลาป่วย ลาคลอดบุตร ลากิจส่วนตัว'));



		$pdf->Ln(11);
		$pdf->Cell(115);
		$pdf->AddFont('THSarabun', '', 'THSarabun.php');
		$pdf->SetFont('THSarabun', '', 16);
		$pdf->Cell(23,20,iconv('UTF-8', 'TIS-620', 'เขียนที่.....................................................................'));
		$pdf->Cell(50, 18, iconv('UTF-8', 'TIS-620', 'ที่สำนักงานเขตสุขภาพที่ 4'));
		
		$pdf->Ln(8);
		$pdf->Cell(113);
		$pdf->Cell(23,20,iconv('UTF-8', 'TIS-620', 'วันที่...........................................................................'));
		$pdf->Cell(50, 18, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['leave_date'])));


		$pdf->Ln(12);
		$pdf->Cell(5);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'เรื่อง    ขออนุญาติ'));

		$pdf->Ln(10);
		$pdf->Cell(5);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'เรียน    ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4'));

		$pdf->Ln(10);
		$pdf->Cell(25);
		$pdf->Cell(25, 15, iconv('UTF-8', 'TIS-620', 'ข้าพเจ้า........................................................................ตำแหน่ง.................................................................................'));
		$pdf->Cell(70, 14, iconv('UTF-8', 'TIS-620', $values['emp_name']));
		$pdf->Cell(40, 14, iconv('UTF-8', 'TIS-620', $values['emp_position_name']));

		$pdf->Ln(8);
		$pdf->Cell(5);
		
		if("ข้าราชการ" == "ข้าราชการ")
		{
			$pdf->Cell(30, 10, iconv('UTF-8', 'TIS-620', '( / ) ข้าราชการ'));
		}
		
		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', '(  ) ลูกจ้างประจำ'));
		$pdf->Cell(38, 10, iconv('UTF-8', 'TIS-620', '(  ) ลูกจ้างชั่วคราว'));
		$pdf->Cell(40, 10, iconv('UTF-8', 'TIS-620', '(  ) พนักงานของรัฐ'));

		$pdf->Ln(8);
		$pdf->Cell(35);

		//check box
		$pdf->Line(37, 80, 41, 80);//เส้นขอบแนวนอนบน
		$pdf->Line(37, 84, 41, 84);//เส้นขอบแนวนอนล่าง

		$pdf->Line(37, 80, 37, 84);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(41, 80, 41, 84);//เส้นขอบแนวตั้งขวา

		//ป่วย
		if($values['leave_type'] == '2')
		{
			$pdf->Line(37, 84, 41, 80);//เส้นติ๊กเช็ค
		}


		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', 'ป่วย'));
		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', 'เนื่องจาก ..................................................................................................................'));

		if($values['leave_type'] == '2')
		{
			$pdf->Cell(35, 9, iconv('UTF-8', 'TIS-620', $values['leave_detail']));
		}

		$pdf->Ln(8);
		$pdf->Cell(35);

		//check box
		$pdf->Line(37, 88, 41, 88);//เส้นขอบแนวนอนบน
		$pdf->Line(37, 92, 41, 92);//เส้นขอบแนวนอนล่าง

		$pdf->Line(37, 88, 37, 92);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(41, 88, 41, 92);//เส้นขอบแนวตั้งขวา

		//ลากิจส่วนตัว
		if($values['leave_type'] == '3')
		{
			$pdf->Line(37, 92, 41, 88);//เส้นติ๊กเช็ค
		}

		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', 'ขอลากิจส่วนตัว'));
		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', 'เนื่องจาก ..................................................................................................................'));

		//ลากิจส่วนตัว
		if($values['leave_type'] == '3')
		{
			$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', $values['leave_detail']));
		}
		

		$pdf->Ln(8);
		$pdf->Cell(35);

		//check box
		$pdf->Line(37, 96, 41, 96);//เส้นขอบแนวนอนบน
		$pdf->Line(37, 100, 41, 100);//เส้นขอบแนวนอนล่าง

		$pdf->Line(37, 96, 37, 100);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(41, 96, 41, 100);//เส้นขอบแนวตั้งขวา

		//ลาคลอดบุตร
		if($values['leave_type'] == '4')
		{
			$pdf->Line(37, 100, 41, 96);//เส้นติ๊กเช็ค
		}

		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', 'คลอดบุตร'));


		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(35, 10, iconv('UTF-8', 'TIS-620', 'ตั้งแต่วันที่ ......................................................... ถึงวันที่ ......................................................... มีกำหนด ................. วัน'));

		$pdf->Cell(60, 9, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['leave_start'])));
		$pdf->Cell(60, 9, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['leave_end'])));
		$pdf->Cell(35, 9, iconv('UTF-8', 'TIS-620', $values['leave_total']));


		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(33, 10, iconv('UTF-8', 'TIS-620', 'ข้าพเจ้าได้ลา'));


		//check box
		$pdf->Line(42, 112, 46, 112);//เส้นขอบแนวนอนบน
		$pdf->Line(42, 116, 46, 116);//เส้นขอบแนวนอนล่าง

		$pdf->Line(42, 112, 42, 116);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(46, 112, 46, 116);//เส้นขอบแนวตั้งขวา

		//ป่วย
		if($values['after_leave_type'] == '2')
		{
			$pdf->Line(42, 116, 46, 112);//เส้นติ๊กเช็ค
		}

		$pdf->Cell(31, 10, iconv('UTF-8', 'TIS-620', 'ป่วย'));

		//check box
		$pdf->Line(72, 112, 76, 112);//เส้นขอบแนวนอนบน
		$pdf->Line(72, 116, 76, 116);//เส้นขอบแนวนอนล่าง

		$pdf->Line(72, 112, 72, 116);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(76, 112, 76, 116);//เส้นขอบแนวตั้งขวา

		//กิจส่วนตัว
		if($values['after_leave_type'] == '3')
		{
			$pdf->Line(72, 116, 76, 112);//เส้นติ๊กเช็ค
		}

		$pdf->Cell(40, 10, iconv('UTF-8', 'TIS-620', 'กิจส่วนตัว'));


		//check box
		$pdf->Line(112, 112, 116, 112);//เส้นขอบแนวนอนบน
		$pdf->Line(112, 116, 116, 116);//เส้นขอบแนวนอนล่าง

		$pdf->Line(112, 112, 112, 116);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(116, 112, 116, 116);//เส้นขอบแนวตั้งขวา

		//กิจส่วนตัว
		if($values['after_leave_type'] == '4')
		{
			$pdf->Line(112, 116, 116, 112);//เส้นติ๊กเช็ค
		}

		$pdf->Cell(33, 10, iconv('UTF-8', 'TIS-620', 'คลอดบุตร'));


		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(40, 10, iconv('UTF-8', 'TIS-620', 'ครั้งสุดท้ายตั้งแต่วันที่ ................................................ ถึงวันที่ ................................................ มีกำหนด ................. วัน'));

		$pdf->Cell(60, 10, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['after_start'])));
		$pdf->Cell(55, 10, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['after_end'])));
		$pdf->Cell(40, 8, iconv('UTF-8', 'TIS-620', $values['after_total']));
		

		$pdf->Ln(9);
		$pdf->Cell(140);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'ขอแสดงความนับถือ'));

		$pdf->Ln(9);
		$pdf->Cell(110);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'ลงขื่อ'));

		$pdf->Ln(8);
		$pdf->Cell(120);
		$pdf->Cell(15, 10, iconv('UTF-8', 'TIS-620', '(.........................................................................)'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620',$values['emp_name']));


		$pdf->Ln(8);
		$pdf->Cell(108);
		$pdf->Cell(20, 10, iconv('UTF-8', 'TIS-620', 'ตำแหน่ง ........................................................................'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', $values['emp_position_name']));


		$pdf->Ln(9);
		$pdf->Cell(5);
		$pdf->AddFont('THSarabunb', '', 'THSarabun Bold.php');
		$pdf->SetFont('THSarabunb', 'u', 18);
		
		$pdf->Cell(98, 10, iconv('UTF-8', 'TIS-620', 'สถิติการลาในปีงบประมาณนี้'));
		$pdf->Cell(108, 10, iconv('UTF-8', 'TIS-620', 'ความเห็นผู้บังคับบัญชา'));


		$pdf->Ln(8);
		$pdf->AddFont('THSarabun', '', 'THSarabun.php');
		$pdf->SetFont('THSarabun', '', 16);
		$pdf->Cell(7);
		$pdf->Cell(27, 10, iconv('UTF-8', 'TIS-620', 'ประเภทการลา'));

		
		$pdf->Line(16, 169, 95, 169);//เส้นขอบแนวนอนบน
		$pdf->Line(16, 204, 95, 204);//เส้นขอบแนวนอนล่าง

		$pdf->Line(16, 169, 16, 204);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(95, 169, 95, 204);//เส้นขอบแนวตั้งขวา

		$pdf->Cell(19, 10, iconv('UTF-8', 'TIS-620', 'ลามาแล้ว'));
		$pdf->Cell(17, 10, iconv('UTF-8', 'TIS-620', 'ลาครั้งนี้'));
		$pdf->Cell(33, 10, iconv('UTF-8', 'TIS-620', 'รวมเป็น'));
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '..............................................................................................'));

		//เส้นแบ่งคอลัม
		$pdf->Line(42, 169, 42, 204);
		$pdf->Line(61, 169, 61, 204);
		$pdf->Line(78, 169, 78, 204);

		//เส้นแบ่งแถว
		//Row1
		$pdf->Line(16, 177, 95, 177);
		$pdf->Ln(8);
		$pdf->Cell(15);


		

		//ลาป่วย
		if($values['leave_type'] == '2')
		{
			$pdf->Cell(23, 10, iconv('UTF-8', 'TIS-620', 'ป่วย'));

			$pdf->Cell(18, 10, iconv('UTF-8', 'TIS-620', $values['Total_Leave']));

			$pdf->Cell(18, 10, iconv('UTF-8', 'TIS-620', $values['leave_total']));

			$pdf->Cell(29, 10, iconv('UTF-8', 'TIS-620', (double) $values['Total_Leave'] + (double) $values['leave_total'] ));
		}
		else
		{
			$pdf->Cell(23, 10, iconv('UTF-8', 'TIS-620', 'ป่วย'));
			$pdf->Cell(65, 10, iconv('UTF-8', 'TIS-620', $values['Total_Leave']));
		}



		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '..............................................................................................'));

		//Row2
		$pdf->Line(16, 186, 95, 186);
		$pdf->Ln(9);
		$pdf->Cell(11);

		//ลากิจส่วนตัว
		if($values['leave_type'] == '3')
		{
			$pdf->Cell(27, 10, iconv('UTF-8', 'TIS-620', 'กิจส่วนตัว'));

			$pdf->Cell(18, 10, iconv('UTF-8', 'TIS-620', $values['Total_Errand']));

			$pdf->Cell(18, 10, iconv('UTF-8', 'TIS-620', $values['leave_total']));

			$pdf->Cell(120, 10, iconv('UTF-8', 'TIS-620',$values['Total_Errand'] + $values['leave_total'] ));
		}
		else
		{	
			$pdf->Cell(27, 10, iconv('UTF-8', 'TIS-620', 'กิจส่วนตัว'));
			//$pdf->Cell(120, 10, iconv('UTF-8', 'TIS-620', $values['Total_Errand']));
			$pdf->Cell(65, 11, iconv('UTF-8', 'TIS-620', ""));
		}

		

		$pdf->Ln(-1);
		$pdf->Cell(103);
		$pdf->Cell(80, 10, iconv('UTF-8', 'TIS-620', '..............................................................................................'));

		//Row3
		$pdf->Line(16, 195, 95, 195);
		$pdf->Ln(9);
		$pdf->Cell(11);
		

		//ลากิจส่วนตัว
		if($values['leave_type'] == '4')
		{
			$pdf->Cell(27, 11, iconv('UTF-8', 'TIS-620', 'คลอดบุตร'));

			$pdf->Cell(18, 11, iconv('UTF-8', 'TIS-620', $values['Total_Manternity']));

			$pdf->Cell(18, 11, iconv('UTF-8', 'TIS-620', $values['leave_total']));

			$pdf->Cell(29, 11, iconv('UTF-8', 'TIS-620', $values['Total_Manternity'] + $values['leave_total'] ));
		}
		else
		{
			$pdf->Cell(27, 11, iconv('UTF-8', 'TIS-620', 'คลอดบุตร'));

			//$pdf->Cell(65, 11, iconv('UTF-8', 'TIS-620', $values['Total_Manternity']));
			$pdf->Cell(65, 11, iconv('UTF-8', 'TIS-620', ""));

		}
		

		$pdf->Cell(19, 10, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) .................................................................................'));

		$pdf->Ln(6);
		$pdf->Cell(103);
		$pdf->Cell(30, 15, iconv('UTF-8', 'TIS-620', '(ตำแหน่ง) ............................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(103);
		$pdf->Cell(30, 15, iconv('UTF-8', 'TIS-620', 'วันที่  .............../.........................................../......................'));


		$pdf->Ln(9);
		$pdf->Cell(103);
		$pdf->AddFont('THSarabunb', '', 'THSarabun Bold.php');
		$pdf->SetFont('THSarabunb', 'u', 16);

		$pdf->Cell(0, 15, iconv('UTF-8', 'TIS-620', 'คำสั่ง'));

		$pdf->Ln(5);
		$pdf->Cell(5);
		$pdf->SetFont('THSarabunb', '', 16);
		$pdf->Cell(117, 15, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ......................................................... ผู้ตรวจสอบ'));

		// check box
		$pdf->Line(125, 226, 129, 226);//เส้นขอบแนวนอนบน
		$pdf->Line(125, 230, 129, 230);//เส้นขอบแนวนอนล่าง

		$pdf->Line(125, 226, 125, 230);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(129, 226, 129, 230);//เส้นขอบแนวตั้งขวา

		$pdf->Cell(33, 15, iconv('UTF-8', 'TIS-620', 'อนุญาต'));
		$pdf->Line(157, 226, 161, 226);//เส้นขอบแนวนอนบน
		$pdf->Line(157, 230, 161, 230);//เส้นขอบแนวนอนล่าง

		$pdf->Line(157, 226, 157, 230);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(161, 226, 161, 230);//เส้นขอบแนวตั้งขวา
		$pdf->Cell(0, 15, iconv('UTF-8', 'TIS-620', 'ไม่อนุญาต'));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(98, 15, iconv('UTF-8', 'TIS-620', '(ตำแหน่ง) ....................................................'));
		$pdf->Cell(10, 15, iconv('UTF-8', 'TIS-620', '........................................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(98, 15, iconv('UTF-8', 'TIS-620', 'วันที่ .............../.........................../................'));
		$pdf->Cell(10, 15, iconv('UTF-8', 'TIS-620', '........................................................................................'));


		$pdf->Ln(8);
		$pdf->Cell(103);
		$pdf->Cell(122, 15, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ...........................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(103);
		$pdf->Cell(117, 15, iconv('UTF-8', 'TIS-620', '(ตำแหน่ง) .....................................................................'));


		$pdf->Ln(8);
		$pdf->Cell(103);
		$pdf->Cell(117, 15, iconv('UTF-8', 'TIS-620', 'วันที่ ..............................................................................'));

		$pdf->Output();


		/*
		//unlink("tokens.pdf");
		$pdf->Output('tokens.pdf','D');  

		readfile('tokens.pdf');

		header('Content-Type: text/csv');
		header('Content-Disposition: attachment; filename="tokens.pdf"');
		*/
		
          	
          }
          else
          {
          	$host  = $_SERVER['HTTP_HOST'];
			$uri   = str_replace('report','',rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));
			$extra = 'diary_list.php';
			header("Location: http://$host$uri/$extra"); 
          }


		
		

		/*
		// Redirect to a different page in the current directory that was requested 
		$host  = $_SERVER['HTTP_HOST'];
		$uri   = str_replace('report','',rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));
		$extra = 'diary_list.php';

		echo $host . "<br>";

		echo $uri . "<br>";

		echo $extra . "<br>";

		header("Location: http://$host$uri/$extra"); 
		

		//exit;
		*/
		

		/*
		echo '<script type="text/javascript">';
		echo 'window.location.href="diary_list.php";';
		echo '</script>';*/

function getMonthFullName($month_id) {
  $month_name = "";
  switch ($month_id) {
    case '01':
      $month_name = "มกราคม";
      break;
    case '02':
      $month_name = "กุมภาพันธ์";
      break;
    case '03':
      $month_name = "มีนาคม";
      break;
    case '04':
      $month_name = "เมษายน";
      break;
    case '05':
      $month_name = "พฤษภาคม";
      break;
    case '06':
      $month_name = "มิถุนายน";
      break;
    case '07':
      $month_name = "กรกฎาคม";
      break;
    case '08':
      $month_name = "สิงหาคม";
      break;
    case '09':
      $month_name = "กันยายน";
      break;
    case '10':
      $month_name = "ตุลาคม";
      break;
    case '11':
      $month_name = "พฤศจิกายน";
      break;
    case '12':
      $month_name = "ธันวาคม";
      break;
    default:
      break;
  }
  return $month_name;
}
		
function Get_Full_Date($stryymmdd)
    {
      $year = substr($stryymmdd, 0, 4);
        $month = substr($stryymmdd, 4, 2);
        $day = substr($stryymmdd, 6, 2);

        return $day . " " . getMonthFullName($month) . " " . ($year + 543);
    }

?>