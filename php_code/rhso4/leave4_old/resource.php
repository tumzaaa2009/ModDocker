<?php 

  if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 

include_once 'connect/connect.php';
    
    mysqli_set_charset($objconnect, "utf8");
////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////
$id_emp=$_GET["id_emp"];
$type=$_GET["type"];
// if ($_GET["type"]==1) {

$text = "
SELECT *,holiday_startdate,holiday_enddate,holiday_startime,holiday_endtime,tbl_type_hoilday.type_name as typename FROM tbl_holiday_detail
INNER JOIN tbl_type_hoilday ON tbl_type_hoilday.type_id = tbl_holiday_detail.type_id where 
tbl_holiday_detail.Emp_id= ".$id_emp."  and tbl_holiday_detail.status_leave > 0  ";


// }
// else{

//   $text = "SELECT * FROM tbl_holiday_detail where 
//   Emp_id='".$id_emp."' and type_id='".$type."'  " ;

// }
 
$result2 = mysqli_query($objconnect,$text); 


$events = array();
$num=mysqli_num_rows($result2);
// echo $num;
 $i = 0; 

    if ($num > 0) {

        while($row = mysqli_fetch_array($result2)) {
          
          if ($row["type_id"]==2) {
  

            $x= $row["sick_startdate"].' '.$row['sick_startime'];
            $y= $row["sick_enddate"].' '.$row["sick_endtime"];

            $start = str_replace(" ","T",$x);

            $end = str_replace(" ","T",$y);
        
             $events[$i]['id']=$row['hoilday_detail_id'];
             $events[$i]['title']=$row['sick_type'];
             $events[$i]['start']=$start;
             $events[$i]['end']=$end;
 


              
             $i++;
        


          }
          else{
    
           $x= $row["holiday_startdate"].' '.$row['holiday_startime'];
            $y= $row["holiday_enddate"].' '.$row["holiday_endtime"];
            $start = str_replace(" ","T",$x);
             $end = str_replace(" ","T",$y);    
                $events[$i]['id']=$row['hoilday_detail_id'];
                $events[$i]['title']=$row['typename'];
                $events[$i]['start']=$start;
                $events[$i]['end']=$end; 
          
             $i++;
        }
          }

    }
    



    mysqli_close($objconnect);
    
    // if(isset($_GET['resource'])){
    //     echo json_encode($resource);
    // }
    
    if(isset($_GET['events'])){
       echo json_encode($events);

    }
        exit;   

?>  
