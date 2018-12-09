<?php 

    define("MAXFILESIZE", 500000);

    echo " 
    <div id='content'>
    <div> " . $_POST['title'] . " </div>
    <div> " . $_POST['description'] . " </div>
    ";

    //Temp directory
    $tmp_dir = "uploads/temp/";

    //Target directory
    $target_dir = "uploads/";
    $user_id = $this->get_user_id();
    $target_dir = $target_dir . $user_id . "/";

    //Create new folder if it doesn't exist
    if (!file_exists($target_dir)){
        mkdir($target_dir);
    }
    
    //Get the files extension
    $_path = $_FILES['fileToUpload']['name'];
    $extension = pathinfo($_path, PATHINFO_EXTENSION);

    //Create temp file
    $tmp_file_name = uniqid() . "." . $extension;

    //Upload to temp folder
    if ($this->upload_image($extension)){
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $tmp_dir . $tmp_file_name)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. <br>";
        } else {
            echo "Sorry, there was an error uploading your file. <br>";
        }
    }

    //Generate the unique filename by sha1 the file 
    $file_name = sha1_file($tmp_dir . $tmp_file_name) . "." . $extension;

    //If file exists delete the temp file
    if (!file_exists($target_dir . $file_name)){
        rename($tmp_dir . $tmp_file_name, $target_dir . $file_name);
    }
    //Else upload it to the users folder
    else {
        unlink($tmp_dir . $tmp_file_name);
    }
    
    //SQL VALUES
    $img_path = $target_dir . $file_name;
    $title = $_POST['title'];
    $description = $_POST['description'];

    //
    $this->db_insert_product($img_path, $title, $description);

    //Upload sleep
    sleep(2);
    header("location:products");

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



?>