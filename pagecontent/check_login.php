<?php
        echo " 
        <div id='headline'>
            <h2>Login</h2>
        </div>
        <div id='content'>
            <p>Username: ", $_POST['username'], " </p>
            <p>Password: ", $_POST['password'], " </p> 
        ";


        //Check if passwords hash and the login hash is correct
        $sqlscript = "SELECT login_name, password_hash   FROM user WHERE 
        password_hash = SHA1( ? ) AND login_name = ? ";


        $result = db_query ($sqlscript, "ss", array($_POST['password'],$_POST['username']));
        print("Result :  ");
        print_r($result);

        if (isset($result)) {
            //LOGIN SUCCESFULL
            //LOGIN: CORRECT
            echo "                           
            <p>Hash is: ", $result[0]['password_hash'] , " </p>
            </div>";   

            $token = generate_token(20);
            $user_id = get_userid();
            $ip_address = $_SERVER["REMOTE_ADDR"];

            //The time the cookie is stored
            $time_stored = 0;

            update_hash($user_id, $token, $ip_address);

            if (isset($_POST['remember'])){
                remember_me($user_id, $token);
                $time_stored =  time() + (60 * 60 * 24 * 30);
            }

            setcookie("user_id", $user_id, $time_stored, "/");  //Cookie is et to expire in 1 month
            setcookie("auth_token", $token, $time_stored, "/"); //Cookie is et to expire in 1 month

            header("location:profile");
            
        } else {
            //LOGIN UNSUCCESFULL
            //LOGIN: WRONG
            echo "<p> Incorrect Username/Password</p>";
        }
        
        echo"</div>";


        function remember_me ($user_id, $token) {

            //Store the authentication in DB

            echo "Token: " . $token . " USER ID: " . $user_id . " IP address: " . $ip_address . "<br>";
        }
        
        function update_hash ($user_id, $token, $ip_address){
            $sql = "SELECT * from user_auth where user_id= ? AND ip_address= ?";
            //If the auth doesn't already exsist
            
            if (db_query($sql, "ss", array($user_id, $ip_address)) == NULL){
                echo "Doesn't exist";

                $sql = "INSERT INTO user_auth (user_id, token, ip_address, time)
                VALUES ( ? , ? , ? , DATE_ADD(CURDATE(), INTERVAL 1 MONTH));";
                $params = array($user_id, $token, $ip_address);
            }
            else{
                echo "Does exist";
                $sql = "UPDATE user_auth 
                SET token= ?, time=DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
                WHERE user_id= ? AND ip_address= ?";
                $params = array($token , $user_id , $ip_address);
            }
            db_query_no_return($sql, "sss", $params);
        }

        //Returns the current users userid
        function get_userid (){
            $result = db_query("SELECT id FROM user WHERE login_name = ?", "s", array($_POST['username']));
            while($userdata = $result){
                //USERDATA['id'] is the id of the user that logged in
                return ($userdata[0]['id']);
            }
        }

        function generate_token ($length){
            return bin2hex(openssl_random_pseudo_bytes ($length));
        }



        function db_query ($sql, $param_type, $params){                
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "myweb";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    return;
            }

            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param($param_type, ...$params);
            $stmt->execute();

            $meta = $stmt->result_metadata();
            while ($field = $meta->fetch_field())
            {
                $result_names[] = &$row[$field->name];
            }
        
            call_user_func_array(array($stmt, 'bind_result'), $result_names);
            $returnvalue = NULL;
            while ($stmt->fetch()) {
                foreach($row as $key => $val)
                {
                    $c[$key] = $val;
                }
                $returnvalue[] = $c;
            }

            $stmt->close();
            $conn->close();
            
            return $returnvalue;
        }



        function db_query_no_return ($sql, $param_type, $params){                
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "myweb";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                    return;
            }

            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param($param_type, ...$params);
            $stmt->execute();

            $stmt->close();
            $conn->close();
            
        }


        /*
        $sql = "
        INSERT INTO user_auth (user_id, token, ip_address, time)
        VALUES (" . get_userid() . ", " . generate_token(20) . ", " . $_SERVER['REMOTE_ADDR'] . ", DATE_ADD(CURDATE(), INTERVAL 1 MONTH)) 
        ON DUPLICATE KEY 
        UPDATE token=" . generate_token(20) . ", time=DATE_ADD(CURDATE(), INTERVAL 1 MONTH))
        ";
        */
?>