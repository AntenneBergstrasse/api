<?php
/**
 * AntenneBergstrasse API Adapater
 * Weeks: Get a list of broadcasting weeks
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1
 */

header('Content-Type: application/json;charset=UTF-8');

require_once("db.php");

$conn = db_connect("schedule");
$res = $conn->query("SELECT `broadcastingweek`.id, day, month, `date`.year FROM `broadcastingweek`, `date` WHERE `broadcastingweek`.id = date.broadcastingweek_id ORDER BY `date`.id ASC;");

if(!$res)
{
    echo "[]";
}
else
{
    $weeks = Array();
    $current_week = null;
    $last_id = 0;

    //Loop over all results:
    while($row = $res->fetch_assoc())
    {
        $datestring = sprintf("%d.%d.%d", $row['day'], $row['month'], $row['year']);
        $id = $row['id'];

        //Is the current entry the beginning of a new broadcasting week?
        if($id != $last_id)
        {
            //Then store the last one...
            if(is_array($current_week))
                $weeks[] = $current_week;

            //...and create a new entry
            $current_week = Array(
                "id"=>$id,
                "start"=>$datestring,
                "ende"=>$datestring
            );
            $last_id = $id;
        }
        //The entry is not the beginning of a new broadcasting week?
        else
        {
            //Then it is at least one more day of the current week, thus the end has to be shifted
            $current_week["ende"] = $datestring;
        }
    }

    //Store the last week, too...
    $weeks[] = $current_week;

    //...and output all weeks as json string
    echo json_encode($weeks);
}
$conn->close();

?>
