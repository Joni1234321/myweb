<?php
    echo file_get_contents("C:/Users/Jonas/OneDrive - AARHUS TECH/Programmer/Websites/MyWeb/templates/html/sidebar.html");

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