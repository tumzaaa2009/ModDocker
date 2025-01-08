<?php
session_start();


include 'connect/connect.php';
$secret ="6LduUM4mAAAAALFipeC1sdpOZDQw0J5LXSZfvUWW";

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

 $ipBlockList = "SELECT * FROM tbl_ip_blocklist WHERE ip_block ='$ip'" ;
 $resultBlock =  mysqli_query($objconnect,$ipBlockList) ;
 	$resultFetchBlock = mysqli_fetch_array($result);
$countBlock = mysqli_num_rows($resultFetchBlock);








 if (isset($_POST['response'])) {
    $responseData = json_decode($_POST['response'], true); // Set true for associative array, false for object
    if (is_array($responseData)) {
        $expires_in = $responseData['expires_in'];
        $access_token = $responseData['access_token'];
        $refresh_token = $responseData['refresh_token'];
        $token_type = $responseData['token_type'];
        $scope = $responseData['scope'];
        $pid = $responseData['pid'];
        $name = $responseData['name'];
        $given_name = $responseData['given_name'];
        $family_name = $responseData['family_name'];
        $titleTh = $responseData['titleTh'];
        $login = "SELECT ulog.user_name as username ,emp.Emp_name as name ,emp.Emp_lastname as lastname,emp.Emp_id as id,typeemp.name AS typename,typeemp.group_id AS groupid,pgroup.name AS pname,
            emp.Emp_Status as emp_status , emp.work_group as work_group   FROM tbl_userlogin as ulog INNER JOIN tbl_employment as emp 
            ON ulog.Emp_id = emp.Emp_id INNER JOIN tbl_group_type AS typeemp ON typeemp.group_id = emp.group_id
            INNER JOIN tbl_position_group pgroup ON pgroup.Position_id=emp.group_id
            where emp.Emp_name= '" .$given_name . "' and emp.Emp_lastname='" . $family_name . "'
            and emp_status = '1' and emp.work_group != '' ";
         
            $objQuery = mysqli_query($objconnect, $login);
            $num = mysqli_num_rows($objQuery);
        
            if ($num == 1) {
              
                while ($checkuser = mysqli_fetch_array($objQuery)) {
                    $_SESSION["Emp_id"] = $checkuser["id"];
                    $_SESSION["name"] = $checkuser["name"];
                    $_SESSION["lastname"] = $checkuser["lastname"];
                    $_SESSION["Username"] = $checkuser["username"];
                    $_SESSION["groupid"] = $checkuser["groupid"];
                    $_SESSION["typename"] = $checkuser["typename"];
                    $_SESSION["secname"] = $checkuser["pname"];
                        $_SESSION["work_group"] =$checkuser["work_group"];
                        
     } ?>
                <script type="text/javascript">
                var x = "<?= $_SESSION["
                            Emp_id "] ?>            ";
                window.location.href = 'index.php';
                </script>

                <?php } else {

                                        ?>
                <script type="text/javascript">
                // alert("โปรดตรวจสอบอีกครั้ง/รหัสนี้ถูกยุตติการใช้งานแล้ว");
                // window.location.href = 'login.php';
                </script>
                <?php }
                                }

                            }
                    
                ?>
<?php
 
