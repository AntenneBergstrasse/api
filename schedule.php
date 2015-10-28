<?php
/**
 * AntenneBergstrasse API Adapater
 * Schedule: Get the schedule for a given day
 *
 * Usage: schedule.php?date=YYYY-MM-DD
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */


/**
 * Create a unix timestamp based on a basetime representing a day and a string
 * giving an offset to the beginning of the day
 *
 * @param $timestring string string representing a time using HHMM
 * @param $basedate DateTime base time object for the current day
 * @return mixed int timestamp based on given basedate and offset
 */
function convertTimeToTimestamp($timestring, $basedate)
{
    $timestamp = $basedate->getTimestamp();

    $hours = substr($timestring, 0, 2);
    $minutes = substr($timestring, 2, 2);

    return $timestamp + $hours * 3600 + $minutes * 60;
}


header('Content-Type: application/json;charset=UTF-8');

require_once("db.php");

$conn = db_connect("schedule");

//Get current date_id based on given datestring
$date_array = explode("-", $_GET["day"]);
$res = $conn->query(sprintf("SELECT id FROM `date` WHERE `day` = %d AND `month` = %d AND `year` = %d ORDER BY `id` DESC;", $date_array[2], $date_array[1], $date_array[0]));

if(!$res || !($row = $res->fetch_assoc()))
{
    echo '{"message":"Malformed Day"}';
}
else
{
    $date_id = $row['id'];
    $date = new DateTime(sprintf("%d-%d-%d 00:00:00", $date_array[0], $date_array[1], $date_array[2]));

    $res = $conn->query(sprintf("SELECT `show`.`id`, `rail`.`name` AS railname, `show`.`name` AS showname, `rail`.`description` AS raildescription, `show`.`description` AS showdescription, time_start, time_end FROM `show`, `rail` WHERE `date_id` = %d AND `rail`.`id` = rail_id ORDER BY `rail`.`time_start` ASC", $date_id));

    if(!$res)
    {
        echo '{"message":"No program found"}';
    }

    $schedule = Array();

    //Loop over all results:
    while($row = $res->fetch_assoc())
    {
        //Create an entry for every result...
        $schedule_item = Array(
            "id" => $row["id"],
            "subject" => utf8_encode($row["showname"]),
            "description" => utf8_encode($row["showdescription"]),
            "start" => convertTimeToTimestamp($row["time_start"], $date),
            "end" => convertTimeToTimestamp($row["time_end"], $date),
            "redaktionen" => Array("name"=> utf8_encode(html_entity_decode($row["railname"])), "description"=>utf8_encode($row["raildescription"]))
        );

        //...store it...
        $schedule[] = $schedule_item;
    }

    //...and output the whole schedule as json
    echo json_encode($schedule);
}

$conn->close();

?>
