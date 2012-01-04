<?php

/**
 * This is the data object to represent there is a 1 at that place.
 */
class dlxnode {

    /**
     * @var dlxnode left
     */
    public $l = null;

    /**
     * @var dlxnode right
     */
    public $r = null;

    /**
     * @var dlxnode up
     */
    public $u = null;

    /**
     * @var dlxnode down
     */
    public $d = null;

    /**
     * @var dlxnode column head
     */
    public $c = null;

}

/**
 * This is a special data object, which has two more field.
 */
class dlxhead extends dlxnode {

    /**
     * @var int size
     */
    public $s = 0;

    /**
     * @var string name
     */
    public $n = '';

}

/**
 * This is the matrix object
 */
class dlxmatrix {

    /**
     * @var dlxhead root
     */
    public $root = null;

}