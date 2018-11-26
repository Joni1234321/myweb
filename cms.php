<?php

    class CMS {
        var $servername = "localhost";
        var $username = "root";
        var $password = "";
        var $dbname = "myweb";
    

        function __construct ($title) {
            
        }

        public function create_page_template ($title) {
            echo '

                <head> 
                    <title>' . $title . '</title>
                    <link rel="stylesheet" href="style/home.css">
                    <link rel="stylesheet" href="style/prod.css">
                    <link rel="stylesheet" href="style/prof.css">
                    <link rel="stylesheet" href="style/proj-view.css">
                    <link rel="stylesheet" href="style/proj.css">
                    <link rel="stylesheet" href="style/style.css">
                </head>
                <body>
                    <div class="page-sidebar"> ';
                    
                    require('templates/sidebar.php');

                    echo "</div>";
                
                
        }

        //Do MYSQL
        public function db_query ($sql, $param_type, $params){
    
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

        //DO MYSQL WITHOUT RETURNS
        public function db_query_no_return ($sql, $param_type, $params){                
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

        //Return the user id
        public function get_userid_with_token ($token){
            $result = db_query("SELECT user_id FROM user_auth WHERE token = ? AND ip_address = ?", "ss", array($token, $_SERVER["REMOTE_ADDR"]));
            while($userdata = $result){
                //USERDATA['id'] is the id of the user that logged in
                return ($userdata[0]['user_id']);
            }
        }
    
    }

?>