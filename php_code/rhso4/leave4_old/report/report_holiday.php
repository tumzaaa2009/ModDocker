<?php
	require('../fpdf/write_html.php');
	include '../class/class_db.php';

	session_start();
	//ปีงบประมาณ
	$fiscal_year = $_SESSION['fiscal_year'];

	class PDF extends FPDF {
	}

		$conn = new class_db();
          $n = array(
            'leave_list_id' => $_GET['leaveid']
          );


$sql = " SELECT AA.* , AA.Total_Holiday + AA.leave_total T_holiday , AA.Total_Leave + AA.leave_total T_leave ";
$sql .= " , AA.Total_Errand + AA.leave_total T_errand , AA.Total_Manternity + AA.leave_total T_manternity FROM ( ";
$sql .= "select ll.*,ld.emp_id,ld.lea_type_id,ld.leave_start,ld.leave_end,ld.leave_day_total,ld.leave_detail,ld.leave_subject
  ,ld.leave_remark,ld.leave_total,(select CONCAT(tm2.title_name,' ',CONCAT(emp2.emp_name,' ',emp2.emp_lastname)) leave_assign from employee emp2 , title_master tm2 where ld.leave_assign = emp2.emp_id and tm2.id = emp2.title_id) leave_assign,ld.leave_half_detail,ld.leave_contact ";

            $sql .= " ,CONCAT(t.title_name,' ',CONCAT(emp.emp_name,' ',emp.emp_lastname)) emp_name, emp_position_name , lmt.emp_holiday_lmt , lmt.emp_holiday_remain";

          	$sql .= " ,(select ld2.leave_start from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_start ";

			$sql .=" ,(select ld2.leave_end from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_end ";

			$sql .=" ,(select ld2.leave_total from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_total ";

			$sql .=" ,(select ld2.lea_type_id from leave_list l , leave_detail ld2 where l.leave_list_id = ld2.leave_list_id and l.leave_emp_id = ll.leave_emp_id ORDER BY l.leave_date DESC limit 1) after_leave_type ";

			//SUM
			$sql .=" ,(select IFNULL(SUM(A.leave_total),0) Total_Holiday from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			//$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " where ld3.leave_start >= REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 0 YEAR),'-','') and ld3.leave_end < REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 1 YEAR),'-','') ";

			$sql .= " and ld3.lea_type_id = '1' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Holiday ";


			$sql .= " ,(select SUM(A.leave_total) Total_Leave from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " and ld3.lea_type_id = '2' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Leave ";


			$sql .= ",(select SUM(A.leave_total) Total_Errand from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " and ld3.lea_type_id = '3' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Errand ";


			$sql .= " ,(select COALESCE(SUM(A.leave_total),0) Total_Manternity from ( ";

			$sql .= " select ld3.leave_total , ld3.emp_id , ld3.leave_id from leave_detail ld3 ";

			$sql .= " where ld3.leave_start >= DATE_FORMAT(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),'%Y%m%d') and ld3.leave_end < DATE_FORMAT(DATE_ADD(CONCAT(DATE_FORMAT(CURDATE(), '%Y'),'10','01'),INTERVAL 1 YEAR),'%Y%m%d') ";

			$sql .= " and ld3.lea_type_id = '4' ";

			$sql .= " ) AS A where A.emp_id = ld.emp_id and A.leave_id not in(ld.leave_id) ) Total_Manternity ";

          	
          	$sql .= " from leave_list ll , leave_detail ld , employee emp , leave_lmt_master lmt , title_master t";
			$sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_list_id = :leave_list_id ";
			$sql .= " and ll.leave_emp_id = emp.emp_id and emp.emp_id = lmt.emp_id";
			$sql .= " and emp.title_id = t.id ";

		   $sql .= " ) AA ";

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
		$pdf->SetFont('THSarabunb', '', 20);
		
		$pdf->Cell(75);
		$pdf->Cell(0,20,iconv('UTF-8', 'TIS-620', 'แบบใบลาพักผ่อน'));

		
		$pdf->Ln(11);
		$pdf->Cell(115);
		$pdf->AddFont('THSarabun', '', 'THSarabun.php');
		$pdf->SetFont('THSarabun', '', 16);
		$pdf->Cell(23,20,iconv('UTF-8', 'TIS-620', 'เขียนที่.....................................................................'));
		$pdf->Cell(50, 18, iconv('UTF-8', 'TIS-620', 'ที่สำนักงานเขตสุขภาพที่ 4'));
		
		$pdf->Ln(8);
		$pdf->Cell(110);
		$pdf->Cell(23,20,iconv('UTF-8', 'TIS-620', 'วันที่...........................................................................'));
		$pdf->Cell(50, 18, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['leave_date'])));

		$pdf->Ln(12);
		$pdf->Cell(5);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'เรื่อง    ขอลาพักผ่อน'));

		$pdf->Ln(10);
		$pdf->Cell(5);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'เรียน    ผู้อำนวยการสำนักงานเขตสุขภาพที่ 4'));

		$pdf->Ln(10);
		$pdf->Cell(25);
		$pdf->Cell(15, 10, iconv('UTF-8', 'TIS-620', 'ข้าพเจ้า........................................................................ตำแหน่ง.................................................................................'));
		$pdf->Cell(80, 9, iconv('UTF-8', 'TIS-620', $values['emp_name']));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', $values['emp_position_name']));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(25, 10, iconv('UTF-8', 'TIS-620', 'สังกัด...............................................................................................................................................................................................'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', 'สำนักงานเขตสุขภาพที่ 4'));


		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(40, 10, iconv('UTF-8', 'TIS-620', 'มีวันลาพักผ่อนสะสม................................วันทำการ มีสิทธิลาพักผ่อนประจำปีนี้อีก 10 วันทำการ รวมเป็น........................... วัน'));

		//$pdf->Cell(120, 8, iconv('UTF-8', 'TIS-620', $values['emp_holiday_remain']));
		//$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', $values['emp_holiday_lmt']));


		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(45, 10, iconv('UTF-8', 'TIS-620', 'ขอลาพักผ่อนตั้งแต่วันที่........................................................ถึงวันที่........................................................มีกำหนด...................วัน'));
		$pdf->Cell(60, 8, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['leave_start'])));
		$pdf->Cell(63, 8, iconv('UTF-8', 'TIS-620', Get_Full_Date($values['leave_end'])));
		$pdf->Cell(0, 8, iconv('UTF-8', 'TIS-620', $values['leave_total']));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(70, 10, iconv('UTF-8', 'TIS-620', 'ในระหว่างลาจะติดต่อข้าพเจ้าได้ที่..................................................................................................................................................'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', $values['leave_contact']));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '........................................................................................................................................................................................................'));


		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(70, 10, iconv('UTF-8', 'TIS-620', 'ในระหว่างลานี้ ได้มอบหมายให้ .....................................................................................................................................................'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', $values['leave_assign']));


		$pdf->Ln(11);
		$pdf->Cell(140);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'ขอแสดงความนับถือ'));

		$pdf->Ln(9);
		$pdf->Cell(110);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'ลงขื่อ'));

		$pdf->Ln(8);
		$pdf->Cell(120);
		$pdf->Cell(12, 10, iconv('UTF-8', 'TIS-620', '(.........................................................................)'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620',$values['emp_name']));

		$pdf->Ln(8);
		$pdf->Cell(108);
		$pdf->Cell(18, 10, iconv('UTF-8', 'TIS-620', 'ตำแหน่ง ........................................................................'));
		$pdf->Cell(0, 9, iconv('UTF-8', 'TIS-620', $values['emp_position_name']));


		$pdf->Ln(10);
		$pdf->Cell(108);
		$pdf->AddFont('THSarabunb', '', 'THSarabun Bold.php');
		$pdf->SetFont('THSarabunb', 'u', 18);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', 'ความเห็นผู้บังคับบัญชา'));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(103, 10, iconv('UTF-8', 'TIS-620', 'สถิติการลาในปีงบประมาณนี้'));

		$pdf->AddFont('THSarabun', '', 'THSarabun.php');
		$pdf->SetFont('THSarabun', '', 16);

		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '........................................................................................'));

		$pdf->Line(16, 175, 95, 175);//เส้นขอบแนวนอนบน y=35
		$pdf->Line(16, 175, 16, 200);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(95, 175, 95, 200);//เส้นขอบแนวตั้งขวา
		$pdf->Line(16, 200, 95, 200);//เส้นขอบแนวนอนล่าง

		$pdf->Line(16, 188, 95, 188);//เส้นแบ่งตาราง
		$pdf->Line(42, 175, 42, 200);//เส้นขอบแบ่งคอลัม
		$pdf->Line(70, 175, 70, 200);//เส้นขอบแบ่งคอลัมขวา

		

		$pdf->Ln(8);
		$pdf->Cell(11);
		$pdf->Cell(28, 15, iconv('UTF-8', 'TIS-620', 'ลามาแล้ว'));
		$pdf->Cell(27, 15, iconv('UTF-8', 'TIS-620', 'ลาครั้งนี้'));
		$pdf->Cell(43, 15, iconv('UTF-8', 'TIS-620', 'รวมเป็น'));
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '........................................................................................'));

		$pdf->Ln(5);
		$pdf->Cell(10);
		$pdf->Cell(27, 15, iconv('UTF-8', 'TIS-620', '(วันทำการ)'));
		$pdf->Cell(27, 15, iconv('UTF-8', 'TIS-620', '(วันทำการ)'));
		$pdf->Cell(48, 15, iconv('UTF-8', 'TIS-620', '(วันทำการ)'));
		$pdf->Ln(3);
		$pdf->Cell(108);
		$pdf->Cell(0, 10, iconv('UTF-8', 'TIS-620', '........................................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(113);
		$pdf->Cell(30, 15, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ......................................................................'));

		$pdf->Ln(1);
		$pdf->Cell(15);
		$pdf->Cell(27, 10, iconv('UTF-8', 'TIS-620', $values['Total_Holiday']));
		$pdf->Cell(27, 10, iconv('UTF-8', 'TIS-620', $values['leave_total']));
		$pdf->Cell(30, 10, iconv('UTF-8', 'TIS-620', $values['T_holiday']));

		$pdf->Ln(8);
		$pdf->Cell(109);
		$pdf->Cell(30, 15, iconv('UTF-8', 'TIS-620', '(ตำแหน่ง) .....................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(117);
		$pdf->Cell(30, 15, iconv('UTF-8', 'TIS-620', 'วันที่ ............/.................................../...................'));

		$pdf->Ln(9);
		$pdf->Cell(108);
		$pdf->AddFont('THSarabunb', '', 'THSarabun Bold.php');
		$pdf->SetFont('THSarabunb', 'u', 18);
		$pdf->Cell(0, 15, iconv('UTF-8', 'TIS-620', 'คำสั่ง'));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->SetFont('THSarabunb', '', 16);
		$pdf->Cell(128, 15, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ................................................................ ผู้ตรวจสอบ'));

		// check box
		$pdf->Line(130, 225, 135, 225);//เส้นขอบแนวนอนบน
		$pdf->Line(130, 230, 135, 230);//เส้นขอบแนวนอนล่าง

		$pdf->Line(130, 225, 130, 230);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(135, 225, 135, 230);//เส้นขอบแนวตั้งขวา

		$pdf->Cell(35, 15, iconv('UTF-8', 'TIS-620', 'อนุญาต'));
		$pdf->Line(165, 225, 170, 225);//เส้นขอบแนวนอนบน
		$pdf->Line(165, 230, 170, 230);//เส้นขอบแนวนอนล่าง

		$pdf->Line(165, 225, 165, 230);//เส้นขอบแนวตั้งซ้าย
		$pdf->Line(170, 225, 170, 230);//เส้นขอบแนวตั้งขวา
		$pdf->Cell(0, 15, iconv('UTF-8', 'TIS-620', 'ไม่อนุญาต'));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(103, 15, iconv('UTF-8', 'TIS-620', '(ตำแหน่ง) ...........................................................'));
		$pdf->Cell(10, 15, iconv('UTF-8', 'TIS-620', '...................................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(5);
		$pdf->Cell(103, 15, iconv('UTF-8', 'TIS-620', 'วันที่ .............../............................../....................'));
		$pdf->Cell(10, 15, iconv('UTF-8', 'TIS-620', '...................................................................................'));


		$pdf->Ln(8);
		$pdf->Cell(108);
		$pdf->Cell(122, 15, iconv('UTF-8', 'TIS-620', '(ลงชื่อ) ......................................................................'));

		$pdf->Ln(8);
		$pdf->Cell(108);
		$pdf->Cell(117, 15, iconv('UTF-8', 'TIS-620', '(ตำแหน่ง) .................................................................'));


		$pdf->Ln(8);
		$pdf->Cell(108);
		$pdf->Cell(117, 15, iconv('UTF-8', 'TIS-620', 'วันที่ ..........................................................................'));
		

		$pdf->Output();

        }
        else
        {
          	$host  = $_SERVER['HTTP_HOST'];
			$uri   = str_replace('report','',rtrim(dirname($_SERVER['PHP_SELF']), '/\\'));
			$extra = 'diary_list.php';
			header("Location: http://$host$uri/$extra"); 
        }


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