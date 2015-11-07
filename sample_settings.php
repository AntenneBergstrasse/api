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


/*
 * ===============================================================================================
 * Database
 * ===============================================================================================
 */
define("SERVERNAME", "localhost");

$databases = Array();
//Add a chunk like this for every database
$databases["music"] = Array();
$databases["music"]["user"] = "user";
$databases["music"]["password"] = "password";
$databases["music"]["name"] = "database_name";


/*
 * ===============================================================================================
 * Cam
 * ===============================================================================================
 */

//Change visibility of all cams at once without the need to remove the settings
//To change the visibility individually, just (un)comment the corresponding line
$cams_active = true;

$cams = Array();
//Add a line like this for every cam
$cams[] = Array("id"=>1, "name"=>"Name", "url"=> "URL");


/*
 * ===============================================================================================
 * Streams
 * ===============================================================================================
 */

//Change visibility of all steams at once without the need to remove the settings
//To change the visibilit individually, just (un)comment the corresponding line
$streams_active = true;

$streams = Array();
//Add a line like this for every stream
$streams[] = Array("id"=>1, "name"=>"Name", "url"=> "URL", "format"=>"", "bitrate"=>"");

?>
