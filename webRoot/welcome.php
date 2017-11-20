<!--
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/06/05
    *
    * Resources:
    *   http://php.net/manual/en/book.session.php
    *   http://php.net/manual/en/function.date.php
    *   http://php.net/manual/en/function.date-default-timezone-set.php
    *
-->

<?php
    // start the session
    session_start();

    require_once "classes/Db.class.php";
    require_once "classes/General.class.php";


    // check if user is logged in
    if(!isset($_SESSION["userId"])) {
        // redirect user to login page
        header("Location: login.php");
        exit();
    }
    else {
        // save a session
        $sessionUserId = $_SESSION['userId'];

        // create instance of DB class & set connection
        $db = new DB();

        // get user data
        $userData = $db->getUserData($sessionUserId, 'id', 'username', 'password', 'email');

        // close database connection
        $db->closeConn();
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TE20_C_FSD Assessment: OOP PHP App | Welcome - Login & Register System</title>
        <link rel="stylesheet" href="css/style.css">

    </head>
    <body>
        <div id="wrapper">
            <header>
                <h2>TE20_C_FSD Assessment - OOP PHP App:</h2>
                <h2 id="subtitle">The Login / Register System</h2>
            </header>

            <nav>
                <div id="left">
                    <a href="index.php">Home</a>
                    <a href="#">Our Offer</a>
                </div>
                <div id="right">
                    <a href="logout.php">Log Out</a>
                </div>
            </nav>

            <div id="content">
                <?php
                    echo "<h2>Welcome, " . $userData["username"] . "</h2>";

                    date_default_timezone_set("Europe/Dublin");
                    echo "<p class='infop'>You are successfully logged in at: " . date('h:ia') . " and ... and this is good time for coffee !</p>";
                ?>

                <img id="welcomeImg" src="images/coffeesmile.png" alt="coffee image" width="73%" height="50%" >
                <br>
            </div>

            <footer>
                Copyright &copy; 2015 by Agnieszka Pas
            </footer>
        </div>
    </body>
</html>
