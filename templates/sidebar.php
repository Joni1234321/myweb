<?php
    
    echo '
    <a href="profile.php">
        <div class="navbox">
            <img src="img/icons/prof.png">
        </div>
    </a>

    <a href="products.php">
        <div class="navbox">
            <img src="img/icons/prod.png">
        </div>
    </a>

    <a href="projects.php">
        <div class="navbox">
            <img src="img/icons/proj.png">
        </div>
    </a>
    ';

    echo "    
    <a href='" . button_state() . ".php'>
        <div class='navbox'>
            <img src='img/icons/" . button_state() . ".png'>
        </div>
    </a>
    ";



    function button_state () {
        if (loggedin()){
            return "logout";
        }
        return "login";
    }

    function loggedin () {
        if (isset($_COOKIE["user_id"]) && isset($_COOKIE["auth_token"])){
            return true;
        }
        return false;
    }
?>