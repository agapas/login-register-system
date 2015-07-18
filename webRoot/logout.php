<?php
    /*
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/06/04
    *
    */


    // start the session
    session_start();    // resource: http://www.w3schools.com/php/php_sessions.asp

    // check if user is logged in
    if(isset($_SESSION["userId"])) {
        session_unset();        // remove all session variables
        session_destroy();      // destroy all data associated with the current session
    }
    else {
        header("Location: index.php");  // redirect the user to index.php
        exit();
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TE20_C_FSD Assesment: OOP PHP App | Logout - Login & Register System</title>
        <link rel="stylesheet" href="css/style.css">

    </head>
    <body>
        <div id="wrapper">
            <header>
                <h2>TE20_C_FSD Assesment - OOP PHP App:</h2>
                <h2 id="subtitle">The Login / Register System</h2>
            </header>
            
            <nav>
                <div id="left">
                    <a href="index.php">Home</a>
                    <a href="shouldLogin.php">Our Offer</a>
                </div>
                <div id="right">
                    <a href="register.php">Register</a>
                    <a href="login.php">Log In</a>
                </div>
            </nav>

            <div id="content">
                <br>
                <h2>Logged Out</h2>
                <p class="infop">You have been successfully logged out</p>
                <br>
            </div>

            <footer>
                Copyright &copy; 2015 by Agnieszka Pas
            </footer>
        </div>
    </body>
</html>
