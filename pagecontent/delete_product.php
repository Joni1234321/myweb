<?php 
    echo "<script>   
    setTimeout(function () {
       window.location.href = 'products.php';
    }, 10000); </script>";

    echo "<div id='content'>";

    echo ($this->delete_product(82));

    echo "</div>";  
    

?>