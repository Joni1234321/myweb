<?php 
 
    echo " <div id='content'> ";

    //VARS
    $prod_id = $this->current_product_id ();

    
    //VAR CHECK 
    if ($prod_id === null) {
        $this->print_no_access("product");
        return;
    }


    //POST requirements
    //title
    //Returns to the creation page if they are not filled
    if ($_POST["title"] == ""){
        header("location:pagecreate.php?id=". $prod_id);
    }
    else {
        //SQL VALUES
        $title = $_POST["title"];
        $descr = $_POST["section"];
        $font = "normal";

        //Insert the page to the database
        $page_id = $this->insert_page($prod_id, $title);
        $this->insert_page_sect($page_id, $descr, $font);
        echo "<p> The page has been created </p>";

        //Redirect
        $url = $this->url_product($prod_id);
        $this->redirect_delay($url, .5);
    }



?>