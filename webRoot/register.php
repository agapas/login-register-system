<!--
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/05/31
    *
    * Resources:
    *   http://php.net/manual/en/book.session.php
    *   PHP Tutorials, Error Handling, phpacademy, https://www.youtube.com/watch?v=-XvbXxqJ4xQ&list=PLE134D877783367C7&index=9
    *   http://www.geekgumbo.com/2010/12/18/php-the-difference-between-isset-and-empty/
    *
-->

<?php 
    session_start();

    require_once "classes/Db.class.php";
    require_once "classes/General.class.php";

    $general = new General();   // create instance of General class

    $errors = array();

    //if(isset($_POST["signupbtn"])) {
    if(!empty($_POST["signupbtn"])) {   // Resource: http://www.geekgumbo.com/2010/12/18/php-the-difference-between-isset-and-empty/

        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confpassword"];
        $email = $_POST["email"];

        // sanitize variables for security
        $username = $general->sanitizeString($username);
        $password = $general->sanitizeString($password);
        $confirmPassword = $general->sanitizeString($confirmPassword);

        $email = $general->sanitizeEmail($email);

        //testing email validation
        //var_dump($email);
        //die();

        if($email === false) {
            $errors[] = "$email is a not valid email address";
        }

        // create instance of DB class
        $db = new DB(); 

        // check if username is unique
        if($db->getUserExists($username) === true) {
            $errors[] = "This username already exists";
        }

        // check if password & confirmed password match
        if($password !== $confirmPassword) {    
            $errors[] = "Password and confirmed password you entered don't match";
        }

        if(empty($errors) === true) {   // it's validate for registration

            // hash password
            $hash = $general->hashPassword($password);
           
            // register user to database
            $register = $db->registerNewUser($username, $hash, $email);     // it's 1 if registered, 0 if not registered or -1 if db error

            if($register <= 0) {
                $errors[] = "Something is wrong with your registration. Try again, please.";
            }
            else {  // registered successfully

                // set the user session (store userId)
                $_SESSION["userId"] = $db->getUserId($username);

                // redirect user to protected page
                header("Location: welcome.php");
                exit();
            }
        }

        // close database connection
        $db->closeConn();
    }
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TE20_C_FSD Assesment: OOP PHP App | Register - Login & Register System</title>
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
                <?php
                    //print_r($_SESSION);

                    if(empty($errors) === false) {
                        // show errors as unordered list
                        echo $general->outputErrors($errors, "<h3 clas='infoh3'>We tried to register you, but...</h3>");
                    }
                ?>

                <br>
                <h3>Create Account</h3>

                <form method="post" action="register.php" id="loginform">
                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" title="letters and numbers only" size="20" maxlength="20" required /><br><br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" size="30" maxlength="100" value="" required /><br><br>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" pattern=".{6,}" title="6 or more characters, letters and numbers only" size="20" maxlength="20" value="" required /><br><br>

                    <label for="confpassword">Confirm Password:</label>
                    <input type="password" name="confpassword" id="confpassword" pattern=".{6,}" title="6 or more characters, letters and numbers only" size="20" maxlength="20" value="" required /><br><br>

                    <input type="submit" name="signupbtn" id="signupbtn" value="Create Accout" />
                </form>

                <br>
            </div>  

            <footer>
                Copyright &copy; 2015 by Agnieszka Pas
            </footer>
        </div>
    </body>
</html>
