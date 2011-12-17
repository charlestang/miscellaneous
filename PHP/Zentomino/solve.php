<?php
require dirname(__FILE__) . '/classes/ZenToken.php';
require dirname(__FILE__) . '/classes/ZenPiece.php';
require dirname(__FILE__) . '/classes/ZenMap.php';
require dirname(__FILE__) . '/functions.php';


$piece_list = load_pieces(dirname(__FILE__) . '/data/zenpiece.csv');

foreach ($piece_list as $piece) {
    $piece->show();
}