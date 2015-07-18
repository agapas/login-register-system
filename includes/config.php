<?php
    /*
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/05/31
    *
    * config.php
    * It contains:
    * - the database login details 
    */

    // define global configuration variables
    define("DB_HOST", "localhost");         // the host
    define("DB_USER", "user13");            // the database user
    define("DB_PASS", "DwCCLpyXr7Pvf6Cs");  // the database password
    define("DB_NAME", "login_db");          // the database name

    // NOTE: 
    // don't need create manually the 'user13' with his privileges,
    // he is already added to 'login_db.sql' file (in the main app folder)
?>
