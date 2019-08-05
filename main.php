<?php

include("card.php");

$file=file_get_contents('AllSets.json');
$jsonDecoded=json_decode($file, true);

foreach($jsonDecoded as $set){
    $tmp = new Set($set);

    $tmp->SetToCsv("test.csv");
}

?>
