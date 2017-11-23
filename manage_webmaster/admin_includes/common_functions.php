<?php
    
    //common function for web / android /ios user registration
     function saveUser($user_full_name, $user_email, $user_mobile, $user_password,$lkp_status_id,$login_count,$last_login_visit,$lkp_register_device_type_id,$user_login_type,$mobile_token,$created_at) {
        //Save data into users table
        global $conn;
        $created_at = date("Y-m-d h:i:s");
        $sqlIns = "INSERT INTO users (user_full_name,user_email,user_mobile,user_password,lkp_status_id,login_count,last_login_visit,lkp_register_device_type_id,lkp_user_login_type_id,mobile_token,created_at) VALUES ('$user_full_name','$user_email','$user_mobile','$user_password','$lkp_status_id','$login_count','$last_login_visit','$lkp_register_device_type_id','$user_login_type','$mobile_token','$created_at')"; 
        if ($conn->query($sqlIns) === TRUE) {
            return 1;
        } else {
            return 0;
        }
    }

    function getBanners() {
        global $conn;
        $sql="SELECT * FROM `services_banners` AS sb, `services_category` sc WHERE sc.`lkp_status_id` = '0' AND  sb.`lkp_status_id` = '0 '  ";
        $result = $conn->query($sql);        
        return $result;
    }

    function userLogin($user_email,$user_pwd) {
        global $conn;
        $sql="SELECT * FROM users WHERE (user_email = '$user_email' OR user_mobile = '$user_email') AND user_password = '$user_pwd' ";
        $result = $conn->query($sql);        
        return $result;
    }

    function forgotPassword($email) {
        global $conn;
        $sql="SELECT * from users WHERE user_email = '$email' ";
        $result = $conn->query($sql);
        if ($row = $result->fetch_assoc()) {
            $pwd = decryptPassword($row['user_password']);
            $to = $email;
            $subject =  "User Forgot Password";
            $message = "<html><head><title>User New Password</title></head>
                <body>
                    <table rules='all' style='border-color: #666;' cellpadding='10'>
                        <tr><td><strong>Your Password:  </strong>$pwd</td></tr>
                    </table>
                </body>
                </html>
                ";
            $from = "info@myservant.com";
            sendEmail($to,$subject,$message,$from);
            return 1;
        } else {
            return 0;
        }
    }

    function sendMobileSMS($message,$user_mobile) {
        global $conn;
        $username = "*****";
        $password = "*****";
        $numbers = "$user_mobile"; // mobile number
        $sender = urlencode('*****'); // assigned Sender_ID
        // Message text required to deliver on mobile number
        $data = "username="."$username"."&password="."$password"."&to="."$numbers"."&from="."$sender"."&msg="."$message"."&type=1";
        $data = "https://www.smsstriker.com/API/sms.php?".$data;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$data);
        $response = curl_exec($ch);        
    }
    
    function checkUserAvail($table,$clause,$value){
        global $conn;
        $sql = "SELECT * FROM `$table` WHERE `$clause`= '$value' ";
        $result = $conn->query($sql);
        if($result->num_rows>0){
            $returnStmt = "User Already Exist";
            return $returnStmt;
        }
    }

    function sendEmail($to,$subject,$message,$from) {
        global $conn;        
        $headers = 'From: "'.$from.'"' . "\r\n" .            
            'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);

    }

    function getAllData($table)  {
        global $conn;
        $sql="select * from `$table` ";
        $result = $conn->query($sql);        
        return $result;
    }

    function getAllDataWithActiveRecent($table)  {
        global $conn;
        $sql="select * from `$table` ORDER BY lkp_status_id, id DESC ";
        $result = $conn->query($sql); 
        return $result;
    }

    function getIndividualDetails($table,$clause,$id)  {
        global $conn;
        $sql="select * from `$table` where `$clause` = '$id' ";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();        
        return $row;
    }

    function getAllDataWhere($table,$clause,$value)  {
        global $conn;
        $sql="select * from `$table` WHERE `$clause` = '$value' ";
        $result = $conn->query($sql);        
        return $result;
    }

    function getAllDataWhereWithActive($table,$clause,$value)  {
        global $conn;
        $sql="select * from `$table` WHERE `$clause` = '$value' AND lkp_status_id = '0' ";
        $result = $conn->query($sql);        
        return $result;
    }

    function getAllDataWhereWithTWoConditions($table,$clause1,$value1,$clause2,$value2)  {
        global $conn;
        $sql="select * from `$table` WHERE `$clause1` = '$value1' AND `$clause2` = '$value2' AND lkp_status_id = '0' ";
        $result = $conn->query($sql);        
        return $result;
    }

    function getAllDataWhereWithThreeConditions($table,$clause1,$value1,$clause2,$value2,$clause3,$value3)  {
        global $conn;
        $sql="select * from `$table` WHERE `$clause1` = '$value1' AND `$clause2` = '$value2' AND `$clause3` = '$value3' AND lkp_status_id = '0' ";
        $result = $conn->query($sql);        
        return $result;
    }

    function getAllDataWithStatus($table,$status)  {
        global $conn;
        $sql="select * from `$table` WHERE `lkp_status_id` = '$status' ";
        $result = $conn->query($sql); 
        return $result;
    }

    function getAllDataWithStatusLimit($table,$status,$minlimit,$maxlimit)  {
        global $conn;
        if($minlimit!='' && $maxlimit!='') {
            $sql="select * from `$table` WHERE `lkp_status_id` = '$status' ORDER BY id DESC LIMIT $minlimit,$maxlimit";
        } else {
            $sql="select * from `$table` WHERE `lkp_status_id` = '$status' ORDER BY id DESC";
        }
         
        $result = $conn->query($sql); 
        return $result;
    }

    function getServicesProviderDataLimit($minlimit,$maxlimit)  {
        global $conn;
        if($minlimit!='' && $maxlimit!='') {
            $sql="SELECT * FROM `service_provider_registration` AS spr, `service_provider_business_registration` spbr WHERE spr.`lkp_status_id` = '0' AND  spbr.`service_provider_registration_id` = spr.`id` AND  spr.`service_provider_type_id` = 1 ORDER BY spr.`id` DESC LIMIT $minlimit,$maxlimit ";
        } else {
            $sql="SELECT * FROM `service_provider_registration` AS spr, `service_provider_business_registration` spbr WHERE spr.`lkp_status_id` = '0' AND  spbr.`service_provider_registration_id` = spr.`id` AND  spr.`service_provider_type_id` = 1 ORDER BY spr.`id` DESC ";
        }        
         
        $result = $conn->query($sql); 
        return $result;
    }
    
    function getRowsCount($table)  {
        global $conn;
        $sql="select * from `$table` ";
        $result = $conn->query($sql);
        $noRows = $result->num_rows;
        return $noRows;
    }

    /* encrypt and decrypt password for login*/
     function encryptPassword($pwd) {
        $key = "123";
        $admin_pwd = bin2hex(openssl_encrypt($pwd,'AES-128-CBC', $key));
        return $admin_pwd;
    }

    function decryptPassword($admin_password) {
        $key = "123";
        $admin_pwd = openssl_decrypt(hex2bin($admin_password),'AES-128-CBC',$key);
        return $admin_pwd;
    }
   
    function getImageUnlink($val,$table,$clause,$id,$target_dir) {
        global $conn;
        $getBanner = "SELECT $val FROM $table WHERE $clause='$id' ";
        $getRes = $conn->query($getBanner);
        $row = $getRes->fetch_assoc();
        $img = $row[$val];
        $path = $target_dir.$img.'';
        chown($path, 666);
        if (unlink($path)) {
            return 1;
        } else {
            return 0;
        }
    }
?>
