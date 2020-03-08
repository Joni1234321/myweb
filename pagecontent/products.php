<?php

    echo "            
        <div id='content'>
            <div class='create'>
            <button onClick=\"document.location.href='productcreate.php'\">Create Product</button>
            </div>
            <div class='viewer productgrid'>

        ";
    
    
    //VARS
    $products = $this->get_products($this->get_user_id());


    //VAR CHECK
    if ($products === null){
        echo "<p> There are 0 Products here </p>";
        return;
    }


    //CREATE
    while($row = $products->fetch_assoc()) {
        $this->create_product($row);
    }        
    

    echo "</div>  </div>";

?>