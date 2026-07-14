<?php
$tables = DB::select('SHOW TABLES');
foreach($tables as $t) {
    $tName = array_values((array)$t)[0];
    echo "\n--- Table: $tName ---\n";
    $cols = DB::select("SHOW COLUMNS FROM $tName");
    foreach($cols as $c) {
        echo "$c->Field - $c->Type\n";
    }
}
