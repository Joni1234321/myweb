<?php

    echo " <div id='content'> ";

    //VARS
    $prod_id = $this->current_product_id();
    $page_id = $this->current_page_id();
    $page_title = $this->get_page_title ($page_id);
    $state = $this->get_product_access($prod_id);


    //VAR CHECK
    if ($prod_id === null){
        $this->print_no_access("product");
        return;
    }
    if ($state === null){
        $this->print_no_access("product");
        return;
    }

    //CREATE
    $pages = $this->create_product_tableofcontent($prod_id);
    echo "
        <div class='delete'>
        <button onClick=\"document.location.href='productdelete.php?id=" . $prod_id . "'\">Delete Product</button>
        </div>
    ";


    //VAR CHECK
    if ($page_id === null){
        $this->print_no_access("page");
        return;
    }
    if ($page_title === null){
        $this->print_no_access("page");
        return;
    }

    //BUTTONS PAGE
    echo "    
    <div class='delete'>
        <button onClick=\"document.location.href='pagedelete.php?id=" . $prod_id . "&page=" . $page_title . "'\">Delete Page</button>
    </div>
    <div class='create'>
        <button onClick=\"document.location.href='pagecreate.php?id=" . $prod_id . "'\">Create Page</button>
    </div>
        
    <div class='viewer'>";
    
    //CREATE
    $this->create_page_sections($page_id);

    //BUTTONS SECTION
    echo "
    <div class='create'>
    <button onClick=\"document.location.href='sectioncreate.php?id=" . $prod_id . "&page=" . $page_title . "'\">Create Section</button>
    </div>
    ";

    echo "<hr>". $state;

    echo "</div> </div>";

    //Enable section editing
    $this->js_section_edit();

?>