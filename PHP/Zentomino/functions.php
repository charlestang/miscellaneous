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

function load_map($filename) {
    $map = array();
    $lines = file($filename);
    list($width, $height) = explode(',', $lines[0], 2);
    $idx = 1;
    for ($row = 0; $row < $height; $row++) {
        $map[] = explode(',', $lines[$idx++], $width);
    }
    return new ZenMap(intval($width), intval($height), $map);
}