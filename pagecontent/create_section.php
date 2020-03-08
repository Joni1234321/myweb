<?php 

    echo "<div id='content'>";

    //VARS
    $prod_id = $this->current_product_id();
    $page_id = $this->current_page_id();
    $page_title = $this->get_page_title($page_id);


    //VAR CHECK
    if ($prod_id === null) { 
        $this->print_no_access ("product");
        return;
    }
    if ($page_id === null || $page_title === null) {
        $this->print_no_access("page");
        return;
    }

    
    //PRINT FORM
    $this->print_create_page_section_form($prod_id, $page_title);    

    echo "</div>";
    
?>