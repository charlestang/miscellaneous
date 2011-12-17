<?php

require dirname(__FILE__) . '/classes/ZenToken.php';
require dirname(__FILE__) . '/classes/ZenPiece.php';
require dirname(__FILE__) . '/classes/ZenMap.php';


$empty_token = new ZenToken(0, 0, array());
$empty_token->show();
$empty_token = $empty_token->rotate();
$empty_token->show();
$empty_token = $empty_token->flip();
$empty_token->show();

$sigle = new ZenToken(1,1,array(array(1)));
$sigle->show();
$sigle = $sigle->rotate();
$sigle->show();
$sigle = $sigle->flip();
$sigle->show();

$shape = array(
    array(1, 0, ),
    array(1, 0, ),
    array(1, 1, ),
    array(1, 0, )
);



$t = new ZenToken(2, 4, $shape);

$f = $t->flip();

$f->show();

$t->show();

$t1 = $t->rotate();

$t1-> show();

$t2 = $t1->rotate();

$t2->show();

$t3 = $t2->rotate();

$t3->show();

$t4 = $t3->rotate();

var_dump($t->same($t4));

echo '=========<br/>';

$shape = array(
    array(1,0,0),
    array(1,0,0),
    array(1,1,1),
);

$p = new ZenPiece(3,3,$shape);
$p->show();

exit();
echo '====map test===<br/>';
$m = new ZenMap(3, 3, $shape);
$m->show();
echo 'token pick:<br/>';
$x = $p->get(1);
$x->show();
$m = $m->put(0,0, $p->get(1));
$m->show();