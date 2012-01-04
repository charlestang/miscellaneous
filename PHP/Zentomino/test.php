<?php
require dirname(__FILE__) . '/classes/ZenToken.php';
require dirname(__FILE__) . '/classes/ZenPiece.php';
require dirname(__FILE__) . '/classes/ZenMap.php';
require dirname(__FILE__) . '/functions.php';


$map = load_map(dirname(__FILE__) . '/data/map7x7q1.csv');
$map->show();

$scale = 1e-10;
$piece_list = load_pieces(dirname(__FILE__) . '/data/zenpiece.csv');
foreach ($piece_list as $piece) {
    $piece->possible($map);
    $scale *= $piece->position_count;
    echo "Position count:", $piece->position_count, '<br/>', PHP_EOL;
}

echo "Problem scale:", $scale, '<br/>', PHP_EOL;