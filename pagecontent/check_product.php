<?php 

    define("MAXFILESIZE", 500000);

    echo " 
    <div id='headline'>
        <h2>Checking product</h2>
    </div>
    <div id='content'>
    <div> " . $_POST['title'] . " </div>
    <div> " . $_POST['description'] . " </div>
    ";

    //Temp directory
    $tmp_dir = "uploads/temp/";

    //Directory
    $target_dir = "uploads/";

    $user_id = get_userid_with_token($_COOKIE['auth_token']);
    $target_dir = $target_dir . $user_id . "/";
    if (!file_exists($target_dir)){
        mkdir($target_dir);
    }
    
    $_path = $_FILES['fileToUpload']['name'];
    $extension = pathinfo($_path, PATHINFO_EXTENSION);

    $tmp_file_name = uniqid() . "." . $extension;

    //Upload to temp
    if (upload_image($extension)){
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tmp_dir . $tmp_file_name)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. <br>";
        } else {
            echo "Sorry, there was an error uploading your file. <br>";
        }
    }

    $file_name = sha1_file($tmp_dir . $tmp_file_name) . "." . $extension;


    //If file exists delete the temp file
    if (!file_exists($target_dir . $file_name)){
        rename($tmp_dir . $tmp_file_name, $target_dir . $file_name);
    }
    else {
        unlink($tmp_dir . $tmp_file_name);
    }
    
    $img_path = $target_dir . $file_name;
    $title = $_POST['title'];
    $descr = $_POST['description'];

    echo $title . "<br>" . $descr . "<br>" . $img_path; 

    create_product($img_path, $title, $descr);

    sleep(2);
    header("location:products");

    //==========================================================================§====================================================================//

    function create_product ($imagepath, $title, $description) {
        $sql = "INSERT INTO prod ( thumbnail, title, descr) VALUES ( ?, ?, ? )";
        $product_id = db_query_id($sql, "sss", array ($imagepath, $title, $description));
        create_product_acc(get_userid_with_token($_COOKIE['auth_token']), $product_id);
    }

    function create_product_acc ($userid, $productid) {
        $sql = "INSERT INTO prod_acc (user_id, prod_id, state) VALUES ( ?, ?, ? )";
        db_query_no_return($sql, "sss", array ($userid, $productid, "edit"));
    }

    //==============================================================================================================================================//

    //Returns true or false wether you can upload the image or not
    function upload_image ($imageFileType){
        $uploadOk = 1;
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check for file size 
        if ($_FILES["fileToUpload"]["size"] > MAXFILESIZE) {
            echo "Sorry, your file is too large. <br>";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. <br>";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.  <br>";
        }

        if ($uploadOk == 1) 
        { return true; }
        else 
        { return false; }
    }

    //==============================================================================================================================================//
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

    //Returns the id only
    function db_query_id ($sql, $param_type, $params){                
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
        
        $id = $conn->insert_id;

        $stmt->close();
        $conn->close();

        return $id;
    }
?>