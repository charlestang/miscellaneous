<?php

class ZenToken {

    /**
     * @var int
     */
    public $height = 0;

    /**
     * @var int
     */
    public $width = 0;

    /**
     * @var array
     */
    public $shape = array();

    public function __construct($width, $height, $shape) {
        $this->height = $height;
        $this->width = $width;
        $this->shape = $shape;
    }

    /**
     * Rotate the token clockwise
     * @return ZenToken
     */
    public function rotate() {
        $new_width = $this->height;
        $new_height = $this->width;
        $new_shape = array();
        for ($row = 0; $row < $new_height; $row++) {
            $new_shape[$row] = array();
            for ($col = 0; $col < $new_width; $col++) {
                $new_shape[$row][$col] = $this->shape[$this->height - $col - 1][$row];
            }
        }
        return new ZenToken($new_width, $new_height, $new_shape);
    }

    /**
     * @return ZenToken
     */
    public function flip() {
        $new_width = $this->height;
        $new_height = $this->width;
        $new_shape = array();
        for ($row = 0; $row < $new_height; $row++) {
            for ($col = 0; $col < $new_width; $col++) {
                $new_shape[$row][$col] = $this->shape[$col][$row];
            }
        }
        $mirro = new ZenToken($new_width, $new_height, $new_shape);

        return $mirro->rotate();
    }

    /**
     * @param ZenToken $token 
     * @return boolean 
     */
    public function same($token) {
        if ($this->height != $token->height || $this->width != $token->width) {
            return false;
        }

        for ($h = 0; $h < $this->height; $h++) {
            for ($w = 0; $w < $this->width; $w++) {
                if ($this->shape[$h][$w] != $token->shape[$h][$w]) {
                    return false;
                }
            }
        }

        return true;
    }

    public function show() {
        echo '<p>Height: ', $this->height, ' Width: ', $this->width, '</p>', PHP_EOL;
        foreach ($this->shape as $row) {
            foreach ($row as $pos) {
                if ($pos == 0) {
                    echo '<div style="display:inline-block;height:15px;width:15px;background-color:#FFF;"></div>';
                } else {
                    echo '<div style="display:inline-block;height:15px;width:15px;background-color:#'.intval($pos).'FF;"></div>';
                }
            }
            echo '<br/>';
        }
    }

}
