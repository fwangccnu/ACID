<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function random_str($pre="",$length = 6){
    $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "j", "k", "m", "n", "p", "r", "s", "t", "u", "v", "w", "x", "y", "z", "2", "3", "4", "5", "6", "7", "8", "9");
    $keys = array_rand($chars, $length);
    $word = "";
    for ($i = 0; $i < $length; $i++) {
        $word .= $chars[$keys[$i]];
    }
    return $pre. rand(100, 999) . $word . substr(time(), -5);
}