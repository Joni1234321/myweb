<html>
<?php 
    require_once("cms.php");
    $obj = new CMS ("DERP");
    $obj->create_page_template("derp");
    
?>
    <div class="page-content">
        <?php require("pagecontent/products.php");?>
    </div>
</body>

</html>