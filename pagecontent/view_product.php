<?php

    
echo "
    <div id='content'>

    ";
$state = null;
if (isset($_GET["id"])) {
    $prod_id = $_GET["id"];
    $state = $this->get_product_access($prod_id);
}

if ($state === null){
    echo "You do not have access to this product";
}
else {
    
    $pages = $this->create_product_tableofcontent($prod_id);

    echo "<div class='viewer'>";

    echo $state;

}

echo "</div> </div>";

?>