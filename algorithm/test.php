<?php
/**
 * Created by PhpStorm.
 * User: liulei
 * Date: 16/9/21
 * Time: 下午11:50
 */
function binarySearch($arr = [],$target)
{
    $length = count($arr);
    $low = 0;
    $high = $length - 1;
    while ($low <= $high){
        $mid = floor(($low + $high) / 2);

        if($arr[$mid] == $target) return '元素的位置是:'.$mid;

        if($arr[$mid] > $target) $high = $mid - 1;

        if($arr[$mid] < $target) $low = $mid + 1;
    }

    return 'Not Found';

}

$arr = [1,3,5,7,9,10,15,18];
echo binarySearch($arr,9).PHP_EOL;
echo binarySearch($arr,18).PHP_EOL;
echo binarySearch($arr,14);
