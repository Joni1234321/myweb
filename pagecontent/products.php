<?php
    echo "            
        <div id='content'>
            <div class='create'>
            <button onClick=\"document.location.href='productcreate.php'\">Create Product</button>
            </div>
            <div class='create'>
            <button onClick=\"document.location.href='productdelete.php'\">Delete Product</button>
            </div>
            <div class='viewer productgrid'>

        ";
    
    
    //Get the products from the user who is currently logged in
    $products = $this->get_products($this->get_user_id());

    //No products error message
    if ($products == null){
        echo "<p> There are 0 Products here </p>";
    }

    //Print all the products
    else { 
        while($row = $products->fetch_assoc()) {
            $this->create_product($row);
        }
    }           
    
    //Closing divs
    echo "
            </div> 
        </div>
    ";
?>