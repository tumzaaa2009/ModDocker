<?php
    include '../check_session_login.php';
    include '../class/class_utility.php';
    include '../class/class_db.php';
    //include '../class/class_load_report.php';
    
 

    //echo $_POST['emp_id'] .'<BR>';
    //echo $_POST['ddl_year'] .'<BR>';
    //echo $_POST['ddl_month'] .'<BR>';

    $emp_id = $_POST['emp_id'];
    $v_month = $_POST['ddl_month'];
    $v_year = $_POST['ddl_year'];

    $report_type = $_POST['report_type'];
    if ($report_type == "pdf") {
        require('../fpdf/write_html.php');
        //echo "A";
        ob_start();
    }


    if ($report_type == "print" || $report_type == "excel") {
        if ($report_type == "excel") {
          $strExcelFileName = $_POST['emp_id']."_".date("YmdHis").".xls";
          header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
          header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
          header("Pragma:no-cache");
        }

    ?>

<style media="screen">
  .txtNormal {
    font-size: 18px;
    color: #000;
    font-family: "TH SarabunPSK", "tahoma";
  }

  .txtBold {
    font-size: 20px;
    font-weight: bold;
    color: #000;
    font-family: "TH SarabunPSK", "tahoma";
  }

  .txtBoldBig {
    font-size: 24px;
    font-weight: bold;
    color: #000;
    font-family: "TH SarabunPSK", "tahoma";
  }

  .kp {
    font-size: 20px;
    color: #000;
    font-family: "Wingdings", "tahoma";
  }
</style>

<?php
    }
?>

<meta charset="utf-8" />
<div style="text-align: center;">
<span class="txtBoldBig" >รายงานสรุปวันลา <?php echo $_POST['emp_name']; ?> เดือน <?php echo getMonthFullName($v_month); ?> พ.ศ. <?php echo thainumDigit(($v_year + 543)); ?></span>
</div>
<table cellspacing="0" cellpadding="3" width="100%">

<tr bgcolor="#3399FF" class="txtBold">
  <td style="border-top:1px solid #000; border-left:1px solid #000; border-bottom:1px solid #000;  border-right:1px solid #000;" align="center">เดือน</td>
  
  <?php
    for ($i = 1 ; $i<= 31 ; $i++)
    { 
      ?>
      <td style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;" align="center"><?php echo $i; ?></td>
  <?php  
    }
  ?>
</tr>

