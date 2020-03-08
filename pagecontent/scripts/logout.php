<?php 
    //Unset cookies
    unsetCookie("auth_token");
    unsetCookie("user_id");

    header("Location:login");
    
    function unsetCookie ($cookie_name) {
        setcookie($cookie_name, "", 1, "/");
    }
?> 