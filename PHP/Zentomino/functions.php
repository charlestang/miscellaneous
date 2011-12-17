<?php

function load_pieces($filename){
    $piece_list = array();
    $lines = file($filename);
    $idx = 0;
    for ($i = 0; $i < 12; $i ++) {
        list($width, $height) = explode(',', trim($lines[$idx++]), 2);
        $shape = array();
        for ($row = 0; $row < $height; $row++) {
            $shape[] = explode(',', trim($lines[$idx++]), $width);
        }
        $piece_list[] = new ZenPiece(intval($width), intval($height), $shape);
    }
    return $piece_list;
}