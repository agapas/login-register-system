<?php

    /*
    * Author: Anieszka Pas
    * Assignment: TE FSD, Digital Skills Academy
    * Student ID: D14128601
    * Date: 2015/06/01
    *
    * Resources:
    *    http://php.net/manual/en/book.mysqli.php
    *    http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php, answered by Theo
    *    https://pear.php.net/manual/en/standards.php
    *
    */


    /*
      DB.class
      - description:
        The class cooperates with the database. 
        It uses prepared statemets method within class methods 
        to exclude malicious SQL injections.

      - class data:
        $dbConn;            // database connection
        $error;             // error message (not showing the user our files structure)

      - class methods:
        __construct()                   // a constructor: invokes connection to database
        connect()                       // set connection to database
        closeConnection()               // closes connection with database
        getUserExists( $username )      // returns true if user exists in database and false if not,
        getUserId( $username )          // get id of user from database, searching by username
        getHash( $username )            // get hashed password from database, searching by username
        getUserData( $userId )          // get user's all data as an array, searching database by userId
        registerNewUser( $username, $password, $email )     // register the user, returns number of affected rows
    */


    include __DIR__ . "/../../includes/config.php";   // file with database login details, __DIR__ available since php 5.3.0

    error_reporting(0);        // turn off reporting of mySQL errors
    //error_reporting(E_ALL);  // report all errors


    class DB 
    {
        // class variables
        private $dbConn;        // database connection
        private $error = "Sorry, we're experiencing connection problems.";   // database connection error


        // the constructor
        public function __construct() 
        {
            $this->connect();
        }

        // set connection to database
        public function connect()
        {
            // connect to database (procedural style) using constants defined in config.php file

            //$this->dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die(mysqli_connect_error($this->dbConn));
            $this->dbConn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die($this->error);
        }


        // close database connection
        public function closeConn() 
        {
            if(isset($this->dbConn)) {
                mysqli_close($this->dbConn);
                unset($this->dbConn);
            }
        }


        // check if user already exists in database (using prepared statements method)
        public function getUserExists($username) 
        {
            $exists = false;
            
            /*
            // This is the old working version of the code, switched to 
            // prepared statements method to exclude malicious SQL query injections

            $sql = "SELECT `id` FROM `users` WHERE `username` = '$username'";
            $result = mysqli_query($this->dbConn, $sql) or die(mysqli_error($this->dbConn));

            if($result) {
                while($row = $result->fetch_assoc()) {
                    if($row['id'] && $row['id'] > 0) {
                        $exists = true;
                    }
                }

                // free memory associated with result
                mysqli_free_result($result);
            }
            */

            // initialize a statement object suitable for mysqli_stmt_prepare()
            //$stmt = mysqli_stmt_init($this->dbConn) or die(mysqli_error($this->dbConn));
            $stmt = mysqli_stmt_init($this->dbConn) or die($this->error);

            // set the query string
            $sql = "SELECT `id` FROM `users` WHERE `username` = ?";

            // prepare an SQL statement for execution
            if(mysqli_stmt_prepare($stmt, $sql)) {

                // bind parameters for markers   
                mysqli_stmt_bind_param($stmt, 's', $username);  // where 's' is the string type
        
                // execute previously prepared statement
                if(mysqli_stmt_execute($stmt)) {    // returns true on success or false on failure

                    // gets a result set from a prepared statement
                    $result = mysqli_stmt_get_result($stmt);

                    if($result) {
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            //echo $row['id'];
                            if($row['id'] && $row['id'] > 0) {
                                $exists = true;
                            }
                        }

                        // free memory associated with the result
                        mysqli_free_result($result);
                    }
                }
            }

            // close the statement
            mysqli_stmt_close($stmt);

            return $exists;
        }


        // get userId by username (username is set as unique)
        public function getUserId($username) 
        {
            $userId = null;

            //$stmt = mysqli_stmt_init($this->dbConn) or die(mysqli_error($this->dbConn));
            $stmt = mysqli_stmt_init($this->dbConn) or die($this->error);
            
            $sql = "SELECT `id` FROM `users` WHERE `username` = ?";

            if(mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $username);

                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);

                    if($result) {
                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            if($row['id'] && $row['id'] > 0) {
                                $userId = $row['id'];
                            }
                        }
                        mysqli_free_result($result);
                    }
                }
            }
            mysqli_stmt_close($stmt);

            return $userId;
        }


        // get hashed password
        public function getHash($username)
        {
            $hash = null;

            //$stmt = mysqli_stmt_init($this->dbConn) or die(mysqli_error($this->dbConn));
            $stmt = mysqli_stmt_init($this->dbConn) or die($this->error);

            $sql = "SELECT `password` FROM `users` WHERE `username` = ?";

            if(mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, 's', $username);
   
                if(mysqli_stmt_execute($stmt)) {    // returns true on success or false on failure
                    $result = mysqli_stmt_get_result($stmt);

                    if($result) {
                        $hash = mysqli_fetch_array($result, MYSQLI_ASSOC)['password'];
                        //echo "<br>db hash: ".$hash;

                        // free memory associated with result
                        mysqli_free_result($result);
                    }
                }
            }
            mysqli_stmt_close($stmt);

            return $hash;
        }


        // get user data from database (as an array)
        public function getUserData($userId) 
        {
            $data = array();
            $userId = (int)$userId;

            /* resource: http://php.net/manual/en/function.func-num-args.php */
            $func_num_args = func_num_args();   // returns the number of arguments passed to this function
            
            /* resource: http://php.net/manual/en/function.func-get-args.php */
            $func_get_args = func_get_args();   // returns an array comprising the function's argument list
           
            if($func_num_args > 1) {

                unset($func_get_args[0]);       // unset session id to create fieldset from $data array
                //print_r($func_get_args);      // returns now array without session id
                
                // generate fields string for query usage
                $fields = "`" . implode("`, `", $func_get_args) . "`";    // where: ` is a backtick
                
                //testing
                //echo $fields;                 // returns: `id`, `username`, `password`, `email` 
                //die();

                $sql = "SELECT $fields FROM `users` WHERE `id` = ?";
                $stmt = mysqli_stmt_init($this->dbConn) or die(mysqli_error($this->dbConn));

                if(mysqli_stmt_prepare($stmt, $sql)) {
                    mysqli_stmt_bind_param($stmt, 's', $userId);

                    // execute the prepared statement
                    if(mysqli_stmt_execute($stmt)) {    // returns true on success or false on failure

                        // gets a result set from a prepared statement
                        $result = mysqli_stmt_get_result($stmt);

                        if($result) {
                            $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

                            // free memory associated with result
                            mysqli_free_result($result);
                        }
                    }
                }

                // close the statement
                mysqli_stmt_close($stmt);
            }
            return $data;
        }


        // register the user - insert user data to database
        public function registerNewUser($username, $password, $email) 
        {
            /*
            // old working version of code

            $date = date("Y-m-d");
            
            $sql = "INSERT INTO users(`username`, `password`, `email`, `join_date`) VALUES('$username', '$password', '$email', '$date')";
            $result = mysqli_query($this->dbConn, $sql) or die(mysqli_error($this->dbConn));
            
            return $result;
            */

            // initialize a statement object suitable for mysqli_stmt_prepare()
            //$stmt = mysqli_stmt_init($this->dbConn) or die(mysqli_error($this->dbConn));
            $stmt = mysqli_stmt_init($this->dbConn) or die($this->error);

            // define the sql query
            $sql = "INSERT INTO users(`username`, `password`, `email`, `join_date`) VALUES(?, ?, ?, ?)";

            $affectedRows = -1;

            // prepare an SQL statement for execution
            if(mysqli_stmt_prepare($stmt, $sql)) {
                // bind parameters for markers
                mysqli_stmt_bind_param($stmt, 'ssss', $username, $password, $email, $date);

                // set current date
                $date = date("Y-m-d");

                // execute previously prepared statement
                if(mysqli_stmt_execute($stmt)) {    // returns true on success or false on failure

                    $affectedRows = mysqli_stmt_affected_rows($stmt);   // it returns 1 if successfully inserted to db, 0 if not, -1 if query returns error
                    //printf("%d Row inserted.\n", $affectedRows);
                }
            }

            // close the statement
            mysqli_stmt_close($stmt);

            return $affectedRows;
        }
    }
?>
