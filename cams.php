<?php
/**
 * AntenneBergstrasse API Adapater
 * Cams: Get a list of available webcams
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */

header('Content-Type: application/json;charset=UTF-8');

require_once("settings.php");

if(!isset($cams_active) || !$cams_active)
    echo "[]";
else
    echo json_encode($cams);

?>
