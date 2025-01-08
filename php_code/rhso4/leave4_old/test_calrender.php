
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8' />
<link href='css_and_js/fullcalendar-4.3.1/packages/core/main.css' rel='stylesheet' />
<link href='css_and_js/fullcalendar-4.3.1/packages/daygrid/main.css' rel='stylesheet' />
<link href='css_and_js/fullcalendar-4.3.1/packages/timegrid/main.css' rel='stylesheet' />
<link href='css_and_js/fullcalendar-4.3.1/packages/list/main.css' rel='stylesheet' />


<script src='css_and_js/fullcalendar-4.3.1/packages/core/main.js'></script>
<script src='css_and_js/fullcalendar-4.3.1/packages/interaction/main.js'></script>
<script src='css_and_js/fullcalendar-4.3.1/packages/daygrid/main.js'></script>
<script src='css_and_js/fullcalendar-4.3.1/packages/timegrid/main.js'></script>
<script src='css_and_js/fullcalendar-4.3.1/packages/list/main.js'></script>


<script src='https://fullcalendar.io/js/fullcalendar-scheduler-1.7.1/lib/moment.min.js'></script>
<script src='https://fullcalendar.io/js/fullcalendar-scheduler-1.7.1/lib/jquery.min.js'></script>
<script src='https://fullcalendar.io/js/fullcalendar-scheduler-1.7.1/lib/fullcalendar.min.js'></script>
<script src='https://fullcalendar.io/js/fullcalendar-scheduler-1.7.1/scheduler.min.js'></script>



<script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
  schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',//ระบุ license ว่าเราใช้งาน license ประเภทใด
            now: '2017-09-07',
            editable: true, // enable draggable events
            aspectRatio: 1.8,
            scrollTime: '00:00', 
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek'
            },
            defaultView: 'timelineDay',
            views: {
                timelineThreeDays: {
                    type: 'timeline',
                    duration: { days: 3 }
                }
            },
            resourceLabelText: 'Rooms',
            resources: { 
                url: 'resource.php?resource',
                error: function() {
                    $('#script-warning').show();
                }
            },
 
            events: { 
                url: 'resource.php?events',
                error: function() {
                    $('#script-warning').show();
                }
            }   
             });

    calendar.render();
  });

</script>
<script>
  
<script>
 
    $(function() { // document ready
 
        $('#calendar').fullCalendar({
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',//ระบุ license ว่าเราใช้งาน license ประเภทใด
            now: '2017-09-07',
            editable: true, // enable draggable events
            aspectRatio: 1.8,
            scrollTime: '00:00', 
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek'
            },
            defaultView: 'timelineDay',
            views: {
                timelineThreeDays: {
                    type: 'timeline',
                    duration: { days: 3 }
                }
            },
            resourceLabelText: 'Rooms',
            resources: { 
                url: 'resource.php?resource',
                error: function() {
                    $('#script-warning').show();
                }
            },
 
            events: { 
                url: 'resource.php?events',
                error: function() {
                    $('#script-warning').show();
                }
            }
        });
    
    });
 
</script>



</script>



<style>

  body {
    margin: 40px 10px;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 900px;
    margin: 0 auto;
  }

</style>
</head>
<body>

  <div id='calendar'></div>

</body>
</html>
