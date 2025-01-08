<?php
/**** Connect DB *********/
//$objConnect = mssql_connect("barcode_server","WMS_web","Tpipl2010") or die("Error Connect to Database");
//$objDB = mssql_select_db("TPIPL_DATAWH");
 
/**** End ***/
/****** Function ********/
 
function AddDate($date1,$inc) {
		$arrDate1 = explode("-",$date1);
		$arrDate1[2] = $arrDate1[2]+$inc;
		$timStmp1 = mktime(0,0,0,$arrDate1[1],$arrDate1[2],$arrDate1[0]);

		//return  date('Y-m-d',$timStmp1+(86400*$inc));
		return  date('Y-m-d',$timStmp1);
	}

	function compareDate($date1,$date2) {
		$arrDate1 = explode("-",$date1);
		$arrDate2 = explode("-",$date2);
		$timStmp1 = mktime(0,0,0,$arrDate1[1],$arrDate1[2],$arrDate1[0]);
		$timStmp2 = mktime(0,0,0,$arrDate2[1],$arrDate2[2],$arrDate2[0]);

		return ($timStmp1 - $timStmp2)/86400;
	}
	function timestamp($date1) {
		$arrDate1 = explode("-",$date1);
		$timStmp1 = mktime(0,0,0,$arrDate1[1],$arrDate1[2],$arrDate1[0]);
		return $timStmp1;
	}

function txtToWeb($SQLtxt) {
	//$txtToWeb = iconv('TIS-620','UTF-8',$SQLtxt);
	//return $txtToWeb;
	
	 $utf8 = "";
  for ($i = 0; $i < strlen($SQLtxt); $i++) {
    $a = substr($SQLtxt, $i, 1);
    $val = ord($a);
 
    if ($val < 0x80) {
      $utf8 .= $a;
    } elseif ((0xA1 <= $val && $val < 0xDA) || (0xDF <= $val && $val <= 0xFB)) {
      $unicode = 0x0E00+$val-0xA0;
      $utf8 .= chr(0xE0 | ($unicode >> 12));
      $utf8 .= chr(0x80 | (($unicode >> 6) & 0x3F));
      $utf8 .= chr(0x80 | ($unicode & 0x3F));
    }
  }
  return $utf8;
	
	
}
function txtToSQL($SQLtxt) {
	$txtToWeb = iconv('UTF-8','TIS-620',$SQLtxt);
	return $txtToWeb;
	
	
}

function SQLDate($BDate) {
	// Convert  SQL date form 'yyyy-mm-dd'  to 'mm/dd/yyyy'  *****
	$Datetmp=explode('-',$BDate);
	$SQLDate = $Datetmp[1]."/".$Datetmp[2]."/".$Datetmp[0];
	return $SQLDate;
}
function SQLDateTime($BDate) {
	// Convert  SQL date form 'yyyy-mm-dd'  to 'mm/dd/yyyy'  *****
	$Datetmp=explode('/',$BDate);
	$SQLDate = $Datetmp[1]."/".$Datetmp[0]."/".$Datetmp[2];
	return $SQLDate;
}
function PHPDate($BDate) {
	// Convert  SQL date form 'yyyy-mm-dd'  to 'mm/dd/yyyy'  *****
	$Datetmp=explode('/',$BDate);
	$PHPDate = $Datetmp[0]."-".$Datetmp[1]."-".$Datetmp[2];
	return $PHPDate;
}
/**** End function *****/

function getDaysInWeek ($weekNumber, $year, $dayStart = 1) {
  // Count from '0104' because January 4th is always in week 1
  // (according to ISO 8601).
  $time = strtotime($year . '0104 +' . ($weekNumber - 1).' weeks');
  // Get the time of the first day of the week
  $dayTime = strtotime('-' . ($dayStart) . ' days', $time);
  // Get the times of days 0 -> 6
  $dayTimes = array ();
  for ($i = 0; $i < 7; ++$i) {
	$dayTimes[] = strtotime('+' . $i . ' days', $dayTime);
  }
  // Return timestamps for mon-sun.
  return $dayTimes;
}

function addLoc ($UserName,$Action,$IP,$Remark)
{
	$strSQL ="INSERT INTO VAE_Loc
           ([User_Name]
           ,[Action]
           ,[Client_IP]
           ,[Loc_Date_Time]
		   ,[Remark])
     VALUES
           ('".$UserName."'
           ,'".$Action."'
           ,'".$IP."'
           ,CURRENT_TIMESTAMP
		   ,'".$Remark."') ";
	$objQuery = mssql_query($strSQL) or die ("Error Query [".$strSQL."]");

}
function Num2d($Number)
{
	  if($Number==0){$cnum3d="-";}else{$cnum3d=number_format($Number,2);}
	return $cnum3d;
}
function CutDate($DateTime)
{
	//echo $DateTime;
	$DateTime =str_replace(' ','-',$DateTime);
	$DateTime=str_replace('/','-',$DateTime);
	$DateTime=str_replace(':','-',$DateTime);
     $Datetmp=explode('-',$DateTime);
	return $Datetmp ;
}
function DiffHour($ValTime)
{
	//date_default_timezone_set('UTC');
	if($ValTime < 0 )
	{
		$ValTime = abs($ValTime);
		$Mtmp  =date('i',$ValTime);
		$Mr =$Mtmp % 60;
		$Hr  =date('H',$ValTime-25200);
		//$Hr =($Mtmp -$Mr)/60;
		$Mr =$Mr/100;
		return number_format(($Hr+$Mr)* -1,2);
	}else {
		$ValTime = abs($ValTime);
		$Mtmp  =date('i',$ValTime);
		$Mr =$Mtmp % 60;
		$Hr  =date('H',$ValTime-25200);
		//$Hr =($Mtmp -$Mr)/60;
		$Mr = $Mr/100;
		return number_format($Hr+$Mr,2);
	}
}

