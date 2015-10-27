<?php
/**
 * AntenneBergstrasse API Adapater
 * Streams: Get a list of available streams
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */

require_once("settings.php");

if(!isset($streams_active) || !$streams_active)
    echo "[]";
else
    echo json_encode($streams);

?>
