<?php 

    echo "<div id='content'>";

    //VARS
    $prod_id = $this->current_product_id();


    //VAR CHECK
    if ($prod_id === null){
        $this->print_no_access("product");
        return;
    }

    
    //DELETE
    echo ($this->delete_product($prod_id));

    //REDIRECT
    $this->redirect_delay("products", 2);

    echo "</div>";  
    

?>