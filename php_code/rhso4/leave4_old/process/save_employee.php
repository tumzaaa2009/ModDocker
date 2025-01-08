<?php
	//check user login
	include '../check_session_login.php';
	include '../class/class_db.php';
	include '../class/class_utility.php';
  

//phpinfo();

	/*
	echo $_POST['st'] . "<br>";
	echo $_POST['emp_id'] . "<br>";
	echo trim($_POST['emp_name']) . "<br>";
	echo ($_POST['emp_lastname']) . "<br>";
	echo ($_POST['emp_position_name']) . "<br>";
	echo $_POST['emp_holiday_lmt'] . "<br>";
	echo $_POST['emp_leave_lmt'] . "<br>";
	echo $_POST['emp_errand_leave_lmt'] . "<br>";
	echo DDMMYYTOYYYYMMDD($_POST['emp_start']) . "<br>";
	echo $_POST['emp_mobile'] . "<br>";
	echo (($_POST['emp_status'] == "Y")? "Y":"N") . "<br>";

	//echo $_GET['a'] . "<br>";
	//echo "OK" . "<br>";

  //echo $_POST['department_id'] . "<br>";
  //echo explode(':', $_POST['department_id'])[1];

  echo $_POST['emp_status']."_status <br>";

  echo explode(":", $_POST['title_id'])[0] . "Title";


  $str = 'a:b:c';

  // positive limit
  print_r((explode(':', $str))) ;

  $b = ((explode(':', $str)));

  echo  "<br>" . $b[0]; */

  $department_id = explode(':', $_POST['department_id']);

  $title_id = explode(':', $_POST['title_id']);

  $type_id = explode(':', $_POST['type_id']);
  
	//Add user
	if(($_POST['st'] == "add"))
	{
		$n = array(
      	'id' => "",
		    'emp_id' => trim($_POST['emp_id']),
      	'emp_name' => trim($_POST['emp_name']),
      	'emp_lastname' => trim($_POST['emp_lastname']),
      	//'emp_holiday_lmt' => $_POST['emp_holiday_lmt'],
      	//'emp_leave_lmt' => $_POST['emp_leave_lmt'],
      	//'emp_errand_leave_lmt' => $_POST['emp_errand_leave_lmt'],
      	'emp_start' => DDMMYYTOYYYYMMDD($_POST['emp_start']),

      	'emp_end' => "",
      	'emp_group_id' => "",

        //'emp_position_name' => explode(':', $_POST['department_id'])[1],
        'emp_position_name' => $department_id[1],
        

		'emp_status' => ($_POST['emp_status'] == "Y")? "Y":"N",

		'emp_update' => DDMMYYTOYYYYMMDD(date("Y-m-d")),
      	
      	'emp_mobile' => $_POST['emp_mobile'],

        'work_id' => $_POST['work_id'],

        //'department_id' => explode(':', $_POST['department_id'])[0],
        'department_id' => $department_id[0],

        'affiliate_id' => $_POST['affiliate_id'],

        //'emp_maternity_leave_lmt' => $_POST['emp_maternity_leave_lmt'],

        //'emp_holiday_remain' => $_POST['emp_holiday_remain'] ,

        //'title_id' => explode(':', $_POST['title_id'])[0],
        'title_id' => $title_id[0],
        

        //'fiscal_year' => $_SESSION['fiscal_year']

        //'type_id' => explode(':', $_POST['type_id'])[0]
        'type_id' => $type_id[0]
      	
    	);

    	$conn = new class_db();
      /*
        $sql = "insert into employee values(:id, :emp_id, :emp_name, :emp_lastname, :emp_holiday_lmt, :emp_leave_lmt, :emp_errand_leave_lmt, :emp_start, :emp_end, :emp_group_id, :emp_position_name, :emp_status, :emp_update, :emp_mobile, :work_id, :department_id, :affiliate_id, :emp_maternity_leave_lmt , :emp_holiday_remain , :title_id)";*/
    	$sql = "insert into employee values(:id, :emp_id, :emp_name, :emp_lastname, :emp_start, :emp_end, :emp_group_id, :emp_position_name, :emp_status, :emp_update, :emp_mobile, :work_id, :department_id, :affiliate_id, :title_id, :type_id)";
    	$result = $conn->execute_query_tran($sql, $n);

    	if ($result) 
    	{

        $n = array(
        'emp_id' => trim($_POST['emp_id']),
        'emp_holiday_lmt' => $_POST['emp_holiday_lmt'],
        'emp_leave_lmt' => $_POST['emp_leave_lmt'],
        'emp_errand_leave_lmt' => $_POST['emp_errand_leave_lmt'], 

        'emp_maternity_leave_lmt' => $_POST['emp_maternity_leave_lmt'],

        'emp_holiday_remain' => $_POST['emp_holiday_remain'] ,

        'fiscal_year' => $_SESSION['fiscal_year']
        
      );

        $sql = "insert into leave_lmt_master values(:emp_id, :fiscal_year, :emp_holiday_lmt, :emp_leave_lmt, :emp_errand_leave_lmt,:emp_maternity_leave_lmt , :emp_holiday_remain )";
        $result = $conn->execute_query_tran($sql, $n);

        if($result)
        {
          //set alert css
          //**************************************************************
          $_SESSION['flash']['type'] = 'success';
          $_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
          //**************************************************************
          redirec_to('../employee_master.php');
        }

    	}
	}
	else if(($_POST['st'] == "edit"))
	{
		//Edit
    $sql = "";

    
    $n = array(
        'emp_id' => trim($_POST['emp_id']),
        'emp_name' => trim($_POST['emp_name']),
        'emp_lastname' => trim($_POST['emp_lastname']),
        'emp_holiday_lmt' => $_POST['emp_holiday_lmt'],
        'emp_leave_lmt' => $_POST['emp_leave_lmt'],
        'emp_errand_leave_lmt' => $_POST['emp_errand_leave_lmt'],
        'emp_start' => DDMMYYTOYYYYMMDD($_POST['emp_start']),

        'emp_end' => "",
        'emp_group_id' => "",

        //'emp_position_name' => explode(':', $_POST['department_id'])[1],
        'emp_position_name' => $department_id[1],

        'emp_status' => ($_POST['emp_status'] == "Y")? "Y":"N",

        'emp_update' => DDMMYYTOYYYYMMDD(date("Y-m-d")),
        
        'emp_mobile' => $_POST['emp_mobile'],

        'work_id' => $_POST['work_id'],

        //'department_id' => explode(':', $_POST['department_id'])[0],
        'department_id' => $department_id[0],

        'affiliate_id' => $_POST['affiliate_id'],

        'emp_maternity_leave_lmt' => $_POST['emp_maternity_leave_lmt'],

        'temp_emp_id' => $_POST['temp_emp_id'],

        'emp_holiday_remain' => $_POST['emp_holiday_remain'],

        //'title_id' => explode(':', $_POST['title_id'])[0],
        'title_id' => $title_id[0],

        'fiscal_year' => $_SESSION['fiscal_year'],

        //'type_id' => explode(':', $_POST['type_id'])[0]
        'type_id' => $type_id[0]
      );


    $sql .= " update employee emp, leave_lmt_master lmt SET emp.emp_id =:emp_id , emp.emp_name =:emp_name , emp.emp_lastname =:emp_lastname, emp.emp_start =:emp_start, emp.emp_end =:emp_end, emp.emp_group_id =:emp_group_id, emp.emp_position_name =:emp_position_name, emp.emp_status =:emp_status, emp.emp_update =:emp_update, emp.emp_mobile =:emp_mobile, emp.work_id =:work_id, emp.department_id =:department_id, emp.affiliate_id =:affiliate_id,emp.title_id =:title_id,emp.type_id =:type_id
      ,lmt.emp_holiday_lmt =:emp_holiday_lmt, lmt.emp_leave_lmt =:emp_leave_lmt, lmt.emp_errand_leave_lmt =:emp_errand_leave_lmt, lmt.emp_maternity_leave_lmt =:emp_maternity_leave_lmt , lmt.emp_holiday_remain =:emp_holiday_remain
      ,lmt.emp_id =:emp_id ";

    $sql .= " where emp.emp_id =:temp_emp_id and emp.emp_id = lmt.emp_id and lmt.fiscal_year =:fiscal_year ";

    $conn = new class_db();
      $result = $conn->execute_query_tran($sql, $n);

      if ($result) 
      {
        //set alert css
        //**************************************************************
          $_SESSION['flash']['type'] = 'success';
          $_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
          //**************************************************************


          $conn = null;
          $result = null;

          redirec_to('../employee_master.php');

      }


	}
	else if($_GET['del'] == "Y")
	{
    //del
		$n = array(
		'emp_id' => trim($_GET['emp_id'])
    	);

    	$conn = new class_db();
    	$sql = "delete FROM employee where emp_id = :emp_id";
    
    	$result = $conn->execute_query_tran($sql, $n);

    	if ($result) 
    	{
        $sql = "delete FROM leave_lmt_master where emp_id =:emp_id";
        $result = $conn->execute_query_tran($sql, $n);

        if ($result) 
    	{
        		//set alert css
    		//**************************************************************
        $_SESSION['flash']['type'] = 'success';
        $_SESSION['flash']['msg'] = "ลบข้อมูลเรียบร้อยแล้ว";
        //**************************************************************
        redirec_to('../employee_master.php');
        //echo "del";
      }

    
    	}

	}



?>