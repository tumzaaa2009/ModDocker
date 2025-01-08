
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link rel="stylesheet" type="text/css" href="css_and_js/fullcalendar-4.3.1/packages/core/main.css">
<link rel="stylesheet" type="text/css" href="css_and_js/fullcalendar-4.3.1/packages/core/main.css">
<link rel="stylesheet" type="text/css" href="css_and_js/fullcalendar-4.3.1/packages/daygrid/main.css">
<link rel="stylesheet" type="text/css" href="css_and_js/fullcalendar-4.3.1/packages/timegrid/main.css">
<link rel="stylesheet" type="text/css" href="css_and_js/fullcalendar-4.3.1/packages//list/main.css">
<script src="css_and_js/fullcalendar-4.3.1/packages/core/main.js"></script>
<script src="css_and_js/fullcalendar-4.3.1/packages/interaction/main.js"></script>
<script src="css_and_js/fullcalendar-4.3.1/packages/daygrid/main.js"></script>
<script src="css_and_js/fullcalendar-4.3.1/packages/timegrid/main.js"></script>
<script src="css_and_js/fullcalendar-4.3.1/packages//list/main.js"></script>
<!-- ภาษาไทย -->
<script src="css_and_js/fullcalendar-4.3.1/packages/core/locales/th.js" ></script> 
<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      locale:'th',
      plugins: [ 'interaction', 'dayGrid' ],
      
      // defaultDate: '2019-08-12',
      // navLinks: true, // can click day/week names to navigate views
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      events : 'showcalender_main_decription.php' ,
          extraParams: function() {
      return {
        cachebuster: new Date().valueOf()
      };
    }
  });
     calendar.render();
});
</script>
<style>

  html, body {
    overflow: hidden; /* don't do scrollbars */
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar-container {
    position: fixed;
    top: 9%;
    left: 0;
    right: 0;
    bottom: 15%;
    margin-left: 10%;
  }

  .fc-header-toolbar {
    /*
    the calendar will be butting up against the edges,
    but let's scoot in the header's buttons
    */
    padding-top: 1em;
    padding-left: 1em;
    padding-right: 1em;
  }

</style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
       <div class="col-12">
  <div id='calendar-container'>
    <div id='calendar'></div>
  </div>
       </div>
    </div>
    
  </div>
  

</body>
</html>
