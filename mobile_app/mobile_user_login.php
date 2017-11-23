<?php
    error_reporting(0);
    require_once('../manage_webmaster/admin_includes/config.php');
    require_once('../manage_webmaster/admin_includes/common_functions.php');
    
if($_SERVER['REQUEST_METHOD']=='POST'){

    if (isset($_REQUEST['userEmail']) && !empty($_REQUEST['userEmail']) && !empty($_REQUEST['userPassword']) )  {

            $userEmail= $_REQUEST['userEmail'];
            $userPassword = encryptPassword($_REQUEST['userPassword']);           
           
            $result1 = "CALL userLogin('".$userEmail."','".$userPassword."')";  
            $result=$conn->query($result1);         
            $row = $result->fetch_assoc();

            if($result->num_rows > 0) {
                $response["userId"] = $row['id']; 
                $response["firstName"] = $row['first_name']; 
                $response["lastName"] = $row['last_name'];                
                $response["success"] = 0;
                $response["message"] = "Success.";
            } else {
                $response["success"] = 1;
                $response["message"] = "Invalid Credentials.";
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