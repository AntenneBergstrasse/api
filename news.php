<?php
/**
 * AntenneBergstrasse API Adapater
 * News: Get news overview
 *
 * Usage: news.php?page=1 (optional)
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.2
 */

/*
 * Setting: How many news items to return at once
 */
define("NEWS_PER_PAGE", 8);

header('Content-Type: application/json;charset=UTF-8');

require_once("db.php");

//Get id of page to load - default is 0, while the app starts with 1, thus this value has to be decremented by one
$page = isset($_GET["page"]) ? (intval($_GET["page"], 10)-1) : 0;
$start =  $page * NEWS_PER_PAGE;
$end = $start + NEWS_PER_PAGE;

$conn = db_connect("app");
$res = $conn->query(sprintf("SELECT * FROM news_cache  ORDER BY date DESC LIMIT %d,%d;", $start, $end));

if(!$res)
{
    echo "[]";
}
else
{
    $news = Array();

    //Loop over all results:
    while($row = $res->fetch_assoc())
    {
        $news[] = Array(
            "id" => $row["id"],
            "headline" => utf8_encode($row["headline"]),
            "teaser" => utf8_encode($row["teaser"]),
            "text" => utf8_encode($row["text"]),
            "datum" => $row["date"],
            "images" => Array(
                Array("id"=>$row["id"], "url"=>$row["image_url"])
            )
        );
    }

    //Return array of news encoded as json
    echo json_encode($news);
}

?>

