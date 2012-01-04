<?php

class ZenPiece {

    /**
     * @var array
     */
    public $piece_list = array();

    /**
     * @var array 
     */
    public $possible_positions = array();

    public $position_count = 0;

    public function __construct($width, $height, $shape) {
        $token = new ZenToken($width, $height, $shape);
        $this->append($token);
        $rotate = $token;
        for ($r = 1; $r < 4; $r++) {
            $rotate = $rotate->rotate();
            $this->append($rotate);
        }
        $flip = $token->flip();
        $this->append($flip);
        for ($r = 1; $r < 4; $r++) {
            $flip = $flip->rotate();
            $this->append($flip);
        }
    }

    /**
     * @param ZenMap $map 
     * @param boolean $must_use 是否必须使用
     */
    public function possible($map, $must_use = true) {
        if (!$must_use) {
            $this->possible_positions[] = array(-1, 0, 0);
            $this->position_count++;
        }
        for ($s = 0, $length = $this->length(); $s < $length; $s++) {
            $token = $this->get($s);
            for ($x = 0; $x <= $map->width - $token->width; $x++) {
                for ($y = 0; $y <= $map->height - $token->height; $y++) {
                    $new_map = $map->put($x, $y, $token);
                    if (false === $new_map) {
                        continue;
                    }
                    if ($new_map->check2() == false) {
                        continue;
                    }
                    $this->possible_positions[] = array($s, $x, $y);
                    $this->position_count++;
                }
            }
        }
    }

    public function show() {
        foreach ($this->piece_list as $token) {
            $token->show();
        }
    }

    private function append($token) {
        foreach ($this->piece_list as $t) {
            if ($t->same($token)) {
                return;
            }
        }
        $this->piece_list[] = $token;
    }

    /**
     * @return int
     */
    public function length() {
        return count($this->piece_list);
    }

    /**
     * @param int $index
     * @return ZenToken
     */
    public function get($index) {
        return $this->piece_list[$index];
    }

}
