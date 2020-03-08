<?php 

    //POST requirements
    //title
    //description
    //fileToUpload
    //Returns to the creation page if they are not filled
    if ($_POST["title"] == "" || 
        $_POST["description"] == "" ||
        $_FILES["fileToUpload"]["name"] == "")
    {
        header("location:productcreate");
    }
    else {
        define("MAXFILESIZE", 500000);

        echo " 
        <div id='content'>
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
                echo "<p> The image has been uploaded. </p>";
            } else {
                echo "<p> Sorry, there was an error uploading your file. </p> <br>";
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

        //Insert the products to the database
        $this->insert_product($img_path, $title, $description);

        echo "<p> File has been succesfully created </p>";
        //Upload sleep
        $this->redirect_delay("products", 2);

    }
?>