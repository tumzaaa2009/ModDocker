<?php

	class class_db {
		private $conn;
		private $host;
		private $user;
		private $pass;
		private $dbname;
		private $port;
		private $debug;

		function __construct($params=array()){
			$this->conn = false;
			$this->host = 'localhost';
			$this->user = 'root';
			$this->pass = '';//1234
			$this->dbname = 'leave_db';
			$this->port = '3306';
			$this->debug = true;
			$this->connect();
		}

		function __destruct()
		{
			if($this->conn)
			{
				$this->conn = null;
			}
		}

		function connect()
		{
			if(!$this->conn)
			{
				try
				{
					$this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname.'', $this->user, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
				}catch (Exception $e)
				{
					die('Error : '.$e->getMessage());
				}

				if (!$this->conn) {
        			$this->status_fatal = true;
        			echo 'Connection failed';
        			die();
      			} else 
      			{
        			$this->status_fatal = false;
      			}
			}

			return $this->conn; 
		}



		function getOne($sql,$n)
		{
			$result = $this->conn->prepare($sql);
    		$ret = $result->execute($n);
    		if (!$ret) 
    		{
      			echo 'PDO::errorInfo():';
      			echo '<br />';
      			echo 'Error SQL : '.$sql;
      			die();
    		}
    		$result->setFetchMode(PDO::FETCH_ASSOC);
    		$response = $result->fetch();
    		return $response;
		}

		function getAll($sql, $n) 
		{
    		$result = $this->conn->prepare($sql);
    		$ret = $result->execute($n);
    		if (!$ret) 
    		{
      			echo 'PDO::errorInfo():';
      			echo '<br />';
      			echo 'Error SQL : '.$sql;
      			die();
    		}
    		$result->setFetchMode(PDO::FETCH_ASSOC);
    		$response = $result->fetchAll();
    		return $response;
  		}

  		function execute_query($sql, $n) 
  		{
  			//We start our transaction.
			//$this->conn->beginTransaction();

			//We've got this far without an exception, so commit the changes.
    		//$this->conn->commit();

    		//Our catch block will handle any exceptions that are thrown
    		//Rollback the transaction.
    		//$this->conn->rollBack();

			$result = $this->conn->prepare($sql);
			$ret = $result->execute($n);
			if (!$ret) 
			{
				echo 'PDO::errorInfo():';
		   		echo '<br />';
		   		echo 'Error SQL : '.$sql;
		   		die();
			}
			return $ret;
		}

		function execute_query_tran($sql, $n) 
  		{

  			$msg_err = "";
  			echo "OK1" ."<BR>";
    		try 
    		{
    			//We start our transaction.
				$this->conn->beginTransaction();
				echo "OK111" ."<BR>";
				$result = $this->conn->prepare($sql);
				echo "OK112" ."<BR>";
				$ret = $result->execute($n);
				echo "OK113" ."<BR>";
				if (!$ret) 
				{
					echo "OK114" ."<BR>";
					$ret = false;
					   //die(mysql_error());
				}
				echo "OK115" ."<BR>";

			} catch (Exception $e) 
			{
  				//handle pdo exception
  				//Our catch block will handle any exceptions that are thrown
    			//Rollback the transaction.
    			$this->conn->rollBack();
    			$ret = false;
				$msg_err = $e->getMessage();

			}catch (Exception $ex) 
			{
  				//handle others differently
  				$ret = false;
				  $msg_err = $ex->getMessage();
			}

			if($ret)
			{
				//We've got this far without an exception, so commit the changes.
    			$this->conn->commit();
    			echo "OK2" ."<BR>";
			}
			else
			{
				echo "PDO::errorInfo():";
		   		echo "<br />";
		   		echo "Error SQL : ".$msg_err.$sql;
			}

			
			return $ret;
		}

		function cal_fiscal_year()
		{
			$Y = (int) date("Y");
			$m = (int) date("m");
			$d = (int) date("d");

			//echo gettype($Y). "<br>";

			//echo gettype($m). "<br>";

			//echo gettype($d). "<br>";

			//between month 10-12 ต้องปีใหม่ ต้อง +1 and 1-9  ปีเดียวของบ
			if($m >= 10 && $m <= 12)
			{
				return $Y + 1;
			}
			else
			{
				return $Y;
			}
		}

	}


?>