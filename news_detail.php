<?php
/**
 * AntenneBergstrasse API Adapater
 * News Detail: Get a single news entry based on a given id
 *
 * Usage: news_detail.php?id=123
 *
 * @author Benjamin Haettasch (bhaettasch)
 * @version 0.1a (stub)
 */

header('Content-Type: application/json;charset=UTF-8');

$news_entry = Array(
    "id" => 123,
    "headline" => "Headline",
    "text" => "Text",
    "teaser" => "Teaser",
    "datum" => "1970-01-01 00:00:00",
    "images" => Array(
            Array("id"=>111, "url"=>"")
        )
    );

echo json_encode($news_entry);

?>
