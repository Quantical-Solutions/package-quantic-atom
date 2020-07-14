<?php

if (define(ROOTDIR)) {
    
    if (!file_exists(ROOTDIR . '/atom')){
        $fp = fopen(ROOTDIR . '/atom', 'w+');
        $string = file_get_contents(dirname(__DIR__) . 'templates/Commands.php');
        fwrite($fp, $string);
        fclose($fp);
    }
}