<!-- bind data -->
<?php 

    //$class_load_report = new class_load_report();
    //$rs_summary = $class_load_report -> get_report_leave_summary($emp_id,$v_month,$v_year);

    $rs_summary = get_report_leave_summary($emp_id,$v_year,$v_month);
    $loop = 0;
    if(is_array($rs_summary))
    {
      $temp_year= "";
      $temp_month = "";
      //array เก็บค่าออก Report
      //$reportsum = array_keys($rs_summary);
      $temp_arry = array();
      $reportsum = get_structure_array_rptsum();
      
      foreach ($rs_summary as $v => $v_value) 
      { 
        //วน ตาม record ที่ได้จาก Query
        //echo $v_value['leave_list_id'] . "<BR>";

        if($v_value['leave_year'] == $temp_year && $v_value['leave_month'] == $temp_month)
        {
          //เดือนเท่ากัน กรณีเดือนนั้นลามากกว่า 1 วัน
          for($i = 1; $i <= 31; $i++)
          {
            if($v_value['leave_day'] == $i)
            {
              $reportsum['day_'.$i] = $v_value['lea_name_short'];
            }
          }
          echo "More Day" ."<BR>";
        }
        else
        {
          //วนคั้งแรก
          if($loop == 0)
          {
             //เดือนไม่เท่ากัน ต้องแอด New Rows ต้องเช็คครั้งแรก
             /*
              $temp_year = $v_value['leave_year'];
              $temp_month = $v_value['leave_month'];
              $reportsum['leave_year'] = $v_value['leave_year'];
              $reportsum['leave_month'] = $v_value['leave_month'];
              for($i = 1; $i <= 31; $i++)
              {
                if($v_value['leave_day'] == $i)
                {
                  $reportsum['day_'.$i] = $v_value['lea_name_short'];
                }
              }
              */
              bind_data_newrows($temp_year,$temp_month,$reportsum,$v_value);

            //$loop += 1;
          }
          else
          {
            //เดือนไม่เท่ากันต้องแอด New Rows
            //$temp_arry = $temp_arry + $reportsum;
            //array_push($temp_arry ,$reportsum);
            //unset($reportsum);
            $temp_arry[] = $reportsum;
            $reportsum = get_structure_array_rptsum();
            bind_data_newrows($temp_year,$temp_month,$reportsum,$v_value);

            /*
            $temp_year = $v_value['leave_year'];
              $temp_month = $v_value['leave_month'];
              $reportsum['leave_year'] = $v_value['leave_year'];
              $reportsum['leave_month'] = $v_value['leave_month'];
              for($i = 1; $i <= 31; $i++)
              {
                if($v_value['leave_day'] == $i)
                {
                  $reportsum['day_'.$i] = $v_value['lea_name_short'];
                }
              }
              */
              
          }
          echo "New Record"."<BR>";
        }
        $loop += 1;
        if(count($rs_summary) == $loop)
        {
          //$temp_arry += $reportsum;
          $temp_arry[] = $reportsum;
          echo "Finish";
        }
        
      }

      //bind to HTML

      print_r($temp_arry);
      //echo strrpos("5,6,12,13,19,20,26,27","2")."GGG";
      //$pieces = explode(",","5,6,12,13,19,20,26,27");
      //print_r($pieces);
      //$arr_dayofweek = null;
      foreach ($temp_arry as $v) 
      { 
        //echo $v['emp_name'] ;
        //echo $v['leave_day'];
        //echo $v['lea_name'];
        //get dayofweek
        //$arr_dayofweek = explode(",", $v['dayofweek']);
?>
      <!-- row -->
      <tr class="txtBold">
      <td style="border-top:1px solid #000; border-left:1px solid #000;  border-bottom:1px solid #000; border-right:1px solid #000;" align="center"><?php echo $v['leave_month']; ?></td>
      <?php
          for($i = 1 ; $i<= 31 ; $i++)
          { 
            if($v['day_'.$i] <> "" && $v['day_'.$i] <> "H")
            { //0,6 sun,sat
             ?>
              <td style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;background-color:yellow;" align="center"><?php echo $v['day_'.$i]; ?></td>
            <?php
              
            }
            else
            { //0,6 sun,sat
              if($v['day_'.$i] == "H")
              {
             
             ?>
              <td style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;background-color:red;" align="center"></td>
              <?php
               }
               else
               { ?>
                  <td style="border-top:1px solid #000; border-bottom:1px solid #000; border-right:1px solid #000;" align="center"></td>
              <?php
               }
            }

            
          }
        ?>
      <!-- end row -->
      </tr>
      <?php
      }
     
    }  
      ?>



<tr class="txtBold">
</tr>

</table>

<?Php
if ($report_type == "pdf") {
    /*
  //echo "B";
  $html = 'You can now easily print text mixing different styles: <b>bold</b>, <i>italic</i>,
<u>underlined</u>, or <b><i><u>all at once</u></i></b>!<br><br>You can also insert links on
text, such as <a href="http://www.fpdf.org">www.fpdf.org</a>, or on an image: click on the logo.';

$pdf = new PDF_HTML();
// First page
$pdf->AddPage();
$pdf->SetFont('Arial','',20);
$pdf->Write(5,"To find out what's new in this tutorial, click ");
$pdf->SetFont('','U');
$link = $pdf->AddLink();
$pdf->Write(5,'here',$link);
$pdf->SetFont('');
// Second page
$pdf->AddPage();
$pdf->SetLink($link);
//$pdf->Image('logo.png',10,12,30,0,'','http://www.fpdf.org');
$pdf->SetLeftMargin(45);
$pdf->SetFontSize(14);
$pdf->WriteHTML($html);
$pdf->Output();
*/
}
?>

<?php