if(isset($_POST['g-recaptcha-response']) && $countBlock==0){
    $captcha = $_POST['g-recaptcha-response'];
    $verifyResponse =
    file_get_contents('https://google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha);
    $responseData = json_decode($verifyResponse);
    if(!$captcha){
       /*  $_SESSION['error'] = "กรูณาใส่ reCAPTCHA !!"; */
        header("location: login.php");
    }


if (isset($_POST["submit"])) {




    if ($_POST["submit"] == "Log In") {
        $login = "SELECT ulog.user_name as username ,emp.Emp_name as name ,emp.Emp_lastname as lastname,emp.Emp_id as id,typeemp.name AS typename,typeemp.group_id AS groupid,pgroup.name AS pname,
      emp.Emp_Status as emp_status , emp.work_group as work_group   FROM tbl_userlogin as ulog INNER JOIN tbl_employment as emp 
      ON ulog.Emp_id = emp.Emp_id INNER JOIN tbl_group_type AS typeemp ON typeemp.group_id = emp.group_id
      INNER JOIN tbl_position_group pgroup ON pgroup.Position_id=emp.group_id
      where ulog.user_name = '" . $_POST["username"] . "' and ulog.user_pass='" . sha1($_POST["password"]) . "'
      and emp_status = '1' ";
        $objQuery = mysqli_query($objconnect, $login);
        $num = mysqli_num_rows($objQuery);

        if ($num == 1) {
            while ($checkuser = mysqli_fetch_array($objQuery)) {
                $_SESSION["Emp_id"] = $checkuser["id"];
                $_SESSION["name"] = $checkuser["name"];
                $_SESSION["lastname"] = $checkuser["lastname"];
                $_SESSION["Username"] = $checkuser["username"];
                $_SESSION["groupid"] = $checkuser["groupid"];
                $_SESSION["typename"] = $checkuser["typename"];
                $_SESSION["secname"] = $checkuser["pname"];
                 $_SESSION["work_group"] =$checkuser["work_group"];

            } ?>
                <script type="text/javascript">
                var x = "<?= $_SESSION["
                            Emp_id "] ?>            ";
                window.location.href = 'index.php';
                </script>

                <?php } else {

                                        ?>
                <script type="text/javascript">
                // alert("โปรดตรวจสอบอีกครั้ง/รหัสนี้ถูกยุตติการใช้งานแล้ว");
                // window.location.href = 'login.php';
                </script>
                <?php }
                                }

                            }
                        }



                ?>
<?php
if (isset($_POST['Cid-Person'])) {
    // Sanitize the input to prevent SQL injection
    $cidPerson = $_POST['Cid-Person'];

    // Establish a database connection (assuming you have a database connection code here)

    // Perform the SQL query
    $sql = "SELECT * FROM tbl_employment INNER JOIN tbl_userlogin ON tbl_userlogin.Emp_id = tbl_employment.Emp_id WHERE tbl_employment.Emp_Status = 1 AND tbl_employment.Emp_tel = '$cidPerson'";

    // // Execute the query (assuming you have a database connection variable $conn)
      $result = mysqli_query($objconnect, $sql);
    $fetaArray =  mysqli_fetch_array($result);
    
         echo '<h2 id="text-response" style="color:red">UserName : ' . $fetaArray['user_name'] . '</h2>';
             echo '<h2 id="text-response" style="color:red">PassWord: ' . $fetaArray['user_pass_detail'] . '</h2>';
   
 
}
?>

<?php
 
if (isset($_GET['LineId'])) {
    $select = "SELECT ulog.user_name as username ,emp.Emp_name as name ,emp.Emp_lastname as lastname,emp.Emp_id as id,typeemp.name AS typename,typeemp.group_id AS groupid,pgroup.name AS pname,
              emp.Emp_Status as emp_status , emp.work_group as work_group FROM tbl_userlogin as ulog INNER JOIN tbl_employment as emp 
              ON ulog.Emp_id = emp.Emp_id INNER JOIN tbl_group_type AS typeemp ON typeemp.group_id = emp.group_id
              INNER JOIN tbl_position_group pgroup ON pgroup.Position_id=emp.group_id
              WHERE emp.user_line_id = ? AND emp.Emp_Status = '1'";  // Fixed the SQL query

    $stmt = $objconnect->prepare($select);

    // Check if the statement is prepared successfully
    if ($stmt) {
        // Bind the parameter to the statement
        $stmt->bind_param("s", $_GET['LineId']);  // Assuming user_line_id is a string. Use "i" if it's an integer.

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the row
        $row = $result->fetch_assoc();

        // Check if a row is fetched
        if ($row) {
            $_SESSION["Emp_id"] = $row["id"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["lastname"] = $row["lastname"];
            $_SESSION["Username"] = $row["username"];
            $_SESSION["groupid"] = $row["groupid"];
            $_SESSION["typename"] = $row["typename"];
            $_SESSION["secname"] = $row["pname"];
            $_SESSION["work_group"] = $row["work_group"];

            // Redirect to index.php
            echo '<script type="text/javascript">
                var x = "' . $_SESSION["Emp_id"] . '";
                window.location.href = "header_mobile.php";
            </script>';
        } else {
            // User not found
            echo '<script type="text/javascript">
                alert("โปรดตรวจสอบอีกครั้ง/รหัสนี้ถูกยุติการใช้งานแล้ว");
                window.location.href = "login.php";
            </script>';
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle the case where the statement preparation fails
        echo '<script type="text/javascript">
            alert("Error preparing statement: ' . $objconnect->error . '");
            window.location.href = "login.php";
        </script>';
    }

    // Close the database connection
    $objconnect->close();
}
?>


<!DOCTYPE html>
<html>

<head>


    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>ใบลาสำนักงานสาธารณะสุขเขต4</title>
    <link href="icon/logo2.png" rel=icon>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css_and_js/css/bootstrap.min.css">
    <link rel="stylesheet" href="css_and_js/css/all.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

</head>

<body>

    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->
            <!-- Icon -->
            <div class="fadeIn first mt-3">
                <img src="icon/logo2.png" id="icon" alt="User Icon" style="width: 150px;height: 150px" />
            </div>
            <!-- Login Form -->
                <form method="post">
                    <input type="text" name="username" name="login" placeholder="User">
                    <input type="password" name="password" name="password" placeholder="Password">
                    <center>
                        <div>
                            <div class="g-recaptcha" data-sitekey="6LduUM4mAAAAAB3ln1owsGPorfThJWkCI7V2qrIN"></div>
                        </div>
                    </center>
                    <input type="submit" name="submit" value="Log In">
                </form>


            <button type="button" class="btn btn-md btn-success mt-3" onClick="AuthDopa()">
                เข้าสู่ระบบด้วย <img src="/ev/img/thaiid.png" alt="buttonpng" style="height: 30px; border-radius: 3px;">
            </button>


            <!-- Remind Passowrd -->
            <div id="formFooter" class="mt-3">
                <!-- <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#exampleModal">
                    ลืมรหัสผ่าน </button> -->
                <a class="underlineHover btn btn-info"  onClick="RegDopa()" >สมัครหัสผ่านใหม่</a>
            </div>
        </div>
    </div>
    
    <div class="modal fade bd-example-modal-xl" id="exampleModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="login.php">
                        <div class="form-group">
                            <label for="Cid-Person">ระบุเบอร์โทร</label>
                            <input type="number" class="form-control" name="Cid-Person" id="Cid-Person"
                                aria-describedby="helpId" placeholder="">
                            <small id="helpId" class="form-text text-muted">ระบุเบอร์โทร</small>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">ยืนยันข้อมูล</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div id="222">



        </div>
    </div>


</body>

</html>
<style type="text/css">
html {
    background-color: #56baed;
}

body {
    font-family: "Poppins", sans-serif;
    height: 100vh;
}

a {
    color: #92badd;
    display: inline-block;
    text-decoration: none;
    font-weight: 400;
}

h2 {
    text-align: center;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    display: inline-block;
    margin: 40px 8px 10px 8px;
    color: #cccccc;
}



/* STRUCTURE */

.wrapper {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    width: 100%;
    min-height: 100%;
    padding: 20px;
}

#formContent {
    -webkit-border-radius: 10px 10px 10px 10px;
    border-radius: 10px 10px 10px 10px;
    background: #fff;
    padding: 30px;
    width: 90%;
    max-width: 450px;
    position: relative;
    padding: 0px;
    -webkit-box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
    box-shadow: 0 30px 60px 0 rgba(0, 0, 0, 0.3);
    text-align: center;
}

#formFooter {
    background-color: #f6f6f6;
    border-top: 1px solid #dce8f1;
    padding: 25px;
    text-align: center;
    -webkit-border-radius: 0 0 10px 10px;
    border-radius: 0 0 10px 10px;
}



/* TABS */

h2.inactive {
    color: #cccccc;
}

h2.active {
    color: #0d0d0d;
    border-bottom: 2px solid #5fbae9;
}



/* FORM TYPOGRAPHY*/

input[type=button],
input[type=submit],
input[type=reset] {
    background-color: #56baed;
    border: none;
    color: white;
    padding: 15px 80px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    text-transform: uppercase;
    font-size: 13px;
    -webkit-box-shadow: 0 10px 30px 0 rgba(95, 186, 233, 0.4);
    box-shadow: 0 10px 30px 0 rgba(95, 186, 233, 0.4);
    -webkit-border-radius: 5px 5px 5px 5px;
    border-radius: 5px 5px 5px 5px;
    margin: 5px 20px 40px 20px;
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    -ms-transition: all 0.3s ease-in-out;
    -o-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

input[type=button]:hover,
input[type=submit]:hover,
input[type=reset]:hover {
    background-color: #39ace7;
}

input[type=button]:active,
input[type=submit]:active,
input[type=reset]:active {
    -moz-transform: scale(0.95);
    -webkit-transform: scale(0.95);
    -o-transform: scale(0.95);
    -ms-transform: scale(0.95);
    transform: scale(0.95);
}

input[type=text],
input[type=password] {
    background-color: #f6f6f6;
    border: none;
    color: #0d0d0d;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 5px;
    width: 85%;
    border: 2px solid #f6f6f6;
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    -webkit-border-radius: 5px 5px 5px 5px;
    border-radius: 5px 5px 5px 5px;
}

input[type=text]:focus {
    background-color: #fff;
    border-bottom: 2px solid #5fbae9;
}

input[type=text]:placeholder {
    color: #cccccc;
}



/* ANIMATIONS */

/* Simple CSS3 Fade-in-down Animation */
.fadeInDown {
    -webkit-animation-name: fadeInDown;
    animation-name: fadeInDown;
    -webkit-animation-duration: 1s;
    animation-duration: 1s;
    -webkit-animation-fill-mode: both;
    animation-fill-mode: both;
}

@-webkit-keyframes fadeInDown {
    0% {
        opacity: 0;
        -webkit-transform: translate3d(0, -100%, 0);
        transform: translate3d(0, -100%, 0);
    }

    100% {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
    }
}

@keyframes fadeInDown {
    0% {
        opacity: 0;
        -webkit-transform: translate3d(0, -100%, 0);
        transform: translate3d(0, -100%, 0);
    }

    100% {
        opacity: 1;
        -webkit-transform: none;
        transform: none;
    }
}

/* Simple CSS3 Fade-in Animation */
@-webkit-keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@-moz-keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.fadeIn {
    opacity: 0;
    -webkit-animation: fadeIn ease-in 1;
    -moz-animation: fadeIn ease-in 1;
    animation: fadeIn ease-in 1;

    -webkit-animation-fill-mode: forwards;
    -moz-animation-fill-mode: forwards;
    animation-fill-mode: forwards;

    -webkit-animation-duration: 1s;
    -moz-animation-duration: 1s;
    animation-duration: 1s;
}

.fadeIn.first {
    -webkit-animation-delay: 0.4s;
    -moz-animation-delay: 0.4s;
    animation-delay: 0.4s;
}

.fadeIn.second {
    -webkit-animation-delay: 0.6s;
    -moz-animation-delay: 0.6s;
    animation-delay: 0.6s;
}

.fadeIn.third {
    -webkit-animation-delay: 0.8s;
    -moz-animation-delay: 0.8s;
    animation-delay: 0.8s;
}

.fadeIn.fourth {
    -webkit-animation-delay: 1s;
    -moz-animation-delay: 1s;
    animation-delay: 1s;
}

/* Simple CSS3 Fade-in Animation */
.underlineHover:after {
    display: block;
    left: 0;
    bottom: -10px;
    width: 0;
    height: 2px;
    background-color: #56baed;
    content: "";
    transition: width 0.2s;
}

.underlineHover:hover {
    color: #0d0d0d;
}

.underlineHover:hover:after {
    width: 100%;
}



/* OTHERS */

*:focus {
    outline: none;
}

#icon {
    width: 60%;
}
</style>

<script>
const AuthDopa = () => {
    location.href =
        "https://imauth.bora.dopa.go.th/api/v2/oauth2/auth/?client_id=VFFxaUtWUTBwS2pmcHZlUHkzdk5jT3Uxd2p4anpxUTE&redirect_uri=https://rh4.moph.go.th/ev/pages/authdopa.php&response_type=code&scope=pid title given_name family_name name&state=auThenRg4leave4"

}
const RegDopa = () => {
    location.href =
        "https://imauth.bora.dopa.go.th/api/v2/oauth2/auth/?client_id=VFFxaUtWUTBwS2pmcHZlUHkzdk5jT3Uxd2p4anpxUTE&redirect_uri=https://rh4.moph.go.th/ev/pages/authdopa.php&response_type=code&scope=pid title given_name family_name name&state=regRg4leave4"

}
</script>