<?php
session_start();
//include 'load_report_leave.php';
include '../class/class_db.php';
$emp_id = $_SESSION['emp_id'];
$gyear = $_GET['v_year'];
//echo "ABCD";
//$load_report_leave = new load_report_leave();
//$rm = $load_report_leave->getDistinctMonth($emp_id, $gyear);

//echo "DDD" . $gyear . "AAA";

$rm = getDistinctMonth($emp_id, $gyear);

echo "<select class='chosen-select form-control' id='ddl_month' name='ddl_month' data-placeholder='Please Select...' required='required'>
  <option value=''>  </option>";
  foreach ($rm as $v) {
    echo "<option value='$v[leave_month]'>".getMonthFullName($v[leave_month])."</option>";
  }
echo "</select>";

$rm = null;

function getDistinctMonth($leave_emp_id , $gyear)
    {
      $class_db = new class_db();
        $n = array('leave_emp_id' => $leave_emp_id,'leave_year' => $gyear);
        $sql = " select DISTINCT(leave_month) from leave_list where leave_emp_id = :leave_emp_id and leave_year = :leave_year order by leave_month";
        $result = $class_db->getAll($sql, $n);
        $class_db = null;
        return $result;
    }

function getMonthFullName($month_id) {
  $month_name = "";
  switch ($month_id) {
    case '01':
      $month_name = "มกราคม";
      break;
    case '02':
      $month_name = "กุมภาพันธ์";
      break;
    case '03':
      $month_name = "มีนาคม";
      break;
    case '04':
      $month_name = "เมษายน";
      break;
    case '05':
      $month_name = "พฤษภาคม";
      break;
    case '06':
      $month_name = "มิถุนายน";
      break;
    case '07':
      $month_name = "กรกฎาคม";
      break;
    case '08':
      $month_name = "สิงหาคม";
      break;
    case '09':
      $month_name = "กันยายน";
      break;
    case '10':
      $month_name = "ตุลาคม";
      break;
    case '11':
      $month_name = "พฤศจิกายน";
      break;
    case '12':
      $month_name = "ธันวาคม";
      break;
    default:
      break;
  }
  return $month_name;
}
?>

<script type="text/javascript">
  jQuery(function($) {
    if(!ace.vars['touch']) {
      $('.chosen-select').chosen({allow_single_deselect:true});
      //resize the chosen on window resize

      $(window)
      .off('resize.chosen')
      .on('resize.chosen', function() {
        $('.chosen-select').each(function() {
           var $this = $(this);
           $this.next().css({'width': $this.parent().width()});
        })
      }).trigger('resize.chosen');
      //resize chosen on sidebar collapse/expand
      $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
        if(event_name != 'sidebar_collapsed') return;
        $('.chosen-select').each(function() {
           var $this = $(this);
           $this.next().css({'width': $this.parent().width()});
        })
      });

      $('#chosen-multiple-style .btn').on('click', function(e){
        var target = $(this).find('input[type=radio]');
        var which = parseInt(target.val());
        if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
         else $('#form-field-select-4').removeClass('tag-input-style');
      });
    }
  });
</script>