<?php

function load_pieces($filename) {
    $piece_list = array();
    $lines = file($filename);
    $idx = 0;
    for ($i = 0, $lc = count($lines); $i < 12; $i++) {
        if ($idx >= $lc) {
            continue;
        }
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

function ln() {
    $args = func_get_args();
    echo '<p>';
    foreach ($args as $a) {
        echo $a;
    }
    echo '</p>', PHP_EOL;
}

function sort_piece_list(&$piece_list) {
    uasort($piece_list, 'piece_compare');
}


/**
 * @param ZenPiece $p1
 * @param ZenPiece $p2 
 */
function piece_compare(&$p1, &$p2) {
    if ($p1->position_count == $p2->position_count) {
        return 0;
    }

    if ($p1->position_count < $p2->position_count) {
        return -1;
    } else {
        return 1;
    }
}


function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}