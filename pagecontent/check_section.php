<?php 
 
    echo " <div id='content'> ";

    //VARS
    $prod_id = $this->current_product_id ();
    $page_id = $this->current_page_id ();
    $page_title = $this->get_page_title ($page_id);


    //VAR CHECK 
    if ($prod_id === null) {
        $this->print_no_access("product");
        return;
    }
    //VAR CHECK 
    if ($page_id === null || $page_title === null) {
        $this->print_no_access("page");
        return;
    }


    //POST requirements
    //text
    //Returns to the creation section if they are not filled
    if ($_POST["text"] == ""){
        header("location:sectioncreate.php?id=". $prod_id . "&page=" . $page_title);
    }
    else {
        //SQL VALUES
        $text = $_POST["text"];
        $font = "normal";

        //Insert the section to the database
        $this->insert_page_sect($page_id, $text, $font);
        echo "<p> The section has been created </p>";

        //Redirect
        $url = $this->url_product($prod_id);
        $this->redirect_delay($url, .5);
    }



?>