/********* End function *******************/

function convertDate($Start_Date)
{
	$exp = explode("/", $Start_Date);
	$date = $exp[2]."-".$exp[1]."-".$exp[0]." 00:00:00.000";
	
	return $date;
}

function getType_PD($pd)
{
	$sql = "select ms_ProductType.ProductType_Index from tb_BaggingOrder inner join ms_SKU on tb_BaggingOrder.Sku_Index = ms_SKU.Sku_Index
	inner join ms_Product on ms_SKU.Product_Index = ms_Product.Product_Index
	inner join ms_ProductType on ms_Product.ProductType_Index = ms_ProductType.ProductType_Index
	where BaggingOrder_No = '".$pd."' and tb_BaggingOrder.Status <> -1";
	$result = mssql_query($sql) or die($sql);
	$num = mssql_num_rows($result);
	if($num > 0)
	{
		$row = mssql_fetch_array($result);
		return $row['ProductType_Index'];
	}
	else
	{		
		return '';
	}
	
}

function getDayDate($group)
{

	if($group  == '0010000000001')
	{
		$limit = ',minDate:-20,maxDate: 0';//'';
	}
	else if($group  == '0010000000006')
	{
		$limit = '';//',minDate:-7,maxDate: 0';
	}
	else if($group  == '0010000000000' || $group  == '0010000000009')
	{
		$limit = '';
	}
	else 
	{
		$limit = ',minDate:1,maxDate: 0';
	}
	
	return $limit;
}
	
function getCostGroupLine($line)
{
	if($line == 12)
	{
		$str = "in ('12','13','Store Product','OFFICE')";
	}
	else
	{
		$str = "in ('".$line."')";
	}
	
	return $str;
}

function getPriceGroupLine($loc,$type)
{
	$sql = "select * from ms_Cost where Cost_Name = '".$loc."' and CostProductType_Index = '".$type."'";
	$result = mssql_query($sql) or die($sql);
	$num = mssql_num_rows($result);
	if($num > 0)
	{
		$row = mssql_fetch_array($result);
		return number_format($row['Price1'], 2, '.', '');
	}
	else
	{		
		return 0;
	}
}

function getFectorLine($line)
{
	$sql = "select * from ms_CostProduct where Str2 = '".$line."'";
	$result = mssql_query($sql) or die($sql);
	$num = mssql_num_rows($result);
	if($num > 0)
	{
		$row = mssql_fetch_array($result);
		return $row['Unit1'];
	}
	else
	{		
		return 0;
	}
}

function getPDIssue_AutoMobile($sku,$lot,$line,$date,$pd,$shift,$type)
{
	if($type != 'RecheckBalance')
	{
		$sql = "
			select SUM(Qty_Issue) as Qty from tb_PDIssueRawMat issue inner join tb_Tag tag on issue.Tag_No = tag.Tag_No
			where issue.Status = 1 and Sku_Index = '".$sku."' and Line_No = '".$line."' and convert(varchar(19),Date,103) = convert(varchar(19),'".$date."',103)
			and Production_No = '".$pd."' and WorkShifts_Index = '".$shift."' and Type = '".$type."' and PLot = '".$lot."'
		";
		
	}
	else
	{
		$sql = " select sum(Qty_Begin) as Qty from (
			select MAX(Seq_Tag) as Seq_Tag,issue.Tag_No from tb_PDIssueRawMat issue inner join tb_Tag tag on issue.Tag_No = tag.Tag_No
			where issue.Status = 1 and Sku_Index = '".$sku."' and Line_No = '".$line."' and convert(varchar(19),Date,103) = convert(varchar(19),'".$date."',103)
			and Production_No = '".$pd."' and WorkShifts_Index = '".$shift."' and Type = '".$type."' and PLot = '".$lot."' group by issue.Tag_No ) a
			inner join tb_PDIssueRawMat b on a.Tag_No = b.Tag_No and a.Seq_Tag = b.Seq_Tag
			
			where Type = 'RecheckBalance' and b.Status = 1
		";
	}
	
	$result = mssql_query($sql) or die($sql);
	$num = mssql_num_rows($result);
	if($num > 0)
	{
		$row = mssql_fetch_array($result);
		return $row['Qty'];
	}
	else
	{		
		return 0;
	}
}

function getCustomerLine($pd)
{
	$sql = " select * from tb_BaggingOrder where BaggingOrder_No = '".$pd."' and Status <> -1";
	$result = mssql_query($sql) or die($sql);
	$num = mssql_num_rows($result);
	if($num > 0)
	{
		$row = mssql_fetch_array($result);
		
		$sql_cus = "select * from ms_Customer where Customer_Name = '".'LINE'.$row['BaggingLine_No']."'";
		
		$result_cus = mssql_query($sql_cus) or die($sql_cus);
		$num_cus = mssql_num_rows($result_cus);
		if($num > 0)
		{
			$row_cus = mssql_fetch_array($result_cus);
			return $row_cus['Customer_Index'];
		}
		else
		{		
			return '0010000000004';
		}
	}
}
	