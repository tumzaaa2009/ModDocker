<?php
include_once 'connect/connect.php';
include 'head/headder.php';
 
?>

<?php


            if (isset($_POST['response'])) {
           
                $responseData = json_decode($_POST['response'], true); 
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
                    
                }
            }

$secret = "6LduUM4mAAAAALFipeC1sdpOZDQw0J5LXSZfvUWW";
if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
    $verifyResponse =
        file_get_contents('https://google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $captcha);
    $responseData = json_decode($verifyResponse);
    if (!$captcha) {
        echo "<script> alert(\"กรูณาใส่ reCAPTCHA !!\");window.location.href = \"register_from.php\"  </script>";

    } else if (isset($_POST["submit"])) {
        if ($_POST["submit"] == "submit") {

            $check = "SELECT Emp_passport FROM tbl_employment where Emp_name ='" . $_POST["name"] . "' and Emp_lastname ='".$_POST['lname']."' ";
            $querycheck = mysqli_query($objconnect, $check);
            $mycheknum = mysqli_num_rows($querycheck);

            $checkuselog = "SELECT user_name FROM tbl_userlogin  where user_name='" . $_POST["uname"] . "' ";
            $querycheck_uselog = mysqli_query($objconnect, $checkuselog);
            $mynum_uselog = mysqli_num_rows($querycheck_uselog);


            echo $mycheknum ;

            $select_max = "SELECT MAX(CONVERT( Emp_id, SIGNED ))AS id FROM tbl_employment";
            $querry_max = mysqli_query($objconnect, $select_max);
            $featch_max = mysqli_fetch_array($querry_max);
            $nummax = $featch_max['id'] + 1;

            if($mycheknum==0){
            $insert_regis = "INSERT INTO tbl_employment
                        (Emp_id,Register_date,Titile_name,Emp_name, Emp_lastname, Emp_passport, Emp_tel,Emp_old,Emp_email,group_id,Position_id,work_group,Emp_address,affiliate_id,Emp_Status)
                        VALUES 
                        ('" . $nummax . "'
                        ,NOW()
                        ,'" . $_POST['titlename'] . "'
                        ,'" . $_POST["name"] . "'	
                        ,'" . $_POST["lname"] . "'	
                        ,'" . $_POST["passport"] . "'	
                        ,'" . $_POST["tel"] . "'	
                        ,'" . $_POST["old"] . "'	
                        ,'" . $_POST["email"] . "'	
                        ,'" . $_POST["grouptype"] . "'	
                        ,'" . $_POST["position"] . "'
                        ,'" . $_POST["workgroup"] . "'	
                        ,'" . $_POST["Address"] . "'	
                        ,1
                        ,1	) " ;
            $query_inset = mysqli_query($objconnect, $insert_regis);

            // เก็บ userloin//
            $select_maxuser = "SELECT MAX(CONVERT(userlogin_id,SIGNED)) AS userlogin_id FROM tbl_userlogin";
            $query_maxuser = mysqli_query($objconnect, $select_maxuser);
            $featch_maxuser = mysqli_fetch_array($query_maxuser);
            $numcheck_user = $featch_maxuser["userlogin_id"] + 1;

            $insertuser_login = "INSERT INTO tbl_userlogin
            (userlogin_id,Emp_id,user_name,user_pass,user_pass_detail,user_register)
            VALUES 
            ('" . $numcheck_user . "'
            ,'" . $nummax . "'
            ,'" . $_POST["uname"] . "'
            ,'" . sha1($_POST["password"]) . "'	
            ,'" . $_POST["password"] . "'
            ,NOW()	
            ) ";
            $query_cherkuser = mysqli_query($objconnect, $insertuser_login);
            $select_maxlimit = "SELECT Max(CAST(count_limit_holiday_id AS SIGNED)) AS nummaxlimit from tbl_total_limit_holiday";
            $query_maxlimit = mysqli_query($objconnect, $select_maxlimit);
            $featch_maxlimit = mysqli_fetch_array($query_maxlimit);
            $numcheck_maxlimit = $featch_maxlimit["nummaxlimit"] + 1;

            $insert_limt = "INSERT INTO tbl_total_limit_holiday
            (count_limit_holiday_id,Emp_id,group_id,register_day,status_limit)
            VALUES 
            ('" . $numcheck_maxlimit . "'
            ,'" . $nummax . "'	
            ,'" . $_POST["grouptype"] . "'
            ,NOW()
            ,1
            ) ";

            mysqli_query($objconnect, $insert_limt);
            // echo "string",$query_cherkuser;

            echo "<script> alert(\"บันทึกผู้ใช้เรียบร้อยแล้ว\");window.location.href = \"index.php\"  </script>";
            }else{

            echo "<script> alert(\"ผู้ใช้ซ้ำ\");window.location.href = \"login.php\"  </script>";
            }
            }

            }
    
?>


    <?php
}

?>

