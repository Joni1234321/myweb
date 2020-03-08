<?php 

    echo "<div id='content'>";

    //VARS
    $prod_id = $this->current_product_id();
    

    //VAR CHECK
    if ($prod_id === null) {
        $this->print_no_access("product");
        return;
    }

    
    //PRINT
    $this->print_create_page_form($prod_id);

    echo "</div>";
    
?>