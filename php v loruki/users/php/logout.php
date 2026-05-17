<?php
//Starts the Session
session_start();

//Destroy the session
session_unset(); //Remove all the session variables
session_destroy(); //Destroy the session itself

//Redirect the user to the login page
header("location:login.php");
exit;
?>