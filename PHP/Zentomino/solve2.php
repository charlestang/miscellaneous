<?php

require dirname(__FILE__) . '/classes/ZenToken.php';
require dirname(__FILE__) . '/classes/ZenPiece.php';
require dirname(__FILE__) . '/classes/ZenMap.php';
require dirname(__FILE__) . '/functions.php';
// <editor-fold defaultstate="collapsed" desc="显示HTML头信息">
$html_head = <<< HTML
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
        * {line-height:10px}
        div {margin:0;pedding:0}
        </style>
    </head>
    <body>
HTML;
echo $html_head;
// </editor-fold>
?>
<p><h1>Zentomino 求解程序</h1></p>
<p>装入问题：</p>
<?php

$map = load_map(dirname(__FILE__) . '/data/map11x9q3.csv');
$map->show();
ln('图形面积总共：', $map->size());
$piece_list = load_pieces(dirname(__FILE__) . '/data/zenpiece.csv');
$total_scale = 1.0;
ln('装入牌：');
foreach ($piece_list as $piece) {
    $piece->get(0)->show();
    $piece->possible($map, $map->size() == 60);
    $total_scale = $total_scale * $piece->position_count;
    ln('可能的位置数量：', $piece->position_count);
    ln('==========END============');
}
ln('问题总规模：', round($total_scale / 1000000 / 100000000, 2), '亿M');

ln('排序所有用到的牌⋯⋯');

sort_piece_list($piece_list);

ln('排序后，可能的位置数顺序为：');
foreach ($piece_list as $p) {
    echo $p->position_count, '&nbsp;&nbsp;';
}
ln(' ');

$area_cut_count = 0;
$solved = false;
$tot = 0;
/**
 *
 * @global type $map
 * @global type $piece_list
 * @global boolean $solved
 * @global int $area_cut_count
 * @param type $target_map
 * @param type $idx
 * @param type $result
 * @return type 
 */
function solve($target_map, $idx, $result) {
    global $tot, $map, $piece_list, $solved, $area_cut_count;
    if ($solved) { //如果问题已经有一组解了
        return;
    }
    if ($idx >= count($piece_list)) { //如果所有的牌已经用完了
        return;
    }

    $piece = $piece_list[$idx];

    foreach ($piece->possible_positions as $pos) {
        if ($pos[0] == -1) {
            $result[] = array($idx, -1, -1, -1);
            solve($target_map, $idx + 1, $result);
            array_pop($result);
            continue;
        }

        $token = $piece->get($pos[0]);
        $new_map = $target_map->put($pos[1], $pos[2], $token);
        if ($new_map == false) {
            continue;
        }
        if (count($result) > 1 && $new_map->check2() === false) {
            $area_cut_count++;
            if ($area_cut_count % 10000 == 0) {
                error_log('Progress: ' . $area_cut_count);
            }
            continue;
        } else {
            $result[] = array($idx, $pos[1], $pos[2], $pos[0]);
            if ($map->finish($new_map)) {
                ln('=====恭喜！计算完成！=====');
                $new_map->show();
                $solved = true;
                foreach ($result as $r) {
                    if ($r[3] == -1) {
                        
                    } else {
                        ln('Id: ', $r[0], ' X: ', $r[1], ' Y: ', $r[2]);
                        $piece_list[$r[0]]->get($r[3])->show();
                        ln('==end==');
                    }
                }
            } else {
                solve($new_map, $idx + 1, $result);
                array_pop($result);
            }
        }
    }

}

$start = getmicrotime();
solve($map, 0, array());
$end = getmicrotime();

ln('Total time used: ', $end - $start);
ln('Area cut count: ', $area_cut_count);

// <editor-fold defaultstate="collapsed" desc="显示HTML尾部信息">
$html_foot = <<< HTML
    </body>
</html>
HTML;
echo $html_foot;
// </editor-fold>



