<?php
if (!isset($_SESSION)) {
  session_start();
}
include 'header.php';
include 'connect/connect.php';
 
/////////////////////////////////////////////////////////////////////ดึงวันลาที่เหลืออยู่
$select_calholiday = "SELECT * FROM  tbl_total_limit_holiday AS countlimit 
WHERE countlimit.Emp_id ='" . $_SESSION["Emp_id"] . "' and countlimit.status_limit=1";
$querry_calholiday = mysqli_query($objconnect, $select_calholiday);
$numcheck_calholiday = mysqli_num_rows($querry_calholiday);
$featch_calholiday = mysqli_fetch_array($querry_calholiday);
// echo $numcheck_calholiday;

if ($numcheck_calholiday == 0) { //num เช็ค user emp_id เข้าระบบแรก ถ้าไม่มี id นี้จะสร้าง insert ใหม่ขึ้น
  $insertmaxcount = "SELECT MAX(convert(count_limit_holiday_id,SIGNED))id FROM tbl_total_limit_holiday ";
  $queryinsertmaxcount = mysqli_query($objconnect, $insertmaxcount);
  $featchinsertmaxcount = mysqli_fetch_array($queryinsertmaxcount);
  $maxcount = $featchinsertmaxcount["id"] + 1;


  if ($_SESSION["typename"]) {

    if ($_SESSION["Username"] == "pratoom" || $_SESSION["Username"] == "panittrah") {
      $count_oldyear_hoilday = 20;
      $count_year_holiday = 10;
      $sum_thisyear_holiday =  $count_oldyear_hoilday + $count_year_holiday;
    } else if ($_SESSION["typename"] == "ข้าราชการ") {
      $count_oldyear_hoilday = 10;
      $count_year_holiday = 10;
      $sum_thisyear_holiday =  $count_oldyear_hoilday + $count_year_holiday;
    } else {
      $count_oldyear_hoilday = 5;
      $count_year_holiday = 10;
      $sum_thisyear_holiday =  $count_oldyear_hoilday + $count_year_holiday;
    }

    $holiday_detail_insert = "INSERT INTO tbl_total_limit_holiday
 (count_limit_holiday_id
 ,Emp_id
 ,group_id
 ,register_day
 ,start_holiday
 ,count_oldyear_hoilday
 ,fix_count_oldyear_holiday
 ,count_year_holiday
 ,sum_thisyear_holiday
 ,end_holiday
 ,status_limit
  )
    VALUES 
    ('" . $maxcount . "' 
    ,'" . $_SESSION["Emp_id"] . "'
    ,'" . $_SESSION["groupid"] . "'
    ,NOW()
    ,'" . $datestart . "' 
    ,'" . $count_oldyear_hoilday . "'
    ,'" . $count_oldyear_hoilday . "'
    ,'" . $count_year_holiday . "'
    ,'" . $sum_thisyear_holiday . "'
    ,'" . $dateend . "' 
    ,1
  ) ";

    // echo 'if',$sum_thisyear_holiday;

    $query_horidayinsert = mysqli_query($objconnect, $holiday_detail_insert);
  } //if เช็ค type


  // echo 'ifnewซิงค์';
  // echo 'insertsessuess';
} //จบ$numcheck_calholiday==0
if ($numcheck_calholiday == 1) { //insert ปีงบใหม่ล่ะ ตัดวันถ้าวัน มากกว่า>=5 insert 5 ถ้าน้อยกว่า เท่านั้น
  $numholiday = 0;
  $numleave = 0;
  if ($featch_calholiday["end_holiday"] < $datenow) {

    if ($featch_calholiday["sum_thisyear_holiday"] != '') {

      if ($_SESSION["typename"] == "ข้าราชการ") {

        if ($featch_calholiday["sum_thisyear_holiday"] >= 20) {
          $numholiday = 10;
        } else {
          $numholiday = $featch_calholiday["sum_thisyear_holiday"];
        }
      } else {
        echo "พนง";
        if ($featch_calholiday["sum_thisyear_holiday"] >= 5) {
          $numholiday = 5;
        } else {
          $numholiday = $featch_calholiday["sum_thisyear_holiday"];
        }
      }
      //  if ($featch_calholiday["sum_thisyear_leave"]>=5) {
      //    $numleave=5;


      // }else  {
      //    $numleave=$featch_calholiday["sum_thisyear_leave"];

      // }

      $updatexprielimitholiday = "Update tbl_total_limit_holiday as countlimit 
        SET countlimit.status_limit =0  WHERE countlimit.Emp_id ='" . $_SESSION["Emp_id"] . "' and countlimit.status_limit=1";
      mysqli_query($objconnect, $updatexprielimitholiday);


      // insert limitใหม่ โดย key id ตาม max ของ id user นั้นๆ
      $selectmaxid = "SELECT MAX(convert(count_limit_holiday_id,SIGNED)) id FROM tbl_total_limit_holiday ";
      $querymaxid = mysqli_query($objconnect, $selectmaxid);
      $featchmaxid = mysqli_fetch_array($querymaxid);
      $maxid = $featchmaxid["id"] + 1;
      ///ประกาศตัวแปร sum holiday
      $thisyearholiday = 10;
      $sumtotalyearholiday = $thisyearholiday + $numholiday;

      $thisyearleave = 10;
      $sumtotayearleave = $thisyearleave + $numleave;

      // echo $sumtotayearleave;

      ///

      $insertnew_limit = "INSERT INTO tbl_total_limit_holiday
        (count_limit_holiday_id
        ,Emp_id
        ,group_id
        ,register_day
        ,start_holiday
        ,count_oldyear_hoilday
        ,fix_count_oldyear_holiday
        ,count_year_holiday
        ,sum_thisyear_holiday
        ,end_holiday
        ,status_limit
        )
          VALUES 
          ('" . $maxid . "' 
          ,'" . $_SESSION["Emp_id"] . "'
           ,'" . $_SESSION["groupid"] . "'
           ,NOW()
          ,'" . $datestart . "' 
          ,'" . $numholiday . "'
          ,'" . $numholiday . "'
          ,'" . $thisyearholiday . "'
          ,'" . $sumtotalyearholiday . "'
          ,'" . $dateend . "' 
          ,1
        ) ";
      mysqli_query($objconnect, $insertnew_limit);
    }
  }
}  //ปิด if numcheck==1
/////////////////////////////////////////////////////////////////////ENDดึงวันลาที่เหลืออยู่
//รับค่ามาเพื่อ gen
if (isset($_POST["submit_gen_facel_year"])) {


  // ปิดปีงบเดิม แล้ว เซท ค่า สเตตัสเป็น 0 แล้วสร้างค่า value วันหยุดใหม่
  $close_max_id = "SELECT MAX(convert(count_limit_holiday_id,SIGNED))id FROM tbl_total_limit_holiday ";
  $queryclose_max_id = mysqli_query($objconnect, $close_max_id);
  $featchclose_max_id = mysqli_fetch_array($queryclose_max_id);
 

   $select_genfinacal_year = "SELECT Financial_year FROM tbl_total_limit_holiday WHERE status_limit ='1' ORDER BY Financial_year DESC";
  $query_genfinacal_year = mysqli_query($objconnect,$select_genfinacal_year);
  $fetch_genfinacal_year = mysqli_fetch_array($query_genfinacal_year);

   $max_Financial_year = $fetch_genfinacal_year["Financial_year"]-543;
   $new_Financial_year = $fetch_genfinacal_year["Financial_year"]-542;
$sumnew_ficnacial_year = $fetch_genfinacal_year["Financial_year"]+1;
  $datestart = '' . $max_Financial_year . '-10-01'; //วันเปิดระบบ (register_start)
  $dateend =   '' . $new_Financial_year . '-09-30'; //วันปิดระบบ (register end)
   $end_close_date = '' . $new_Financial_year . '-09-30'; //วันปิดระบบ เพื่อเช็ค เข้าเงือนไข update ปีงบใหม่

  // end max_id
                                                                    // ข้าราชการ
      $close_fiscal_year = 'SELECT * FROM tbl_total_limit_holiday AS countlimit INNER JOIN tbl_employment emp ON emp.Emp_id=countlimit.Emp_id
      WHERE countlimit.status_limit=1    AND emp.Emp_Status =1 AND Financial_year ='.$fetch_genfinacal_year["Financial_year"].'  ORDER BY CAST(countlimit.group_id AS UNSIGNED) ASC,CAST(countlimit.Emp_id AS UNSIGNED) ASC ';
  $query_close_fiscal_year = mysqli_query($objconnect, $close_fiscal_year);
  $numcheck_close_fiscal_year = mysqli_num_rows($query_close_fiscal_year);
  //id มี 31 จำนวน
  if ($_SESSION["Username"] == "admin") {
    $sql_insert = "INSERT INTO tbl_total_limit_holiday (count_limit_holiday_id,Emp_id,group_id,fix_count_oldyear_holiday,count_year_holiday,sum_thisyear_holiday,register_day,start_holiday,end_holiday,status_limit,Financial_year)  VALUES";
    $sql_lessthanInsert = "INSERT INTO tbl_total_limit_holiday (count_limit_holiday_id,Emp_id,group_id,fix_count_oldyear_holiday,count_year_holiday,sum_thisyear_holiday,register_day,start_holiday,end_holiday,status_limit,Financial_year)  VALUES";
    $sql_insert2 = "INSERT INTO tbl_total_limit_holiday (count_limit_holiday_id,Emp_id,group_id,fix_count_oldyear_holiday,count_year_holiday,sum_thisyear_holiday,register_day,start_holiday,end_holiday,status_limit,Financial_year)  VALUES"; 
    $sql_insert2_lessthanInsert ="INSERT INTO tbl_total_limit_holiday (count_limit_holiday_id,Emp_id,group_id,fix_count_oldyear_holiday,count_year_holiday,sum_thisyear_holiday,register_day,start_holiday,end_holiday,status_limit,Financial_year)  VALUES"; 
    $sql_insert3 = "INSERT INTO tbl_total_limit_holiday (count_limit_holiday_id,Emp_id,group_id,fix_count_oldyear_holiday,count_year_holiday,sum_thisyear_holiday,register_day,start_holiday,end_holiday,status_limit,Financial_year)  VALUES"; 
    $sql_insert3_lessthanInsert ="INSERT INTO tbl_total_limit_holiday (count_limit_holiday_id,Emp_id,group_id,fix_count_oldyear_holiday,count_year_holiday,sum_thisyear_holiday,register_day,start_holiday,end_holiday,status_limit,Financial_year)  VALUES"; 
    $countCheckGroup1_30 = 1;
    $countCheckGroup1_30_lessthan = 1; 
    $countCheckGroup1_20 = 1;
    $countCheckGroup1_20_lessthan = 1;
    $countCheckGroup2_15 = 1;
    $countCheckGroup2_15_lessthan = 1;
 

    $gen_idlimit= $featchclose_max_id["id"]+1;
    while ($row_close = mysqli_fetch_array($query_close_fiscal_year)) {
      $count_limit_holiday_id = $row_close['count_limit_holiday_id'] + 1;
      $emp_id = $row_close['Emp_id'];
      $group_id = $row_close["group_id"];
      $reslut_year_old = $row['sum_thisyear_holiday'];
      $status_limit = "1";
      // ข้าราขการ
        if($row_close["group_id"]=="1" && $row_close["Emp_limit30day"]=="1" ){
              if($row_close['sum_thisyear_holiday']>=20) {
                // echo "มากกว่า 20";
                if ($countCheckGroup1_30 == 1) {
                  $sql_insert = $sql_insert . "('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','20','10','30',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                  } else {
                  $sql_insert = $sql_insert . ",('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','20','10','30',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                  }
                  
                  $countCheckGroup1_30 = $countCheckGroup1_30+1;
              }
              else if ($row_close['sum_thisyear_holiday']<20){
                // echo "น้อยกว่า20";
                if ($countCheckGroup1_30_lessthan == 1) {
                  $result_this_year_lessthan = $row_close['sum_thisyear_holiday'] + 10;
                  $sql_lessthanInsert = $sql_lessthanInsert . "('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','" . $row_close['sum_thisyear_holiday'] ."','10','" . $result_this_year_lessthan . "',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                } else {
                  $result_this_year_lessthan = $row_close['sum_thisyear_holiday'] + 10;
                  $sql_lessthanInsert = $sql_lessthanInsert . ",('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','" . $row_close['sum_thisyear_holiday'] ."','10','" . $result_this_year_lessthan . "',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                }
                $countCheckGroup1_30_lessthan = $countCheckGroup1_30_lessthan+1;
              }
     
          $gen_idlimit  = $gen_idlimit +1 ;
          // จบวันลา ราชการ 30 วัน
        }
        if($row_close["group_id"]=="1" && $row_close["Emp_limit30day"]=="0" ){
            if($row_close['sum_thisyear_holiday']>=10){
              if ($countCheckGroup1_20 == 1) {
                $sql_insert2 = $sql_insert2 . "('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','10','10','20',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                } else {
                $sql_insert2 = $sql_insert2 . ",('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','10','10','20',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                }
                $countCheckGroup1_20 = $countCheckGroup1_20+1;
            }else if ($row_close['sum_thisyear_holiday']<10){
              if ($countCheckGroup1_20_lessthan == 1) {
                $result_this_year_lessthan = $row_close['sum_thisyear_holiday'] + 10;
                $sql_insert2_lessthanInsert = $sql_insert2_lessthanInsert . "('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','" . $row_close['sum_thisyear_holiday'] ."','10','" . $result_this_year_lessthan . "',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
              } else {
                $result_this_year_lessthan = $row_close['sum_thisyear_holiday'] + 10;
                $sql_insert2_lessthanInsert = $sql_insert2_lessthanInsert . ",('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','" . $row_close['sum_thisyear_holiday'] ."','10','" . $result_this_year_lessthan . "',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
              }
              $countCheckGroup1_20_lessthan = $countCheckGroup1_20_lessthan+1;
            }
            $gen_idlimit  = $gen_idlimit +1 ;
        }

        // พนักงานราช
        if($row_close["group_id"]=="2"){
            if($row_close['sum_thisyear_holiday']>=5){
              if ($countCheckGroup2_15 == 1) {
                $sql_insert3 = $sql_insert3 . "('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','5','10','15',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                } else {
                $sql_insert3 = $sql_insert3 . ",('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','5','10','15',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
                }
                $countCheckGroup2_15 = $countCheckGroup2_15+1;
            }else if ($row_close['sum_thisyear_holiday']<5){
              if ($countCheckGroup2_15_lessthan == 1) {
                $result_this_year_lessthan = $row_close['sum_thisyear_holiday'] + 10;
                $sql_insert3_lessthanInsert = $sql_insert3_lessthanInsert . "('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','" . $row_close['sum_thisyear_holiday'] ."','10','" . $result_this_year_lessthan . "',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
              } else {
                $result_this_year_lessthan = $row_close['sum_thisyear_holiday'] + 10;
                $sql_insert3_lessthanInsert = $sql_insert3_lessthanInsert . ",('" . $gen_idlimit . "','" . $emp_id . "','" . $group_id . "','" . $row_close['sum_thisyear_holiday'] ."','10','" . $result_this_year_lessthan . "',NOW(),'" . $datestart . "','" . $dateend . "','" . $status_limit . "','" . $sumnew_ficnacial_year . "')";
              }
              $countCheckGroup2_15_lessthan = $countCheckGroup2_15_lessthan+1;
            }

            $gen_idlimit  = $gen_idlimit +1 ; 
          }

    }
    
// echo  $sql_insert."<br>";
// echo  $sql_lessthanInsert."<br>";
// echo $sql_insert2."<br>";
//  echo  $sql_insert2_lessthanInsert."<br>";
// echo  $sql_insert3_lessthanInsert."<br>";
 
  $sql_update3 = " Update tbl_total_limit_holiday set status_limit ='0' Where Financial_year =".$fetch_genfinacal_year["Financial_year"]."";
   mysqli_query($objconnect, $sql_update3);
   mysqli_query($objconnect, $sql_insert);
   mysqli_query($objconnect, $sql_lessthanInsert);
   mysqli_query($objconnect, $sql_insert2);
   mysqli_query($objconnect, $sql_insert2_lessthanInsert);
   mysqli_query($objconnect, $sql_insert3);
   mysqli_query($objconnect, $sql_insert3_lessthanInsert);


?>
<!-- <script>
      alert('ครั้งเดียวต่อหนึ่งปีงบจ้าาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาาา');
    </script> -->
<?php }
  //END iSSET
}
?>

<style>
#calendar {
    max-width: 75%;
    margin: auto;
    margin-top: 2%;
}
</style>



<?php
// include 'footer.php' ; 
?>

<script type="text/javascript">
window.jQuery || document.write("<script src='assets/js/jquery.min.js'>" + "<" + "/script>");
</script>


<script type="text/javascript">
if ('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>" +
    "<" + "/script>");
</script>

<script>
function go_page(p) {
    window.location.assign(p);
}


</script>
