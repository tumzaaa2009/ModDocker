
<?php
  if(!isset($_SESSION)) 
    { 
        session_start(); 
        
  


  } 
 
if(isset($_GET['id'])){
  
if ($_GET['id']==1) {
		 session_destroy();
	echo "<script>
	window.location = 'login.php';
	</script>";
} elseif($_GET['id']==2) {
		 session_destroy();
	echo "<script>
	window.location = '../index_for_staff.php';
	</script>";
}
}
 session_destroy();
  	echo "<script>
	window.location = 'login.php';
	</script>";
  exit;

  exit;
?>
