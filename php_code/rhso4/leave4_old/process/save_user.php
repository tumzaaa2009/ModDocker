<?php
	//check user login
	include '../check_session_login.php';
	include '../class/class_db.php';
	include '../class/class_utility.php';
	
	/*
	echo $_POST['st'] . "<br>";
	echo $_POST['user_login'] . "<br>";
	echo trim($_POST['user_login']) . "<br>";
	echo sha1($_POST['pass_login']) . "<br>";
	echo sha1($_POST['re-password']) . "<br>";
	echo $_POST['accouont_name'] . "<br>";
	echo $_POST['account_type'] . "<br>";
	echo (($_POST['account_status'] == "Y")? "Y":"N") . "<br>";
	echo date("Ymd") . "<br>";
	echo date("Y") . "<br>";
	echo date("m") . "<br>";
	echo date("d") . "<br>";
	echo DDMMYYTOYYYYMMDD(date("Y-m-d"));*/

	echo("DDD");
	
	
	//Add user
	if($_POST['st'] == "add")
	{

		$n = array(
      	//'id' => "",
				'u_id' => trim($_POST['user_login']),
      	'u_pass' => sha1($_POST['pass_login']),
      	'u_name' => trim($_POST['accouont_name']),
      	'u_lastname' => "",
      	'u_type' => $_POST['account_type'],
      	'u_status' => ($_POST['account_status'] == "Y")? "Y":"N",
      	'u_image' => "",
      	'u_update' => DDMMYYTOYYYYMMDD(date("Y-m-d")),
      	'emp_id' => $_POST['emp_name']
		);
			/*
			$n = array(
      	'aaa' => "111",
				'b' => "222"
      	
			);*/

				echo("1");
			$conn = new class_db();
			echo("2");
			//print_r($n);
			$sql = "insert into user_login (u_id,u_pass,u_name,u_lastname,u_type,u_status,u_image,u_update,emp_id)  values(:u_id, :u_pass, :u_name, :u_lastname, :u_type, :u_status, :u_image, :u_update, :emp_id)";
			//$sql = "insert into user_login (id,u_id,u_pass,u_name,u_lastname,u_type,u_status,u_image,u_update,emp_id) values(?,?,?,?,?,?,?,?,?,?)";
			echo("3");
			//echo($sql);
			//print_r($conn);

			//$sql = " insert into user_a (aaa,b) values (:aaa,:b)";
			
			$result = $conn->execute_query_tran($sql, $n);

			//mysql_query($sql) or die(mysql_error());

			echo("4");
    	if ($result) 
    	{
    		//set alert css
    		//**************************************************************
      		$_SESSION['flash']['type'] = 'success';
      		$_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
      		//**************************************************************

      		$_SESSION['login']['id'] = trim($_POST['user_login']);
      		redirec_to('../user.php');
			}
			
			$conn = null;
	}
	else if($_POST['st'] == "edit")
	{
		
		$sql = "";
		//Edit
		if(is_sha1($_POST['pass_login']))
		{
			//Not Change Password
			$n = array(
			'u_id' => trim($_POST['user_login']),
      		'u_name' => trim($_POST['accouont_name']),
      		'u_lastname' => "",
      		'u_type' => $_POST['account_type'],
      		'u_status' => ($_POST['account_status'] == "Y")? "Y":"N",
      		'u_image' => "",
      		'u_update' => DDMMYYTOYYYYMMDD(date("Y-m-d")),
      		'emp_name' => $_POST['emp_name'],
      		'temp_u_name' => $_POST['temp_u_name']
    		);

			$sql .= "update user_login SET u_id =:u_id , u_name =:u_name , u_lastname =:u_lastname, u_type =:u_type, u_status =:u_status, u_image =:u_image, u_update =:u_update, emp_id =:emp_name "; 
		}
		else
		{
			//Change Password
			$n = array(
			'u_id' => trim($_POST['user_login']),
			'u_pass' => sha1($_POST['pass_login']),
      		'u_name' => trim($_POST['accouont_name']),
      		'u_lastname' => "",
      		'u_type' => $_POST['account_type'],
      		'u_status' => ($_POST['account_status'] == "Y")? "Y":"N",
      		'u_image' => "",
      		'u_update' => DDMMYYTOYYYYMMDD(date("Y-m-d")),
      		'emp_name' => $_POST['emp_name'],
      		'temp_u_name' => $_POST['temp_u_name']
    		);

			$sql .= "update user_login SET u_id =:u_id, u_pass =:u_pass , u_name =:u_name , u_lastname =:u_lastname, u_type =:u_type, u_status =:u_status, u_image =:u_image, u_update =:u_update, emp_id =:emp_name ";
		}
		
		$sql .= "where u_id =:temp_u_name";

		//echo $_POST['account_status'];
		
		$conn = new class_db();
    	$result = $conn->execute_query_tran($sql, $n);

    	if ($result) 
    	{
    		//set alert css
    		//**************************************************************
      		$_SESSION['flash']['type'] = 'success';
      		$_SESSION['flash']['msg'] = "บันทึกข้อมูลเรียบร้อยแล้ว";
      		//**************************************************************

      		$_SESSION['login']['id'] = trim($_POST['user_login']);

      		$conn = null;
      		$result = null;

      		redirec_to('../logout.php');
    	}
    	

	}
  else if($_GET['st'] == "del")
  {
      $n = array(
      'u_id' => trim($_GET['id'])
        );
    
    
      $sql = "delete FROM user_login where u_id =:u_id";

    
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

          redirec_to('../user.php');

      }

  }
?>