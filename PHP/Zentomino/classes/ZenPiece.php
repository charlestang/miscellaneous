<?php
class ZenPiece {
    /**
     * @var array
     */
    public $piece_list = array();
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

    public function show() {
        foreach($this->piece_list as $token) {
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
