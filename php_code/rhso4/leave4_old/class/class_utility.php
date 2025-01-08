<?php
  
  
  
	function redirec_to($url) 
	{
    	header('Location:'.$url);
    	exit();
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

function getMonthShortName($month_id) {
  $month_name = "";
  switch ($month_id) {
    case '1':
      $month_name = "ม.ค.";
      break;
    case '2':
      $month_name = "ก.พ.";
      break;
    case '3':
      $month_name = "มี.ค.";
      break;
    case '4':
      $month_name = "เม.ย.";
      break;
    case '5':
      $month_name = "พ.ค.";
      break;
    case '6':
      $month_name = "มิ.ย.";
      break;
    case '7':
      $month_name = "ก.ค.";
      break;
    case '8':
      $month_name = "ส.ค.";
      break;
    case '9':
      $month_name = "ก.ย.";
      break;
    case '10':
      $month_name = "ต.ค.";
      break;
    case '11':
      $month_name = "พ.ย.";
      break;
    case '12':
      $month_name = "ธ.ค.";
      break;
    default:
      break;
  }
  return $month_name;
}

function thainumDigit($num){
    return str_replace(array('0', '1', '2', '3', '4', '5', '6','7', '8', '9'),
    array("o", "๑", "๒", "๓", "๔", "๕", "๖", "๗", "๘", "๙"), $num);
}

function DDMMYYTOYYYYMMDD($strdate)
{
  $arrstrdate = explode("-", $strdate);

  //yyyy
  if((int) ($arrstrdate[0]) > 2500)
  {
     $stryyyymmdd =  (string) ((int) ($arrstrdate[0]) - 543);
  }
  else
  {
    $stryyyymmdd = $arrstrdate[0];
  }

  $stryyyymmdd .= $arrstrdate[1] . $arrstrdate[2];//mmdd

  return $stryyyymmdd;
  
}

function DDMMYYTOYYYYMMDD2($strdate,$strexplode)
{
  $arrstrdate = explode($strexplode, $strdate);

  //yyyy
  if((int) ($arrstrdate[0]) > 2500)
  {
     $stryyyymmdd =  (string) ((int) ($arrstrdate[0]) - 543);
  }
  else
  {
    $stryyyymmdd = $arrstrdate[0];
  }

  $stryyyymmdd .= $arrstrdate[1] . $arrstrdate[2];//mmdd

  return $stryyyymmdd;
  
}

function YYYYMMDDTOYYMMDD_2($strdate)
{
  //if(isset($strdate) && $strdate.lenght == 8)
  //{
    $year = substr($strdate, 0, 4);
    $month = substr($strdate, 4, 2);
    $day = substr($strdate, 6, 2);
    return $year . "-" . $month . "-" . $day;
  //}
}

function YYYYMMDDTOMMDDYY($strdate)
{
  //if(isset($strdate) && $strdate.lenght == 8)
  //{
    $year = substr($strdate, 0, 4);
    $month = substr($strdate, 4, 2);
    $day = substr($strdate, 6, 2);
    return  $month . "/" .  $day . "/" . $year;
  //}
}

function YYYYMMDDTODDMMYY($strdate)
{
  //if(isset($strdate) && $strdate.lenght == 8)
  //{
    $year = substr($strdate, 0, 4);
    $month = substr($strdate, 4, 2);
    $day = substr($strdate, 6, 2);
    return $day . "/" . $month . "/" . $year;
  //}
}



function Check_User_Role_Admin()
{
    $ret_ = false;
    //Check User
    //***************************************************
    if($_SESSION['login']['u_type'] == "1")
    {
        $ret_ = true;
    }
    else
    {
        $ret_ = false;
    }

    return $ret_;
}

function is_sha1($str) {
    return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
}

function getWeekday($date) {
  //echo getWeekday('2012-10-11'); // returns 4
  return date('w', strtotime($date));
}


  

?>