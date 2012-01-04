<?php

class ZenMap extends ZenToken {

    public $area_size = -1;

    public function size() {
        if ($this->area_size == -1) {
            $this->area_size = 0;
            foreach ($this->shape as $key => $row) {
                foreach ($row as $col) {
                    if ($col == 1)
                        $this->area_size++;
                }
            }
        }
        return $this->area_size;
    }

    public function __construct($width, $height, $map) {
        $this->width = $width;
        $this->height = $height;
        $this->shape = $map;
    }

    /**
     * @param ZenMap $map
     * @return boolean
     */
    public function check($map, $impossible = array()) {
        for ($r = 0; $r < $this->height; $r++) {
            for ($c = 0; $c < $this->width; $c++) {
                if ($r == 0 && $c == 0) {
                    if ($map->shape[0][0] == 1
                            && $map->shape[0][1] == 2
                            && $map->shape[1][0] == 2) {
                        return false;
                    }
                } elseif (($r == 0 && $c > 0) && ($c < $this->width - 1)) {
                    if ($map->shape[0][$c] == 1
                            && $map->shape[0][$c - 1] == 2
                            && $map->shape[0][$c + 1] == 2
                            && $map->shape[1][$c] == 2) {
                        return false;
                    }
                } elseif ($r == 0 && ($c == ($this->width - 1))) {
                    $w = $this->width - 1;
                    if ($map->shape[0][$w] == 1
                            && $map->shape[0][$w - 1] == 2
                            && $map->shape[1][$w] == 2) {
                        return false;
                    }
                } elseif ($c == 0 && $r > 0 && $r < $this->height - 1) {
                    if ($map->shape[$r][0] == 1
                            && $map->shape[$r - 1][0] == 2
                            && $map->shape[$r + 1][0] == 2
                            && $map->shape[$r][1] == 2) {
                        return false;
                    }
                } elseif ($c == 0 && $r == $this->height - 1) {
                    if ($map->shape[$r][0] == 1
                            && $map->shape[$r][1] == 2
                            && $map->shape[$r - 1][0] == 2) {
                        return false;
                    }
                } elseif ($r < $this->height - 1 && $c < $this->width - 1) {
                    if ($map->shape[$r][$c] == 1
                            && $map->shape[$r - 1][$c] == 2
                            && $map->shape[$r][$c - 1] == 2
                            && $map->shape[$r][$c + 1] == 2
                            && $map->shape[$r + 1][$c] == 2) {
                        return false;
                    }
                }

                if ($r > 0 && $r < $this->height - 1
                        && $c > 0 && $c < $this->width - 2) {
                    if ($map->shape[$r][$c] == 1 && $map->shape[$r][$c + 1] == 1
                            && $map->shape[$r][$c - 1] == 2 && $map->shape[$r][$c + 2] == 2
                            && $map->shape[$r - 1][$c] == 2 && $map->shape[$r - 1][$c + 1] == 2
                            && $map->shape[$r + 1][$c] == 2 && $map->shape[$r + 1][$c + 1] == 2
                    ) {
                        return false;
                    }
                }
                if ($r > 0 && $r < $this->height - 2
                        && $c > 0 && $c < $this->width - 1) {
                    if ($map->shape[$r][$c] == 1 && $map->shape[$r + 1][$c] == 1
                            && $map->shape[$r][$c - 1] == 2 && $map->shape[$r][$c + 1] == 2
                            && $map->shape[$r - 1][$c] == 2 && $map->shape[$r + 2][$c] == 2
                            && $map->shape[$r + 1][$c - 1] == 2 && $map->shape[$r + 1][$c + 1] == 2
                    ) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    private $bitmap = array();

    public function check2() {
        $this->bitmap = array();
        for ($r = 0; $r < $this->height; $r++) {
            for ($c = 0; $c < $this->width; $c++) {
                if (!isset($this->bitmap[$r])) {
                    $this->bitmap[$r] = array();
                }
                if (!isset($this->bitmap[$r][$c])) {
                    $this->bitmap[$r][$c] = 0;
                }
                if ($this->bitmap[$r][$c] == 0) {
                    $size = $this->area($r, $c);
                    if ($size % 5 != 0) {
                        //echo 'Pos, x:', $c, ' y:', $r, ' size:', $size, '<br/>', PHP_EOL;
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * 数出与给定坐标的小格连续的小格的面积
     * @param int $r
     * @param int $c 
     * @return int 面积值
     */
    public function area($r, $c) {
        if ($r < 0 || $c < 0 || $r >= $this->height || $c >= $this->width) { //越界了
            return 0;
        }
        if (!isset($this->bitmap[$r])) {
            $this->bitmap[$r] = array();
        }
        if (!isset($this->bitmap[$r][$c])) {
            $this->bitmap[$r][$c] = 0;
        }
        if ($this->bitmap[$r][$c] == 1) { //如果位图上有记号，表示已经遍历过了
            return 0;
        }
        $this->bitmap[$r][$c] = 1; //在位图上做一个记号
        if ($this->shape[$r][$c] == 0 || $this->shape[$r][$c] == 2) { //边界
            return 0;
        }
        return 1 + $this->area($r, $c - 1) + $this->area($r, $c + 1) + $this->area($r - 1, $c) + $this->area($r + 1, $c);
    }

    /**
     * @return boolean
     */
    public function finish($map) {
        for ($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                if ($this->shape[$row][$col] == 0 && $map->shape[$row][$col] != 0) {
                    return false;
                }
                if ($this->shape[$row][$col] == 1 && $map->shape[$row][$col] != 2) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * @param int $x
     * @param int $y
     * @param ZenToken $token 
     * @return mixed
     */
    public function put($x, $y, $token) {
        if ($x + $token->width > $this->width || $y + $token->height > $this->height) {
            return false;
        }
        $map = $this->shape;
        for ($row = 0; $row < $token->height; $row++) {
            for ($col = 0; $col < $token->width; $col++) {
                $origin = $map[$y + $row][$x + $col];
                $map[$y + $row][$x + $col] += $token->shape[$row][$col];
                if ($origin == 0 && $map[$y + $row][$x + $col] != 0) {
                    return false;
                }
                if ($map[$y + $row][$x + $col] > 2) {
                    return false;
                }
            }
        }
        return new ZenMap($this->width, $this->height, $map);
    }

}