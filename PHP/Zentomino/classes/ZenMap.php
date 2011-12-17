<?php
class ZenMap extends ZenToken{
    public function __construct($width, $height, $map) {
        $this->width = $width;
        $this->height = $height;
        $this->shape = $map;
    }

    /**
     * @return boolean
     */
    public function check($map){
        for($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                if ($map[$row][$col] > 2) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * @return boolean
     */
    public function finish($map) {
        for($row = 0; $row < $this->height; $row++) {
            for ($col = 0; $col < $this->width; $col++) {
                if ($this->shape[$row][$col] == 0 && $map[$row][$col] != 0 ) {
                    return false;
                }
                if ($this->shape[$row][$col] == 1 && $map[$row][$col] != 2) {
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
                $map[$y + $row][$x + $col] += $token->shape[$row][$col];
            }
        }
        return new ZenMap($this->width, $this->height, $map);
    }
}