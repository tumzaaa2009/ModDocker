<?php

  //include 'check_session_admin.php';

?>



<div class="row">
  <div class="col-xs-12">
    <form class="form-horizontal" role="form" action="report/report_summary_leave.php" method="post" target="_blank">


    <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="emp_id"> พนักงาน </label>
        <div class="col-sm-4">
          <select class="chosen-select form-control" id="emp_id" name="emp_id" data-placeholder="Please Select..." required="required">
            <option value=""></option>
        
          </select>
        </div>
      </div>


      <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="ddl_year"> ปี </label>
        <div class="col-sm-4" id="tYear">
          <select class="chosen-select form-control" id="ddl_year" name="ddl_year" data-placeholder="Please Select..." required="required" >
            <option value=""></option>
    
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="ddl_month"> เดือน </label>
        <div class="col-sm-4" id="tMonth">
          <select class="chosen-select form-control" id="ddl_month" name="ddl_month" data-placeholder="Please Select..." required="required">
            <option value=""></option>
          </select>
        </div>
      </div>

      
      <div class="form-group">
        <label class="col-sm-2 control-label no-padding-right" for="gmonth"> รูปแบบรายงาน </label>
        <div class="col-sm-4">
          <select class="chosen-select form-control" id="report_type" name="report_type" data-placeholder="Please Select..." required="required">
            <option value="print">Print Preview</option>
            <option value="excel">Excel Export</option>
            <!--<option value="pdf">PDF Preview</option>-->
          </select>
        </div>
      </div>
      <div class="clearfix form-actions">
        <div class="col-md-offset-2 col-md-9">
          <button class="btn btn-info" type="submit">
            <i class="ace-icon fa fa-search bigger-110"></i>
            ประมวลผล
          </button>
           <!-- hiden field เก็บ status-->
           <input type="hidden" id ="emp_name" name="emp_name">
        </div>
      </div>
    </form>
  </div>
</div>


<!-- 
<script>
 function getYear(emp_id) {
    $("#tYear").load('process/load_year_leave.php?emp_id=' + emp_id);
    //$.session.set('leave_year', $("#tYear").val);
    //$("#tMonth").load('process/load_report_leave.php');
   //alert("DDDD");
  }
  function getMonth() {
    //alert($('#tYear :selected').val() + "DDD");
    //alert('process/load_month_leave.php?v_year =' + $('#tYear :selected').val());
    //$("#tMonth").load('process/load_month_leave.php?emp_id=' + emp_id + '&v_year =' + $('#tYear :selected').val());
    //$("#tMonth").load('process/load_month_leave.php?v_year =' + $('#tYear :selected').val());
    $("#emp_name").val($('#emp_id :selected').text());
   //alert($("#emp_name").val());

    var a = $('#tYear :selected').val();
    $("#tMonth").load('process/load_month_leave.php?v_year=' + $('#tYear :selected').val());
   //alert("DDDD");
  }
</script>


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
 -->
