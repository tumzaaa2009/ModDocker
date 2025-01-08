<?php
  if(!isset($_SESSION)) 
    { 
        session_start();

    } 
include_once 'connect/connect.php';

// echo $_POST['type'];


 ?>
<html>
<head>
<meta charset='utf-8' />
 


<style>
 

</style>
</head>
<body> 
 <div class="container">
     <div class="row">
          <div class="col-12">
                <div id='calendar' ></div>
          </div>
     </div>
 </div>

</body>
</html>

<script type="text/javascript">

    $(function() { // document ready
 var x = <?=$_SESSION["Emp_id"]?>;
 // var y = <?=$_POST['type']?>;

 
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale:'th' ,
      plugins: [ 'interaction', 'dayGrid', 'timeGrid', 'list' ],
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
      },
      // defaultDate: '2019-08-12',
      navLinks: true, // can click day/week names to navigate views
      businessHours: true, // display business hours
      editable: true,

            events: { 
                url: 'resource.php?events&&id_emp='+x,

                // error: function() {
                //     $('#script-warning').show();
                // }
            }

    });

    calendar.render();
  });





</script>
