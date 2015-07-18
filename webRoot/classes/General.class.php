<?php 
    
    /*
    * Author: Anieszka Pas
    * Assignment: TE2.0 Fundamentals of Software Development, Digital Skills Academy
    * Date: 2015/06/06
    *
    * Resources: 
    *    http://www.w3schools.com/php/php_filter.asp
    *    http://www.w3schools.com/php/php_ref_filter.asp
    *    http://www.sitepoint.com/regular-expressions
    *    http://php.net/manual/en/filter.examples.validation.php
    *    http://php.net/manual/en/function.password-hash.php
    *    http://php.net/manual/en/faq.passwords.php
    *
    */


    /*
      General.class
      - description:
        The class includes general functions. 

      - class methods:
        outputErrors( $errors, $title )         // returns errors as unordered list
        sanitizeString( $str )                  // returns sanitized and validated string
        sanitizeEmail( $email )                 // returns sanitized and validated email
        hashPassword( $password )               // returns hashed password (default algorithm is used)
        verifyPassword( $password, $hash )      // returns verified hashed password
    */

    
    class General 
    {
        // show errors as unordered list
        public function outputErrors($errors, $title) 
        {   
            return "<ul>$title<li>" . implode("</li><li>", $errors) . "</li></ul>";
        }


        // sanitize and validate specified string
        public function sanitizeString($str) 
        {
            // remove all HTML tags from a string
            $str = filter_var($str, FILTER_SANITIZE_STRING);    // resource: http://www.w3schools.com/php/php_ref_filter.asp
            
            // validate string value against a Perl-compatible regular expression
            $str = filter_var($str, FILTER_VALIDATE_REGEXP, 
                array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")));     // resource: http://www.sitepoint.com/regular-expressions
            
            return $str;
        }


        // sanitize and validate specified email
        public function sanitizeEmail($email) 
        {
            // remove all illegal characters from email
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);    // resource: http://www.w3schools.com/php/php_ref_filter.asp
            
            // check if it's a valid email address
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);    // resource: http://php.net/manual/en/filter.examples.validation.php

            return $email;
        }


        // hash the password using the current default algorithm
        public function hashPassword($password)
        {
            /*
            recources: 
            http://php.net/manual/en/function.password-hash.php
            http://php.net/manual/en/faq.passwords.php
            */

            return password_hash($password, PASSWORD_DEFAULT);
        }


        // verify the password
        public function verifyPassword($password, $hash) 
        {
            /* 
            resource: 
            http://php.net/manual/en/function.password-verify.php 
            */

            return password_verify($password, $hash);
        }

    }
?>
