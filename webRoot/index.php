<!--
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/06/05
    *
    * Resources:
    * http://html.net/tutorials/php/lesson12.php
    * image is taken from: http://www.wallpaperhere.com/Burning_Fire_Letter_X_101716/download_1920x1200
    *
-->

<?php 
    
    session_start();    // start the session

    require_once "classes/Db.class.php";
    require_once "classes/General.class.php";


    // check if user is logged in
    if(isset($_SESSION["userId"])) {
        // save a session
        $sessionUserId = $_SESSION['userId'];

        // create instance of DB class & set connection
        $db = new DB();   

        // get user data
        $userData = $db->getUserData($sessionUserId, 'id', 'username', 'password', 'email');

        //echo "<br>hi, " . $userData["username"];
        //die();

        // close database connection
        $db->closeConn();
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TE20_C_FSD Assesment: OOP PHP App | Home - Login & Register System</title>
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
                     <?php
                        if(isset($_SESSION["userId"])) {
                            $link = "welcome.php";
                        }
                        else {
                            $link = "shouldLogin.php";
                        }
                        echo "<a href=$link class='homeLink'>Our Offer</a>";
                    ?>
                </div>
                <div id="right">
                    <?php
                        if(!isset($_SESSION["userId"])) {
                            echo "<a href='register.php'>Register</a>
                            <a href='login.php'>Log In</a>";
                        }
                        else {
                            echo "<a href='logout.php'>Log Out</a>";
                        }
                    ?>
                </div>
            </nav>

            <div id="content">
                <h2>The Company X</h2>

                <p style="text-align:center;">Please, try our new Login & Register System and see our great offer</p>
                
                <!-- image resource: http://www.wallpaperhere.com/Burning_Fire_Letter_X_101716/download_1920x1200 -->
                <img id="fireX" src="images/FireLetterX.jpg" alt="fire letter X img" width="60%" height="30%" >
            </div>

            <footer>
                Copyright &copy; 2015 by Agnieszka Pas
            </footer>
        </div>
    </body>
</html>
