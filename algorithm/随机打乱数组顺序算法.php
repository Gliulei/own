<?php
/**
 * Created by PhpStorm.
 * User: liulei
 * Date: 19/6/28
 * Time: 下午9:14
 */
//循环随机位交换法
$arr = [1, 3, 5, 7, 10];
function _shuffle($arr)
{
    $length = count($arr) - 1;
    while ($length > 1) {
        $index = mt_rand(0, $length - 1);
        $tmp = $arr[$length];
        $arr[$length] = $arr[$index];
        $arr[$index] = $tmp;
        $length--;
    }
    return $arr;
}
var_dump(_shuffle($arr));