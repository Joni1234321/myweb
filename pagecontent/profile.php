<?php
    echo "
    <div id='content'>
    ";

    //=============================================================================//
    echo "<h2> Products </h2>
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


    //=============================================================================//

    echo " </div>
    <h2>Projects: </h2>
    <div class='viewer projectgrid'>";
    
    //Get the projects from the user who is currently logged in
    $projects = $this->get_projects($this->get_user_id());

    //No projects error message
    if ($projects == null){
        echo "<p> There are 0 projects here </p>";
    }

    //Print all the projects
    else { 
        while($row = $projects->fetch_assoc()) {
            $this->create_project($row);
        }
    }  

    //Closing divs
    echo "</div> </div>"
?>