<?php

    
echo "
    <div id='content'>
    <div class='viewer'>
    ";

$state = $this->access_product($_GET["id"]);
if ($state === null){
    echo "You do not have access to this product";
}
else {
    echo $state;
}

echo "</div> </div>"

?>