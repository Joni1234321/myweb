<?php
        echo "  <div id='headline'>
                        <h2>Login</h2>
                </div>
                <div id='content'>
                        <form action='logincheck.php' method='post'>
                        <input type='text' name='username' placeholder='Username'> <br>
                        <input type='password' name='password' placeholder='Password'> <br>
                        <input type='checkbox' name='remember' value='remember'> <br>
                        <button type='submit'> Submit </button>
                </div>";

        
        $cookie_name = "auth_token";
        if (isset($_COOKIE[$cookie_name])) {
                login_with_token($_COOKIE[$cookie_name]);
        }
        else {
                print("Please login");
        }
        
        

        function login_with_token ($token) {

                $user_id = get_userid_with_token($token);
                if (isset($user_id)){
                        print($user_id);
                        print("HELLO THERE");
                        generate_new_token($user_id);
                        header("Location:profile");
                }
                else {
                        print ("Sorry, there has been an error, and the login could not be completed <br>");
                        print ("Please enter your credentials again if you wish to log in");
                }
        }


        function generate_new_token ($id) {
                
        }
        
        function get_userid_with_token ($token){
        $result = db_query("SELECT id FROM user_auth WHERE token = ? AND ip_address = ?", "ss", array($token, $_SERVER["REMOTE_ADDR"]));
        while($userdata = $result){
                //USERDATA['id'] is the id of the user that logged in
                return ($userdata[0]['id']);
            }
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
?>