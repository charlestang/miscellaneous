<?php

class ZenMap extends ZenToken {

    public function __construct($width, $height, $map) {
        $this->width = $width;
        $this->height = $height;
        $this->shape = $map;
    }

    /**
     * @return boolean
     */
    public function check($map, $impossible = array()) {
        //if ($this->search_impossible($map, $impossible)) {
        //    return false;
        //}
        return true;
    }

    private function search_impossible($map, $impossible) {
        foreach ($impossible as $piece) {
            for ($i = 0; $i < $piece->length(); $i++) {
                $token = $piece->get($i);
                for ($x = 0; $x <= $map->width - $token->width; $x++) {
                    for ($y = 0; $y <= $map->height - $token->height; $y++) {
                        $counter = 0;
                        $match = true;
                        for ($row = 0; $row < $token->height && $match; $row++) {
                            for ($col = 0; $col < $token->width && $match; $col++) {
                                if ($token->shape[$row][$col] == -1) {
                                    $counter++;
                                    continue;
                                }
                                if ($token->shape[$row][$col] != $map->shape[$row + $y][$col + $x]) {
                                    $match = false;
                                    continue;
                                }
                                $counter++;
                            }
                        }
                        if ($counter == $token->width * $token->height) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
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