<!DOCTYPE html>
<!DOCTYPE html>
<html>

<head>
    <link href="icon/logo2.png" rel=icon>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="css_and_js/css/bootstrap.min.css" />
</head>

<body>

</body>

</html>

<body>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <form method="POST">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <img src="icon/logo2.png" width="75px" height="75px">
                                    <strong style="font-size:180%;color: #C7BDBD;">สมัครสมาชิกใบลา</strong>
                                </div>
                                <div class="col-6" align="right">
                                    <button type="button" class="btn btn-primary"><a href="login.php"
                                            style="color: white;">กลับสู่หน้า login</a></button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-2">
                                    <label>คำนำหน้าชื่อ</label>
                                 <select name="titlename" class="form-control">
                                    <option value="<?php if($titleTh != "") { echo $titleTh; } else { echo "0"; } ?>" selected="true"><?php if($titleTh != "") { echo $titleTh; } else { echo "0"; } ?></option>
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                 </select>
                                </div>
                                
                                <div class="col-1">

                                </div>
                                <div class="form-group col-md-3">
                                    <label>ชื่อ</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $given_name; ?>" readonly required="true">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>นามสกุล</label>
                                    <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $family_name; ?>" readonly
                                        required="true">
                                </div>

                                <div class="form-group col-md-2">
                                    <label>เบอร์ติดต่อ</label>
                                    <input type="number" class="form-control" id="tel" name="tel" placeholder=""
                                        required="true">
                                </div>
                                <div class="form-group col-md-1">
                                    <label>อายุ</label>
                                    <input type="number" class="form-control" id="old" name="old" placeholder=""
                                        required="true">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>รหัสบัตรประชาชน</label>
                                    <input type="number" class="form-control" id="passport" name="passport"
                                        placeholder="รหัสบัตรประชาชน13หลัก" name="somename"
                                        value="<?php echo $pid; ?>"
                                        readonly
                                        type="number" maxlength="13" required="true">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder=""
                                        required="true">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>ประเภทพนักงาน</label>
                                    <select name="grouptype" required="true" name="group_type">
                                        <option value="0" selected="">.....เลือกประเภทพนักงาน.....</option>
                                        <?php
                                        $selectgrouptype = "SELECT * FROM tbl_group_type ";
                                        $querygrouptype = mysqli_query($objconnect, $selectgrouptype);
                                        while ($type = mysqli_fetch_array($querygrouptype, MYSQLI_ASSOC)) {
                                            $type_id = $type['group_id'];
                                            $type_name = $type['name'];


                                            echo " <option value=\"$type_id\">$type_name</option>";
                                        }

                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 " style="margin-left: 5%;">
                                    <label>ตำแหน่งงาน</label>
                                    <select name="position" required="true">
                                        <option value="0" selected="true">.....เลือกประเภทตำแหน่งงาน.....</option>
                                        <?php
                                        $selectpostion = "SELECT * FROM tbl_position_group";
                                        $queryposition = mysqli_query($objconnect, $selectpostion);
                                        while ($position = mysqli_fetch_array($queryposition, MYSQLI_ASSOC)) {
                                            $p_id = $position['Position_id'];
                                            $p_name = $position['name'];
                                            echo " <option value=\"$p_id\">$p_name</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputAddress">กลุ่มงาน</label>
                                <select name="workgroup" required="true">
                                    <option value="0" selected="true">.....เลือกกลุ่มงาน.....</option>
                                    <?php
                                    $selectworkgroup = "SELECT * FROM tbl_workgroupR4";
                                    $queryworkgroup = mysqli_query($objconnect, $selectworkgroup);
                                    while ($wokegroup = mysqli_fetch_array($queryworkgroup, MYSQLI_ASSOC)) {
                                        $p_idWorkGroup = $wokegroup['nameShot_sectionR4'];
                                        $p_Workgrop = $wokegroup['name_sectionR4'];
                                        echo " <option value=\"$p_idWorkGroup\">$p_Workgrop</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputAddress">ที่อยู่</label>
                                <input type="text" class="form-control" id="Address" name="Address"
                                    placeholder="บ้านเลขที่" required="true">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Username</label>
                                    <input type="text" class="form-control" id="uname" name="uname" required="true">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="password" name="password"
                                        placeholder="" required="true">
                                </div>
                            </div>
                            <div class="form-row">
                                <div>
                                    <div class="g-recaptcha" data-sitekey="6LduUM4mAAAAAB3ln1owsGPorfThJWkCI7V2qrIN">
                                    </div>
                                </div>
                                <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                            </div>
                            <div class="form-row">
                            </div>
                            <div class="card-footer text-muted" align="center">
                                <button type="submit" name="submit" value="submit"
                                    class="btn btn-primary">ยืนยัน</button>
                                <button type="reset" class="btn btn-warning">ลบข้อมูล</button>
                            </div>
                    </form>
                </div>
            </div>

        </div>
</body>

</html>