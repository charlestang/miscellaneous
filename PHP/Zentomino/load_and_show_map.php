<?php

require dirname(__FILE__) . '/classes/ZenToken.php';
require dirname(__FILE__) . '/classes/ZenPiece.php';
require dirname(__FILE__) . '/classes/ZenMap.php';
require dirname(__FILE__) . '/functions.php';

$map = load_map(dirname(__FILE__) . '/data/map1.csv');
$map->show();

$piece_list = load_pieces(dirname(__FILE__) . '/data/zenpiece.csv');
$piece = array_pop($piece_list);
$piece->get(0)->show();


$position_count = 0;
$cut_count = 0;
$hit_count = 0;
$area_check = 0;


for ($s = 0, $length = $piece->length(); $s < $length; $s++) {
    $token = $piece->get($s);
    for ($x = 0; $x <= $map->width - $token->width; $x++) {
        for ($y = 0; $y <= $map->height - $token->height; $y++) {
            $new_map = $map->put($x, $y, $token);
            if (false === $new_map) {
                $cut_count++;
                continue;
            }
            if ($map->check($new_map, $impossible_list) == false) {
                $hit_count++;
                $cut_count++;
                continue;
            }
            if ($new_map->check2() == false) {
                $area_check++;
                $cut_count++;
                $new_map->show();
                continue;
            }

            $position_count++;
        }
    }
}

echo 'Total posible position: ', $position_count, '<br/>', PHP_EOL;
echo 'Total put cut: ', $cut_count, '<br/>', PHP_EOL;
echo 'Total check cut: ', $hit_count, '<br/>', PHP_EOL;
echo 'Total check2 cut: ', $area_check, '<br/>', PHP_EOL;