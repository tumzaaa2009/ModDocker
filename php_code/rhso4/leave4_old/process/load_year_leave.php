<?php
session_start();
include '../class/class_db.php';
$emp_id = $_GET['emp_id'];
$_SESSION['emp_id'] = $emp_id;

echo $_SESSION['emp_id'];

//ปีงบประมาณ
$fiscal_year = $_SESSION['fiscal_year'];

//echo $fiscal_year;

//$rm = getAllYearLeave($emp_id,$fiscal_year);



echo "<select class='chosen-select form-control' id='ddl_year' name='ddl_year' data-placeholder='Please Select...' required='required'onchange='getMonth(this.value)'>
  <option value=''>  </option>";
  //ปีงบปัจจุบันย้อนหลังไป 1 ปี
  //foreach ($rm as $v) {
    for($i = 0;$i <= 1 ;$i++) {
    //echo "<option value='$v[leave_year]'>".$v[leave_year]."</option>";
    //echo "<option value='333'>3333</option>";
    echo "<option value='".($fiscal_year - $i)."'>".(($fiscal_year - $i) + 543)."</option>";

    
  }
echo "</select>";


$rm = null;

function getAllYearLeave($leave_emp_id,$fiscal_year)
    {
        $class_db = new class_db();
        $n = array('leave_emp_id' => $leave_emp_id//,
                    //'fiscal_year' => $fiscal_year
                  );

        //ต้องเช็คช่วง เดือนของการหา Fiscal year
        $sql = " select DISTINCT(YEAR(CAST(leave_start AS DATE))) leave_year ";
        $sql .= " from leave_detail where emp_id = :leave_emp_id and CAST(leave_start as INT) >= CAST(REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL -1 YEAR),'-','') AS INT) ";
        $sql .= " and CAST(leave_end as INT) < REPLACE(DATE_ADD('".$fiscal_year."-10-01', INTERVAL 1 YEAR),'-','') ";
        
        //Original
        //$sql = " select DISTINCT(leave_year) from leave_list where leave_emp_id = :leave_emp_id ";
        //echo $sql;
        $result = $class_db->getAll($sql, $n);
        $class_db = null;
        return $result;
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