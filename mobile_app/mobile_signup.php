<?php
    error_reporting(0);
    require_once('../manage_webmaster/admin_includes/config.php');
    require_once('../manage_webmaster/admin_includes/common_functions.php');

if($_SERVER['REQUEST_METHOD']=='POST'){

    if (isset($_REQUEST['firstName']) && !empty($_REQUEST['firstName']) && !empty($_REQUEST['lastName']) && !empty($_REQUEST['lastName']) && !empty($_REQUEST['userEmail']) && !empty($_REQUEST['userPassword']) ) {
            
            $firstName = $_REQUEST['firstName'];
            $lastName = $_REQUEST['lastName'];
            $userEmail = $_REQUEST['userEmail'];
            $userPassword = encryptPassword($_REQUEST['userPassword']);
            //Send parames with stored procedures function
            $getCount = "CALL checkUserAvail ('" . $userEmail . "')";
            $output = $conn->query($getCount);
            //When call multiple stored procedures using below function
            $conn->next_result();
            if($output->num_rows > 0) {
                $response["success"] = 4;
                $response["message"] = "Email Already Exists! Please Enter New Email";

            } else {
                if($conn->query("CALL saveUserData('$firstName','$lastName', '$userEmail','$userPassword')") ) {
                $response["success"] = 0;
                $response["message"] = "You have successfully registered and logged in";

                } else {
                    $response["success"] = 1;
                    $response["message"] = "An error occurred during registration.";
                }
            }                      

    }  else {
        $response["success"] = 3;
        $response["message"] = "Required field(s) is missing";
    } 
    
} else {
    $response["success"] = 4;
    $response["message"] = "Invalid request";

}

echo json_encode($response);

?>