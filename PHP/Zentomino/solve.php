<?php

require dirname(__FILE__) . '/classes/ZenToken.php';
require dirname(__FILE__) . '/classes/ZenPiece.php';
require dirname(__FILE__) . '/classes/ZenMap.php';
require dirname(__FILE__) . '/functions.php';


$piece_list = load_pieces(dirname(__FILE__) . '/data/zenpiece.csv');

foreach ($piece_list as $piece) {
    echo 'Status count: ', $piece->length(), '<br/>';
    $piece->get(0)->show();
}

$map = load_map(dirname(__FILE__) . '/data/map8.csv');
$map->show();

$solved = false;
$cut_count = 0;

/**
 * @param ZenMap $map
 * @param array $piece_list
 * @param array $status
 * @param int $idx
 */
function solve($target_map, $idx, $result) {
    global $map, $piece_list, $solved, $cut_count;
    if ($solved)
        return;
    if ($idx >= count($piece_list)) {
        return;
    }

    $piece = $piece_list[$idx];
    for ($s = -1, $length = $piece->length(); $s < $length; $s++) {
        if ($s == -1) {
            $result[] = array($idx, -1, -1, -1);
            solve($target_map, $idx + 1, $result);
            array_pop($result);
            continue;
        }

        $token = $piece->get($s);
        for ($x = 0; $x <= $map->width - $token->width; $x++) {
            for ($y = 0; $y <= $map->height - $token->height; $y++) {
                $new_map = $target_map->put($x, $y, $token);
                if ($map->check($new_map) == false) {
                    $cut_count++;
                    continue;
                } else {
                    if ($map->finish($new_map)) {
                        echo '=======final map======<br/>';
                        $new_map->show();
                        $solved = true;
                        $result[] = array($idx, $x, $y, $s);
                        echo '=======solve======<br/>';
                        foreach ($result as $r) {
                            echo 'idx: ', $r[0], '<br/>';
                            echo 'pos x: ', $r[1], '<br/>';
                            echo 'pos y: ', $r[2], '<br/>';
                            if ($r[3] == -1) {
                                echo 'not use;<br/>';
                            } else {
                                $piece_list[$r[0]]->get($r[3])->show();
                            }
                        }
                        echo '######end#######<br/>';
                        return;
                    } else {
                        $result[] = array($idx, $x, $y, $s);
                        solve($new_map, $idx + 1, $result);
                        array_pop($result);
                    }
                }
            }
        }
    }
}

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

$start = getmicrotime();
solve($map, 0, array());
$end = getmicrotime();
echo 'Total time: ', $end - $start, '<br/>';
echo 'Cut count: ', $cut_count, '<br/>';