<?php
/**
 * AntenneBergstrasse API Adapater
 * Song: Get currently or recently played song
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */

header('Content-Type: application/json;charset=UTF-8');

require_once("db.php");

$conn = db_connect("music");

// Get current song from database
$res = $conn->query("SELECT artist, title FROM logging WHERE ((duration > 60 OR active = 1) AND (DATE_SUB(CURDATE(),INTERVAL 15 MINUTE ) <= datetime)) AND (title != '' AND artist != '') ORDER BY datetime DESC LIMIT 0,1;");

if(!$res || !($row = $res->fetch_assoc()))
{
    echo "[]";
}
else
{
    $song = Array("id"=>0, "title"=>utf8_encode($row['title']), "artist"=>utf8_encode($row['artist']), "cover"=>SONG_DEFAULT_COVER);
    echo json_encode($song);
}
$conn->close();

?>
