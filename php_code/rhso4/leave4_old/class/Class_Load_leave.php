<?php
class class_Load_leave extends class_db {
  function getAllData() {
    $n = array('id' => 'all');
    $sql = "select * from leave_list ll , leave_detail ld
            where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y' and ll.leave_approve <> 'Y'";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getAllDataLimit($goto, $each) {
    $n = array('id' => 'all');
    //$sql = "select * from employee order by id limit $goto, $each";
    $sql = "select ll.*,ld.*,CONCAT(emp.emp_name,' ',emp.emp_lastname) emp_name ,(select CONCAT(tm2.title_name,' ',CONCAT(emp2.emp_name,' ',emp2.emp_lastname)) leave_assign from employee emp2 , title_master tm2 where ld.leave_assign = emp2.emp_id and tm2.id = emp2.title_id) leave_assign1 from leave_list ll , leave_detail ld , employee emp";
    $sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y' and ll.leave_approve <> 'Y' ";
    $sql .= " and ll.leave_emp_id = emp.emp_id ";
    $sql .= " ORDER BY ld.leave_start DESC ";
    $sql .= " limit $goto, $each";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getDataLimitByID($goto, $each,$leave_emp_id) {
    $n = array('leave_emp_id' => $leave_emp_id);
    //$sql = "select * from employee order by id limit $goto, $each";
    $sql = "select ll.*,ld.*,CONCAT(emp.emp_name,' ',emp.emp_lastname) emp_name ,(select CONCAT(tm2.title_name,' ',CONCAT(emp2.emp_name,' ',emp2.emp_lastname)) leave_assign from employee emp2 , title_master tm2 where ld.leave_assign = emp2.emp_id and tm2.id = emp2.title_id) leave_assign1 from leave_list ll , leave_detail ld , employee emp";
    $sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y' and ll.leave_approve <> 'Y' and leave_emp_id =:leave_emp_id and ll.leave_emp_id = emp.emp_id ";
    $sql .= " ORDER BY ld.leave_start DESC ";
    $sql .= " limit $goto, $each";
    //echo $leave_emp_id;
    //echo $sql;
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getAccountByID($id) {
    $n = array('leave_emp_id' => $id);
    //$sql = " select * from employee where id = :id";
    $sql = " select * from leave_list ll , leave_detail ld ";
    $sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y' and ll.leave_approve <> 'Y'";
    $sql .= " and ll.leave_emp_id = :leave_emp_id";
    $result = $this->getOne($sql, $n);
    return $result;
  }

  function getCountAccount() {
    $n = array('id' => 'all');
    $sql = "select count(ll.leave_list_id) cc from leave_list ll , leave_detail ld";
    $sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y' and ll.leave_approve <> 'Y'";
    $result = $this->getOne($sql, $n);
    return $result;
  }

  function getCountAccountByID($leave_emp_id) {
    $n = array('leave_emp_id' => $leave_emp_id);
    $sql = "select count(ll.leave_list_id) cc from leave_list ll , leave_detail ld";
    $sql .= " where ll.leave_list_id = ld.leave_list_id and ll.leave_cancel <> 'Y' and ll.leave_approve <> 'Y' and ll.leave_emp_id =:leave_emp_id";
    $result = $this->getOne($sql, $n);
    return $result;
  }

  //get leave lastdate
  //*****************************************
  function Cal_leave_LastDay($leave_emp_id,$leave_type)
  {
     $ret_date = "";
     $n = array('leave_emp_id' => $leave_emp_id,

          'leave_type' => $leave_type

          );

     $sql = "select  leave_date from leave_list ";
     $sql .= " where leave_emp_id = :leave_emp_id and leave_type = :leave_type ";
     $sql .= " order by leave_date DESC ";
     $sql .= " LIMIT 0,1 ";
     $result = $this->getOne($sql, $n);

     if(isset($result))
     {
         $ret_date = $result["leave_date"];
     }

    return $ret_date;

  }

  //Cal leave ตัดและคืนวันลา
  function Cal_leave_Day($emp_id,$leave_type,$leave_total,$fiscal_year,$status)
  {
    
    $n = array(
      'leave_total' => $leave_total,
          'emp_id' => $emp_id,
          'fiscal_year' => $fiscal_year
      );

    //$sql = " update employee set ";
    $sql = " update leave_lmt_master set ";

    //echo $status . "<br>";

    //echo $leave_type . "<br>";
    
    switch ($leave_type) {
      case '1':
        //ลาพักผ่อน
      //echo "1";
        if($status == "D")
        {
          //echo "2";
          $sql .= " emp_holiday_lmt = (emp_holiday_lmt  - :leave_total) ";
        }
        else
        {
          //echo "3";
          $sql .= " emp_holiday_lmt = (emp_holiday_lmt  + :leave_total) ";
        }
        
       //echo "4";
        break;
      
      case '2':
        //ลาป่วย
        if($status == "D")
        {
          $sql .= " emp_leave_lmt = (emp_leave_lmt  - :leave_total) ";
        }
        else
        {
          $sql .= " emp_leave_lmt = (emp_leave_lmt  + :leave_total) ";
        }

        break;

      case '3':
        //ลากิจส่วนตัว
        if($status == "D")
        {
          $sql .= " emp_errand_leave_lmt = (emp_errand_leave_lmt  - :leave_total) ";
        }
        else
        {
          $sql .= " emp_errand_leave_lmt = (emp_errand_leave_lmt  + :leave_total) ";
        }
        

        break;

      case '4':
        //คลอดบุตร
        if($status == "D")
        {
          $sql .= " emp_maternity_leave_lmt = (emp_maternity_leave_lmt  - :leave_total) ";
        }
        else
        {
          $sql .= " emp_maternity_leave_lmt = (emp_maternity_leave_lmt  + :leave_total) ";
        }
        

        break;
    }

    $sql .= " where emp_id =:emp_id and fiscal_year =:fiscal_year";

    $result = $this->execute_query_tran($sql, $n);

    return $result;
  }


  function Get_leave_Day($leave_type,$leave_total,$temp_leave_type,$temp_leave_total)
  {
    
  }
}
?>