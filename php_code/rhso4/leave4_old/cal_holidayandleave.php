<?php
include_once 'connect/connect.php';
$select_typename2 = "SELECT SUM(sick_totalday)dy ,SUM(sick_totalhour)hr  
FROM tbl_holiday_detail
WHERE Emp_id = 1  AND Type_id = 2
AND sick_type='ขอลากิจส่วนตัว' and status_leave=1 
AND sick_startdate< '2019-08-13'";
$query_typename2 = mysqli_query($objconnect, $select_typename2);
$num_typename2 = mysqli_num_rows($query_typename2);
// echo 'string',$num_typename2;

$strStartDate = $_POST['Start_date'];
$strEndDate = $_POST['End_date'];

$intWorkDay = 0;
$intHoliday = 0;
$intTotalDay = ((strtotime($strEndDate) - strtotime($strStartDate)) /  (60 * 60 * 24)) + 1;
while (strtotime($strStartDate) <= strtotime($strEndDate)) {

    $DayOfWeek = date("w", strtotime($strStartDate));
    if ($DayOfWeek == 0 or $DayOfWeek == 6)  // 0 = Sunday, 6 = Saturday;
    {
        $intHoliday++;
        // echo "$strStartDate = <font color=red>Holiday</font><br>";
    } else {
        $intWorkDay++;
        // echo "$strStartDate = <b>Work Day</b><br>";
    }
    //$DayOfWeek = date("l", strtotime($strStartDate)); // return Sunday, Monday,Tuesday....

    $strStartDate = date("Y-m-d", strtotime("+1 day", strtotime($strStartDate)));
}

// echo "<hr>";
// echo "<br>Total Day = $intTotalDay";
// echo "<br>Work Day = $intWorkDay";
// echo "<br>Holiday = $intHoliday";

// echo "<hr>";
// echo "<br>Total Day = $intTotalDay";
$arr['result'] = $intHoliday;
//   echo "<br>Holiday = $intHoliday";
echo json_encode($arr);