function bind_data_newrows(&$temp_year,&$temp_month,&$reportsum,$v_value)
{
  $temp_year = $v_value['leave_year'];
  $temp_month = $v_value['leave_month'];
  $reportsum['leave_year'] = $v_value['leave_year'];
  $reportsum['leave_month'] = getMonthShortName($v_value['leave_month']);
  //$reportsum['leave_month'] = $v_value['leave_month'];
  $ret_dayofweek = "";
  for($i = 1; $i <= 31; $i++)
  {
    if($v_value['leave_day'] == $i)
    {
      $reportsum['day_'.$i] = $v_value['lea_name_short'];
    }

    //Get Day Of week
    if($i <= 9)
    {
      $ret_dayofweek = getWeekday($v_value['leave_year'].$v_value['leave_month']."0".$i);
    }
    else
    {
      $ret_dayofweek = getWeekday($v_value['leave_year'].$v_value['leave_month'].$i);
    }

    if($ret_dayofweek == 0 || $ret_dayofweek == 6)
    {
      //$reportsum['day_'.$i] = "H";

      /*
      if($reportsum['dayofweek'] == "")
      {
        $reportsum['dayofweek'] = $i;
      }
      else
      {
        $reportsum['dayofweek'] .= ",".$i;
      }*/
    }
    

  }
}

function get_report_leave_summary($emp_id,$leave_year,$leave_month)
{  
  $class_db = new class_db();
  $n = array(
    'emp_id' => $emp_id,
    'leave_year' => $leave_year,
    'leave_month' => $leave_month
  );

  $sql = " select ll.leave_list_id , ll.leave_year , ll.leave_month , ll.leave_day , ll.leave_date ";
  $sql .= " , ld.emp_id , tm.title_name , CONCAT(emp.emp_name,' ',emp_lastname) emp_name  , ld.lea_type_id ";
  $sql .= " , lm.lea_name ";
  $sql .= " , CASE ld.lea_type_id ";
  $sql .= " WHEN 1 THEN 'พ' ";
	$sql .= " WHEN 2 THEN 'ป' ";
  $sql .= " WHEN 3 THEN 'ก' ";
	$sql .= " ELSE 'ค'  END lea_name_short ";
  $sql .= " , ld.leave_start , ld.leave_end ";
  $sql .= " , ld.leave_day_total,leave_detail ,ld.leave_remark,ld.leave_assign,leave_half_detail ";

  $sql .= " from leave_list ll , leave_detail ld , employee emp ,title_master tm , master_leave lm ";
  $sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y'  and ld.emp_id = emp.emp_id and emp.title_id = tm.id ";
  $sql .= " and ld.emp_id = :emp_id and ll.leave_year = :leave_year and leave_month = :leave_month ";
  //$sql .= " and ld.emp_id = :emp_id and ll.leave_year = :leave_year  ";
  $sql .= " and ld.lea_type_id = lm.lea_id";

  $result = $class_db->getAll($sql, $n);
  echo $sql . "<BR>";
  //print_r($n);
   print_r($result);
   echo "<BR>";
   echo count($result)." Total Count";
  return $result;

}



 function  get_structure_array_rptsum()
{
  $reportsum = array(
    'leave_year' => '',
    'leave_month' => '',
    'dayofweek' => '',
    'day_1' => '',
    'day_2' => '',
    'day_3' => '',
    'day_4' => '',
    'day_5' => '',
    'day_6' => '',
    'day_7' => '',
    'day_8' => '',
    'day_9' => '',
    'day_10' => '',
    'day_11' => '',
    'day_12' => '',
    'day_13' => '',
    'day_14' => '',
    'day_15' => '',
    'day_16' => '',
    'day_17' => '',
    'day_18' => '',
    'day_19' => '',
    'day_20' => '',
    'day_21' => '',
    'day_22' => '',
    'day_23' => '',
    'day_24' => '',
    'day_25' => '',
    'day_26' => '',
    'day_27' => '',
    'day_28' => '',
    'day_29' => '',
    'day_30' => '',
    'day_31' => '',
  );

  return $reportsum;
}

?>