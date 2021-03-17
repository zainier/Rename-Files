<?php

require_once "functions.php";

$dir_path = $argv[1] ?? "";

if ($dir_path != "") {
    try {
        foreach (getListOfFiles($dir_path) as $file) {
            echo basename($file) . PHP_EOL;
            renameWithDateAndTime($file);
        }
    } catch (Exception $exception) {
        echo $exception->getMessage();
    }
} else {
    echo "Please, enter the path to folder";
}


