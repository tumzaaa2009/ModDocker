<?php
class class_load_report extends class_db {
    function get_report_summary_leave_($emp_id,$leave_year,$leave_month)
    {  
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
      $sql .= " and ld.lea_type_id = lm.lea_id";

      $result = $this->getAll($sql, $n);
      return $result;

    }


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

    

}
?>