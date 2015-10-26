<?php
/**
 * AntenneBergstrasse API Adapater
 * Global settings
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 * 
 * This holds all global settings
 * Insert individual data and rename to 'settings.php'
 */

define("SERVERNAME", "localhost");

$databases = Array();

//Add a chunk like this for every database
$databases["music"] = Array();
$databases["music"]["user"] = "user";
$databases["music"]["password"] = "password";
$databases["music"]["name"] = "database_name";

?>

