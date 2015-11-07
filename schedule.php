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

    $res = $conn->query(sprintf("SELECT `show`.`id`, `rail`.`name` AS railname, `show`.`name` AS showname, `rail`.`description` AS raildescription, `show`.`description` AS `showdescription`, `time_start`, `time_end`, `e1`.`firstname` AS `e1firstname`, `e2`.`firstname` AS `e2firstname`, `e3`.`firstname` AS `e3firstname`, `e1`.`lastname` AS `e1lastname`, `e2`.`lastname` AS `e2lastname`, `e3`.`lastname` AS `e3lastname` FROM `show` LEFT JOIN `rail` ON `show`.`rail_id` = `rail`.`id` LEFT JOIN `show_team` ON `show`.`id` = `show_team`.`show_id` LEFT JOIN `team` AS `e1` ON `show_team`.`editor1_id`=`e1`.`id` LEFT JOIN `team` AS `e2` ON `show_team`.`editor2_id`=`e2`.`id` LEFT JOIN `team` AS `e3` ON `show_team`.`editor3_id`=`e3`.`id` WHERE `date_id` = %d ORDER BY `rail`.`time_start` ", $date_id));

    if(!$res)
    {
        echo '{"message":"No program found"}';
    }

    $schedule = Array();

    //Loop over all results:
    while($row = $res->fetch_assoc())
    {
        //Create detail information for this entry
        $detail = "";
        if($row["showname"] != "")
            $detail = "<b>".$row["showname"]."</b><br><br>";
        $detail .= $row["showdescription"] != "" ? $row["showdescription"] : $row["raildescription"];

        if($row["e3lastname"] !="")
            $detail .= sprintf("<br><br>Mit %s %s, %s %s und %s %s", $row["e1firstname"], $row["e1lastname"], $row["e2firstname"], $row["e2lastname"], $row["e3firstname"], $row["e3lastname"]);
        elseif($row["e2lastname"] !="")
            $detail .= sprintf("<br><br>Mit %s %s und %s %s", $row["e1firstname"], $row["e1lastname"], $row["e2firstname"], $row["e2lastname"]);
        elseif($row["e1lastname"] !="")
            $detail .= sprintf("<br><br>Mit %s %s", $row["e1firstname"], $row["e1lastname"]);

        //Create an entry for every result...
        $schedule_item = Array(
            "id" => $row["id"],
            "subject" => utf8_encode($detail),
            "start" => convertTimeToTimestamp($row["time_start"], $date),
            "end" => convertTimeToTimestamp($row["time_end"], $date),
            "redaktionen" => Array("name"=> utf8_encode(html_entity_decode($row["railname"])))
        );

        //...store it...
        $schedule[] = $schedule_item;
    }

    //...and output the whole schedule as json
    echo json_encode($schedule);
}

$conn->close();

?>
