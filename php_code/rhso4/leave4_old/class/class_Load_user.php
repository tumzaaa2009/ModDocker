<?php
class class_Load_user extends class_db {
  function getAllData() {
    $n = array('id' => 'all');
    $sql = "select * from account";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getAllDataLimit($goto, $each) {
    $n = array('id' => 'all');
    $sql = "select * from user_login order by id limit $goto, $each";
    $result = $this->getAll($sql, $n);
    return $result;
  }

  function getAccountByID($id) {
    $n = array('id' => $id);
    $sql = "select * from user_login where id = :id";
    $result = $this->getOne($sql, $n);
    return $result;
  }

  function getCountAccount() {
    $n = array('id' => 'all');
    $sql = "select count(id) cc from user_login";
    $result = $this->getOne($sql, $n);
    return $result;
  }
}