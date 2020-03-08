<?php
//Redirect the user to the login page


if (!isset($_COOKIE["user_id"]) && !isset($_COOKIE["auth_token"])) 
{
    header("location:login");
}
?>