<?php
/**
 * AntenneBergstrasse API Adapater
 * Song: Get currently or recently played song
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */

require_once("db.php");

$conn = db_connect("music");

// Get current song from database
$res = $conn->query("SELECT artist, title FROM logging WHERE ((active = 1 AND DATE_SUB(CURDATE(),INTERVAL 15 MINUTE ) <= datetime)  OR duration > 60) AND (title != '' AND artist != '') ORDER BY datetime DESC LIMIT 0,1;");

if(!$res)
{
    echo "Kein Titel";
}
else
{
    $row = $res->fetch_assoc();
    echo $row['artist']." - ".$row['title'];
}
$conn->close();
?>
