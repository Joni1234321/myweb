<?php 

    echo "<div id='content'>";

    //VARS
    $prod_id = $this->current_product_id();
    $page_id = $this->current_page_id ();
    $page_title = $this->get_page_title($page_id);
    $url = "products";


    //VAR CHECK
    if ($prod_id === null) { 
        $this->print_no_access("product");
        return;
    }
    if ($page_id === null && $page_title === null) {
        $this->print_no_access("page"); 
        return;   
    }


    //DELETE
    $url = $this->url_product ($prod_id);
    echo ($this->delete_page($page_id));

    //REDIRECT
    $this->redirect_delay($url, 2);

    echo "</div>";  
    
?>