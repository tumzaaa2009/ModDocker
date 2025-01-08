<?php 
 include 'connect/connect.php';

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
   $login = "SELECT ulog.user_name as username ,emp.work_group,emp.Emp_name as name ,emp.Emp_lastname as lastname,emp.Emp_id as id,typeemp.name AS typename,typeemp.group_id AS groupid,pgroup.name AS pname,
            emp.Emp_Status as emp_status , emp.work_group as work_group   FROM tbl_userlogin as ulog INNER JOIN tbl_employment as emp 
            ON ulog.Emp_id = emp.Emp_id INNER JOIN tbl_group_type AS typeemp ON typeemp.group_id = emp.group_id
            INNER JOIN tbl_position_group pgroup ON pgroup.Position_id=emp.group_id
            where emp.Emp_name= '" .$given_name . "' and emp.Emp_lastname='" . $family_name . "'
            and emp_status = '1' and emp.work_group != '' ";
            $objQuery = mysqli_query($objconnect, $login);
            $num = mysqli_num_rows($objQuery);
            if ($num == 1) { ?>

            <form id="redirectForm" action="<?php echo htmlspecialchars("https://rh4.moph.go.th/login.php");?>" method="post">
                <?php
                                foreach ($objQuery as $key => $value) { 
                                    echo $workGroup = strtolower($value["work_group"]);
                                    echo '<input type="hidden" name="workgroup" value="' . htmlspecialchars($workGroup) . '">';
                                }
                                ?>
            </form>
            <script type="text/javascript">
            document.getElementById('redirectForm').submit();
            </script>

            <?php     }}}?>