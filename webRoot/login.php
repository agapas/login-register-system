<!--
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/05/31
    *
    * Resources:
    *   PHP Tutorials, Error Handling, phpacademy, https://www.youtube.com/watch?v=-XvbXxqJ4xQ&list=PLE134D877783367C7&index=9
    *   http://html.net/tutorials/php/lesson12.php
    *   http://php.net/manual/en/book.session.php
    *   http://html5doctor.com/html5-forms-introduction-and-new-attributes
-->


<?php
    // start the session
    session_start();

    require_once "classes/Db.class.php";
    require_once "classes/General.class.php";


    $db = new DB();    // create instance of DB class & set connection
    $general = new General();   // create instance of General class


    // check if user is logged in
    if(isset($_SESSION["userId"])) {
    //if($db->getLoggedIn() === true) {
        // save a session
        $sessionUserId = $_SESSION['userId'];

        // get user data to use them on any subpage
        // NOTE: pass here all parameters which need be used to get data from database
        $userData = $db->getUserData($sessionUserId, 'id', 'username', 'password', 'email');
        //echo "<br>hi, " . $userData["username"];
        //die();

        header("Location: welcome.php");
        exit();
    }


    /* Resource:
    PHP Tutorials, Error Handling, phpacademy,
    https://www.youtube.com/watch?v=-XvbXxqJ4xQ&list=PLE134D877783367C7&index=9
    */
    $errors = array();


    if(!empty($_POST["logbtn"])) {      // using '!empty' instead of 'isset' to exclude blank fields

        $username = $_POST["username"];
        $password = $_POST["password"];

        // sanitize variables for security
        $username = $general->sanitizeString($username);
        $password = $general->sanitizeString($password);

        /*
        // removed because it's already done by 'required' in input fields
        if(empty($username) === true || empty($password) === true) {
            $errors[] = "You need to enter a username and password";
        }
        */

        if($db->getUserExists($username) === false) {
            $errors[] = "We can't find that username. Have you registered?";
        }

        if(empty($errors) === true) {

            // get hashed password stored in database
            $hash = $db->getHash($username);

            // verify that a password matches a hash
            if($general->verifyPassword($password, $hash) === false) {
                $errors[] = "Invalid password.";
            }
            else {  // password matches the hashed password, so login the user

                // set the user session (store userId)
                $_SESSION["userId"] = $db->getUserId($username);

                // redirect user to protected page
                header("Location: welcome.php");
                exit();
            }
        }
    }

    // close database connection
    $db->closeConn();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TE20_C_FSD Assessment: OOP PHP App | Login - Login & Register System</title>
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
                    <a href="shouldLogin.php">Our Offer</a>
                </div>
                <div id="right">
                    <a href="register.php">Register</a>
                    <a href="login.php">Log In</a>
                </div>
            </nav>

            <div id="content">
                <?php
                    if(empty($errors) === false) {
                        // show errors as unordered list
                        echo $general->outputErrors($errors, "<h3 class='infoh3'>We tried to log you in, but...</h3>");
                    }
                ?>

                <br>

                <h3>Login</h3>

                <form method="post" action="login.php" id="loginform">  <!-- NOTE: always set value of action attribute - it prevents iframe clickjacking attacks -->
                    <!-- Resource:
                    http://html5doctor.com/html5-forms-introduction-and-new-attributes
                    -->
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" size="20" maxlength="20" required /><br><br>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" pattern=".{6,}" title="min 6 symbols" size="20" maxlength="20" value="" required /><br><br>

                    <input type="submit" name="logbtn" id="loginbtn" value="Log in" /><br><br>
                </form>

                <br>
            </div>

            <footer>
                Copyright &copy; 2015 by Agnieszka Pas
            </footer>
        </div>
    </body>
</html>
