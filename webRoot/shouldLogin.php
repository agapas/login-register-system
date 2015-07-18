<!--
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/06/06
    *
-->

<?php 
    // start the session
    session_start();

    // check if user is logged in
    if(isset($_SESSION["userId"])) {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TE20_C_FSD Assesment: OOP PHP App | ShouldLogin - Login & Register System</title>
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
                <h2>Please log in first</h2>
                <p class="infop">You need be login to see the content of this page</p>
                <br>
            </div>

            <footer>
                Copyright &copy; 2015 by Agnieszka Pas
            </footer>
        </div>
    </body>
</html>
