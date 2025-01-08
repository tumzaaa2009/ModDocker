<?php
	//check user login
	include '../check_session_login.php';
	include '../class/class_db.php';
	include '../class/class_utility.php';
	include '../class/Class_Load_leave.php';

	/*
	echo $_POST['st'] . "<br>";
	echo $_POST['emp_name'] . "<br>";
	echo $_POST['leave_type'] . "<br>";
	echo $_POST['date_start'] . "<br>";
	echo $_POST['date_end'] . "<br>";
	echo $_POST['leave_remark'] . "<br>";
	echo $_POST['h_leave_total'] . "<br>";
	echo $_POST['contact_tel'] . "<br>";
	echo $_POST['assign_job'] . "<br>";*/
	
	//$_POST['leave_type'] = $_POST['h_leave_total'];

	date_default_timezone_set("Asia/Bangkok");

	/*
	echo date("Y") . " year <br>";
	echo date("m") . " month <br>";
	echo date("d") . " day <br>";

	echo date("H") . " h <br>";

	echo date("i") . " i <br>";

	
	echo date("H:i:s") . "<br>";

	echo (date("Y").date("m").date("d").date("H").date("i").$_POST['emp_name']);*/

	$leave_list_id = (date("Y").date("m").date("d").date("H").date("i").$_POST['emp_name']);

	//echo $_POST['leave_half'];


	if($_POST['leave_half'] == "hm")
	{
		$leave_half_detail = "ลาครึ่งวันเช้า";
	}
	else if($_POST['leave_half'] == "he")
	{
		$leave_half_detail = "ลาครึ่งวันบ่าย";
	}
	else
	{
		$leave_half_detail = "";
	}

  

	//get Last leave Day
	//*************************************************
	$Class_Load_leave = new Class_Load_leave();
    $last_date = $Class_Load_leave->Cal_leave_LastDay($_POST['emp_name'],$_POST['leave_type']);
    $Class_Load_leave = null;
	//*************************************************

    
	//Add user
	if(($_POST['st'] == "add"))
	{
		$n = array(
      	'leave_list_id' => $leave_list_id,
		'leave_year' => date("Y"),
      	'leave_month' => date("m"),
      	'leave_day' => date("d"),
      	'leave_date' => DDMMYYTOYYYYMMDD(date("Y-m-d")),
      	'leave_emp_id' => $_POST['emp_name'],
      	'leave_approve' => "",
      	'leave_type' => $_POST['leave_type'],
      	'leave_cancel' => "N",
      	'leave_last_date' => $last_date
    	);

    	$conn = new class_db();
    	$sql = "insert into leave_list values(:leave_list_id, :leave_year, :leave_month, :leave_day, :leave_date, :leave_emp_id, :leave_approve, :leave_type, :leave_cancel, :leave_last_date)";
    	$result = $conn->execute_query_tran($sql, $n);



    	if ($result) 
    	{
			//save detail
			//************************
			$n = array(
      		'leave_id' => "",
			'emp_id' => $_POST['emp_name'],
      		'lea_type_id' => $_POST['leave_type'],
      		'leave_start' => DDMMYYTOYYYYMMDD($_POST['date_start']),
      		'leave_end' => DDMMYYTOYYYYMMDD($_POST['date_end']),
      		'leave_day_total' => $_POST['h_leave_total'],
      		'leave_detail' => $_POST['leave_remark'],
      		'leave_subject' => $_POST['leave_type'],
      		'leave_remark' => $_POST['leave_remark'],
      		'leave_list_id' => $leave_list_id,
      		'leave_total' => $_POST['h_leave_total'],
      		'leave_assign' => $_POST['assign_job'],
      		'leave_half_detail' => $leave_half_detail,
      		'leave_contact' => $_POST['contact_tel']
      		
      		
    	);

		$sql = "insert into leave_detail values(:leave_id, :emp_id, :lea_type_id, :leave_start, :leave_end, :leave_day_total, :leave_detail, :leave_subject, :leave_remark, :leave_list_id, :leave_total, :leave_assign, :leave_half_detail, :leave_contact)";
    	$result = $conn->execute_query_tran($sql, $n);

    	if($result)
    	{

    		//Cal leave Day
    		//**************************************************************
			$Class_Load_leave = new Class_Load_leave();
    		if($Class_Load_leave->Cal_leave_Day($_POST['emp_name'],$_POST['leave_type'],$_POST['h_leave_total'],$_SESSION['fiscal_year'],"D"))
    		{
    			$Class_Load_leave = null;

    			//set alert css
    			//**************************************************************
      			$_SESSION['flash']['type'] = 'success';
      			$_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
      			//**************************************************************
      			//redirec_to('../diary_list.php');


            if($_POST['leave_type'] == '1')
            {
              redirec_to('../report/report_holiday.php?leaveid='.$leave_list_id);
            }
            else
            {
              redirec_to('../report/report_leave.php?leaveid='.$leave_list_id);
            }

    		}
			//*************************************************

    	}
    		
      		
       }
	}
	else if($_GET['st'] == "del")
	{
		//echo $_GET['st'] . "<br>";
		//echo $_GET['leave_list_id'] . "<br>";
		$n = array(
			'leave_cancel' => 'Y',
      		'leave_list_id' => trim($_GET['leave_list_id'])
    	);

    	$conn = new class_db();
    	$sql = " update leave_list SET leave_cancel =:leave_cancel";
    	$sql .= " where leave_list_id =:leave_list_id";


    	//Cal leave Day
    	//**************************************************
    	
    	$result = $conn->execute_query_tran($sql, $n);
    	
    	if($result)
    	{
    		//Cal leave Day
    		//**************************************************
    		$Class_Load_leave = new Class_Load_leave();
    		if($Class_Load_leave->Cal_leave_Day($_GET['leave_emp_id'],$_GET['leave_type'],$_GET['h_leave_total'],$_SESSION['fiscal_year'],"A"))
    		{
    			$Class_Load_leave = null;

    			//set alert css
    			//**************************************************************
      			$_SESSION['flash']['type'] = 'success';
      			$_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
      			//**************************************************************
      			redirec_to('../diary_list.php');
    		}

    	}
	}
  else if($_POST['st'] == "edit")
  {
  
     // echo $_POST['id'] ;

   // echo $_POST['leave_type'] ."<br>";

   // echo $_POST['h_leave_total'];
    
    

      $conn = new class_db();
      /*
      $sql = " update leave_list SET leave_type =:leave_type";
      $sql .= " where leave_list_id =:leave_list_id ;";

      $sql .= " update leave_detail SET lea_type_id =:lea_type_id , leave_start =:leave_start , leave_end=:leave_end , leave_day_total =:leave_day_total , leave_detail =:leave_detail , leave_subject =:leave_subject , leave_remark =:leave_remark , leave_total =:leave_total , leave_assign =:leave_assign , leave_half_detail =:leave_half_detail ,leave_contact =:leave_contact";*/


      $sql .= " update leave_list ll , leave_detail ld ,employee emp , leave_lmt_master lmt  SET ";
      
      //กรณี แก้ไขเปลี่ยนวันลา หรือ ลามากกว่า 1 วัน ออกรายงาน จะหาวันลาแต่ละวันยังไง?

      $sql .= " ll.leave_type =:leave_type ";

      $sql .= " ,ld.lea_type_id =:lea_type_id ";

      $sql .= " ,ld.leave_start =:leave_start ";

      $sql .= " ,ld.leave_end =:leave_end ";

      if((integer) $_POST['leave_type'] == $_POST['temp_leave_type'])
          {
            //ถ้าแก้ไขของเดิมมากกว่า ของ ใหม่ ให้เอามาลบ
            //$sql .= " ,ld.leave_day_total =(:leave_day_total + :leave_total_temp) ";
            if($_POST['h_leave_total'] > $_POST['temp_leave_total'])
            {
               $sql .= " ,ld.leave_day_total =(leave_day_total + :leave_day_total) ";
            }
            else
            {
              $sql .= " ,ld.leave_day_total =(leave_day_total - :leave_day_total) ";
            }
           
          }
          else
          {
            $sql .= " ,ld.leave_day_total =:leave_day_total";
          }
      

      $sql .= " ,ld.leave_detail =:leave_detail ";

      $sql .= " ,ld.leave_subject =:leave_subject ";

      $sql .= " ,ld.leave_remark =:leave_remark ";

      if((integer) $_POST['leave_type'] == $_POST['temp_leave_type'])
          {
             //$sql .= " ,ld.leave_total =(:leave_total + :leave_total_temp) ";
            if($_POST['h_leave_total'] > $_POST['temp_leave_total'])
            {
              $sql .= " ,ld.leave_total =(leave_total + :leave_total) ";
            }
            else
            {
              $sql .= " ,ld.leave_total =(leave_total - :leave_total) ";
            }
            
          }
          else
          {
             $sql .= " ,ld.leave_total =:leave_total ";
          }
     

      $sql .= " ,ld.leave_assign =:leave_assign ";

      $sql .= " ,ld.leave_half_detail =:leave_half_detail "; 

      $sql .= " ,ld.leave_contact =:leave_contact ";


          //แก้ไข ประเภทเดียวกัน
          //*******************************************
          $total_leave = 0;
          $total_leave_temp = 0;

          echo $_POST['h_leave_total'] . "A<BR>";

          echo $_POST['temp_leave_total'] . "B<BR>";

          echo $_POST['temp_leave_type'] . "<BR>";

          echo $_POST['leave_type'] . "<BR>";

          if((integer) $_POST['leave_type'] == $_POST['temp_leave_type'])
          {

            //check ว่าถ้าจำนวนวันลาเท่าเดิม ประเภทแบบเดิม ไม่ให้คำนวณวันใหม่และupdate วันใหม่
            //***************************************************************************************

            //***************************************************************************************

            $total_leave = cal_remain_leave($_POST['temp_leave_type'],$_POST['leave_type'],$_POST['h_leave_total'],$_POST['temp_leave_total']);

            //ค่าก่อนหน้า
            $total_leave_temp = $_POST['temp_leave_total'];

            //echo $total_leave . "<BR>";

            //ใหม่มากกว่าเดิม
            if($_POST['h_leave_total'] > $_POST['temp_leave_total'])
            {
              $sql .=  get_sql_cal_leave($_POST['leave_type'],"D","NTEMP","-");
            }
            else
            {
              $sql .=  get_sql_cal_leave($_POST['leave_type'],"D","NTEMP","+");
            }

            

            $n = array(
              'leave_type' => $_POST['leave_type'],
              'leave_list_id' => trim($_POST['id']),


              'lea_type_id' => $_POST['leave_type'],
              'leave_start' => DDMMYYTOYYYYMMDD($_POST['date_start']),
              'leave_end' => DDMMYYTOYYYYMMDD($_POST['date_end']),
              'leave_day_total' => $total_leave,
              'leave_detail' => $_POST['leave_remark'],
              'leave_subject' => $_POST['leave_type'],
              'leave_remark' => $_POST['leave_remark'],
              'leave_total' => (double) $total_leave,

              'leave_total_temp' => $total_leave_temp,

              'leave_assign' => $_POST['assign_job'],
              'leave_half_detail' => $leave_half_detail,
              'leave_contact' => $_POST['contact_tel']
            );

          }
          else
          {
            $total_leave = $_POST['h_leave_total'];

            $total_leave_temp = $_POST['temp_leave_total'];

            //echo $total_leave . "<BR>";

            //echo $total_leave_temp . "<BR>";

            //ต้อง Gen 2 query ของเดิมกับอันล่าสุด
            $sql .= get_sql_cal_leave($_POST['leave_type'],"D","NTEMP","-");//ใหม่

            
            $sql .= get_sql_cal_leave($_POST['temp_leave_type'],"A","TEMP","+");//อันเก่า
             //echo "out". $_POST['temp_leave_type'] . $sql2 . "<BR>";   


             $n = array(
              'leave_type' => $_POST['leave_type'],
              'leave_list_id' => trim($_POST['id']),


              'lea_type_id' => $_POST['leave_type'],
              'leave_start' => DDMMYYTOYYYYMMDD($_POST['date_start']),
              'leave_end' => DDMMYYTOYYYYMMDD($_POST['date_end']),
              'leave_day_total' => $total_leave,
              'leave_detail' => $_POST['leave_remark'],
              'leave_subject' => $_POST['leave_type'],
              'leave_remark' => $_POST['leave_remark'],
              'leave_total' => (double) $total_leave,

              'leave_total_temp' => $total_leave_temp,

              'leave_assign' => $_POST['assign_job'],
              'leave_half_detail' => $leave_half_detail,
              'leave_contact' => $_POST['contact_tel']
              ); 

          }

          //ขาด where fiscal year ?
      $sql .= " where ll.leave_list_id =:leave_list_id and ll.leave_list_id = ld.leave_list_id and ld.emp_id = emp.emp_id and emp.emp_id = lmt.emp_id";

      print_r($sql);

      print_r($n);

      $result = $conn->execute_query_tran($sql, $n);
      
      if($result)
      {


            //set alert css
          //**************************************************************
            $_SESSION['flash']['type'] = 'success';
            $_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
            //**************************************************************
            
            //redirec_to('../diary_list.php');
            if($_POST['leave_type'] == '1')
            {
              redirec_to('../report/report_holiday.php?leaveid='.$_POST['id']);
            }
            else
            {
              redirec_to('../report/report_leave.php?leaveid='.$_POST['id']);
            }
            

            //redirec_to('../diary_list.php');
            
      }
      

  }

  function cal_remain_leave($temp_type,$leave_type,$leave_total,$temp_leave_total)
  {
      $ret = 0;

      if((integer) $temp_type == (integer) $leave_type)
      {

          if($leave_total > $temp_leave_total)
          {
              $ret = $leave_total - $temp_leave_total;
          }
          else
          {
              $ret = $temp_leave_total - $leave_total;
          }

      }

      return $ret;
  }

  function get_sql_cal_leave($leave_type,$status,$status_temp,$opera)
  {
    $ret_sql = "";

    switch ($leave_type) {
      case '1':
        //ลาพักผ่อน
      //echo "1";
        if($status == "D")
        {
          //echo "2";
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          //echo "3";
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        
       //echo "4";
        break;

         case 1:
        //ลาพักผ่อน
      //echo "1";
        if($status == "D")
        {
          //echo "2";
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          //echo "3";
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_holiday_lmt = (lmt.emp_holiday_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        
       //echo "4";
        break;
      
      case '2':
        //ลาป่วย
        if($status == "D")
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }

        break;

        case 2:
        //ลาป่วย
        if($status == "D")
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_leave_lmt = (lmt.emp_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }

        break;

      case '3':
        //ลากิจส่วนตัว
      
        if($status == "D")
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          if($status_temp == "NTEMP")
          {
             $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
             $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
         
        }
        

        break;

        case 3:
        //ลากิจส่วนตัว
        if($status == "D")
        {
           if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_errand_leave_lmt = (lmt.emp_errand_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        

        break;

      case '4':
        //คลอดบุตร
        if($status == "D")
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        

        break;

        case 4:
        //คลอดบุตร
        if($status == "D")
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        else
        {
          if($status_temp == "NTEMP")
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total) ";
          }
          else
          {
            $ret_sql .= " , lmt.emp_maternity_leave_lmt = (lmt.emp_maternity_leave_lmt  ". $opera ." :leave_total_temp) ";
          }
          
        }
        

        break;
    }

    return $ret_sql;


  }
	
?>