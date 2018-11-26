<?php 
    echo "  
    <div id='headline'>
        <h2>Products</h2>
    </div>
    <div id='content' >
            <form action='productcheck.php' method='post' enctype='multipart/form-data'>
            <input type='text' name='title' placeholder='Title'> <br>
            <input type='text' name='description' placeholder='Description'> <br>
            <input type='file' name='fileToUpload' id='fileToUpload'> <br>
            <button type='submit'> Submit </button>
    </div>";
    


?>