<?php

/**
 * From http://web.stanford.edu/dept/its/communications/webservices/wiki/index.php/How_to_perform_error_handling_in_PHP
 */
if (defined('DEBUG')) {
// Report all PHP errors
    ini_set('error_reporting', E_ALL);

// Set the display_errors directive to On
    ini_set('display_errors', 1);
} else {

// Report simple running errors
    ini_set('error_reporting', E_ALL ^ E_NOTICE);

// Set the display_errors directive to Off
    ini_set('display_errors', 0);

// Log errors to the web server's error log
//ini_set('log_errors', 1);
// Destinations
    define("ADMIN_EMAIL", "fratrotta@gmail.com");
    define("LOG_FILE", "errors.log");

// Destination types
    define("DEST_EMAIL", "1");
    define("DEST_LOGFILE", "3");

    /**
     * my_error_handler($errno, $errstr, $errfile, $errline)
     *
     * Author(s): thanosb, ddonahue
     * Date: May 11, 2008
     * 
     * custom error handler
     *
     * Parameters:
     *  $errno:   Error level
     *  $errstr:  Error message
     *  $errfile: File in which the error was raised
     *  $errline: Line at which the error occurred
     */
    function my_error_handler($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
            case E_USER_ERROR:
                // Send an e-mail to the administrator
                error_log(date('Y-m-d H:i:s') . " Error: $errstr \n Fatal error on line $errline in file $errfile \n", DEST_EMAIL, ADMIN_EMAIL);

                // Write the error to our log file
                error_log(date('Y-m-d H:i:s') . " Error: $errstr \n Fatal error on line $errline in file $errfile \n", DEST_LOGFILE, LOG_FILE);
                break;

            case E_USER_WARNING:
                // Write the error to our log file
                error_log(date('Y-m-d H:i:s') . " Warning: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
                break;

            case E_USER_NOTICE:
                // Write the error to our log file
                error_log(date('Y-m-d H:i:s') . " Notice: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
                break;

            default:
                // Write the error to our log file
                error_log(date('Y-m-d H:i:s') . " Unknown error [#$errno]: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
                error_log(date('Y-m-d H:i:s') . " Unknown error [#$errno]: $errstr \n in $errfile on line $errline \n", DEST_LOGFILE, LOG_FILE);
                break;
        }

        // Don't execute PHP's internal error handler
        return TRUE;
    }

// Use set_error_handler() to tell PHP to use our method
    $old_error_handler = set_error_handler("my_error_handler");
}
