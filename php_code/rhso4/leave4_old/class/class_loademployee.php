<?php
class class_loademployee extends class_db {
  function getAllData() {
    $n = array('id' => 'all');
    $sql = "select * from employee";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getAllDataLimit($goto, $each) {
    $n = array('id' => 'all');
    //$sql = "select * from employee order by id limit $goto, $each";
    $sql = "select emp.*,af.affiliate_name,wk.work_name,emp_type.type_name, lmt.* from employee emp, leave_lmt_master lmt , affiliation af , work_group wk , employee_type_master emp_type ";
    $sql .= " where emp.affiliate_id = af.affiliate_id and emp.work_id = wk.work_id and emp.emp_id = lmt.emp_id and emp.type_id = emp_type.id";
    $sql .= " limit $goto, $each";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getAccountByID($id) {
    $n = array('id' => $id);
    $sql = "select * from employee where id = :id";
    $result = $this->getOne($sql, $n);
    return $result;
  }

  function getEmployeeByID($emp_id) {
    $n = array('emp_id' => $emp_id);
    $sql = "select * from employee where emp_id = :emp_id";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getCountAccount() {
    $n = array('id' => 'all');
    $sql = "select count(id) cc from employee";
    $result = $this->getOne($sql, $n);
    return $result;
  }

  
}