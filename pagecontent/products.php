<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "myweb";

    echo "  <div id='headline'>
                <h2>Products</h2>
                <div class='create'>
                    <button onClick=\"document.location.href='productcreate.php'\">Create Product</button>
                </div>
            </div>
            <div id='content'>
            <div class='productgrid'>
            ";
    


    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //Get user id
    $userid = get_userid_with_token($_COOKIE["auth_token"]);

    //Get the products
    //Get product id
    $sql = "SELECT prod_id FROM prod_acc WHERE user_id = ?";
    $product_id = db_query($sql, "i", array($userid));
    if (!isset($product_id)){
        echo "0 RESULTS";
    }
    else {
        $product_ids = array_column($product_id, "prod_id");
        //Get the products
        $sql = "SELECT id, thumbnail, title, descr FROM prod  
        WHERE id IN (" . implode(",",  $product_ids) . ")";

        $result = $conn->query($sql);   
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "
            <a href='products.php'><div class='product' id='" . $row["id"] . "'>
            <img class='thumbnail' src='". $row["thumbnail"] . "'>
            <p class='title'>" . $row["title"] . " </p>
            <p class='description'>" . $row["descr"] . " </p>
            </div> </a>";
        }
        
        $conn->close();
    }
    
    function get_userid_with_token ($token){
        $result = db_query("SELECT user_id FROM user_auth WHERE token = ? AND ip_address = ?", "ss", array($token, $_SERVER["REMOTE_ADDR"]));
        while($userdata = $result){
            //USERDATA['id'] is the id of the user that logged in
            return ($userdata[0]['user_id']);
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

    //Closing divs
    echo "</div> 

    </div>"
?>