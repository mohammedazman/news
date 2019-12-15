<?php

# live search for the the js dynamic script

require_once 'db.class.php';

$db = new DB();
$term = $_REQUEST['query'];
if(isset($term)) {

    $sql = $db->query("SELECT title FROM news WHERE text LIKE '%{$term}%' OR title LIKE '%{$term}%'");
    $liveResult = '';
    $size = count($sql);
    $thisIsCounter = -1;
    while ($thisIsCounter <= $size - 2) {
        $thisIsCounter++;
        $liveResult .= '<p>' . $sql[$thisIsCounter]['title'] . '</p>';
    }
    echo $liveResult;
}