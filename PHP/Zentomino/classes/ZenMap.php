<?php

class ZenMap extends ZenToken {

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
                            && $map->shape[$r-1][0] == 2) {
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
                    if ($map->shape[$r][$c] == 1 && $map->shape[$r+1][$c] == 1
                            && $map->shape[$r][$c-1] == 2 && $map->shape[$r][$c+1] == 2
                            && $map->shape[$r-1][$c] == 2 && $map->shape[$r+2][$c] == 2
                            && $map->shape[$r+1][$c-1] == 2 && $map->shape[$r+1][$c+1] == 2
                    ) {
                        return false;
                    }

                }
            }
        }
        return true;
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