<?php
//DEBUG ONLY
        echo " 
        <div id='content'>
            <p>Username: ", $_POST['username'], " </p>
            <p>Password: ", $_POST['password'], " </p> 
        ";


        //Check if passwords hash and the login hash is correct
        $sqlscript = "
        SELECT login_name, password_hash FROM user WHERE 
        password_hash = SHA1( ? ) AND login_name = ? 
        ";
        $result = $this->db_query ($sqlscript, "ss", array($_POST['password'],$_POST['username']));
        
        // print_r($result);

        if (isset($result)) {
            //LOGIN SUCCESFULL
            //LOGIN: CORRECT
            echo "                           
            <p>Hash is: ", $result[0]['password_hash'] , " </p>
            </div>";   

            $token = $this->generate_token(20);
            $user_id = $this->db_query("SELECT id FROM user WHERE login_name = ?", "s", array($_POST['username']))[0]['id'];
            $ip_address = $_SERVER["REMOTE_ADDR"];

            //The time the cookidee is stored
            $time_stored = 0;

            $this->update_hash($user_id, $token, $ip_address);

            if (isset($_POST['remember'])){
                remember_me($user_id, $token, $ip_address);
                $time_stored =  time() + (60 * 60 * 24 * 30);
            }

            setcookie("user_id", $user_id, $time_stored, "/");  //Cookie is et to expire in 1 month
            setcookie("auth_token", $token, $time_stored, "/"); //Cookie is et to expire in 1 month

            $this->redirect_to_last_page ();
            
        } else {
            //LOGIN UNSUCCESFULL
            //LOGIN: WRONG
            echo "<p> Incorrect Username/Password</p>";
        }
        
        echo"</div>";


        function remember_me ($user_id, $token, $ip_address) {

            //Store the authentication in DB

            echo "Token: " . $token . " USER ID: " . $user_id . " IP address: " . $ip_address . "<br>";
        }
        





        /*
        $sql = "
        INSERT INTO user_auth (user_id, token, ip_address, time)
        VALUES (" . get_user_id() . ", " . generate_token(20) . ", " . $_SERVER['REMOTE_ADDR'] . ", DATE_ADD(CURDATE(), INTERVAL 1 MONTH)) 
        ON DUPLICATE KEY 
        UPDATE token=" . generate_token(20) . ", time=DATE_ADD(CURDATE(), INTERVAL 1 MONTH))
        ";
        */
?>