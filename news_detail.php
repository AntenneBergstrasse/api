<?php
/**
 * AntenneBergstrasse API Adapater
 * News Detail: Get a single news entry based on a given id
 *
 * Usage: news_detail.php?id=123
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.2
 */

header('Content-Type: application/json;charset=UTF-8');

if(!isset($_GET["id"]) || $_GET["id"]=="")
{
    echo "[]";
}
else
{
    require_once("db.php");

    $conn = db_connect("app");

    //Get news article from cache
    $res = $conn->query(sprintf("SELECT * FROM `news_cache` WHERE `id`=%d", $_GET["id"]));

    if(!$res || !($row = $res->fetch_assoc()))
    {
        http_response_code(404);
        echo '{"message":"News article with this id not found"}';
    }
    else
    {
        http_response_code(200);
        $news_entry = Array(
            "id" => $row["id"],
            "headline" => utf8_encode($row["headline"]),
            "teaser" => utf8_encode($row["teaser"]),
            "text" => utf8_encode($row["text"]),
            "datum" => $row["date"],
            "images" => Array(
                Array("id"=>$row["id"], "url"=>$row["image_url"])
            )
        );
        echo json_encode($news_entry);
    }
}

?>
