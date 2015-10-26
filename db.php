<?php
/**
 * AntenneBergstrasse API Adapater
 * Basic database manager
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */

//Load settings
require_once("settings.php");

/**
 * Create a database conneciton object to the specified database
 *
 * @param $db_name string identifier of the database in settings object
 * @return mysqli mysqli connection object for the specified database
 */
function db_connect($db_name)
{
    global $databases;
    if(isset($databases[$db_name]) && is_array($databases[$db_name]))
    {
        // Create connection
        $conn = new mysqli(SERVERNAME, $databases[$db_name]["user"], $databases[$db_name]["password"], $databases[$db_name]["name"]);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed (Database error): " . $conn->connect_error);
        }
        return $conn;
    }
    else
        die("Connection failed - database does not exist");
}

?>
