<?php
/**
 * Created by PhpStorm.
 * User: liulei
 * Date: 19/7/18
 * Time: 下午10:10
 */

//二分查找需要数组有序,效率为O(logn)
//复制代码
//二分查找
function binarySearch(Array $arr, int $target)
{
    $mid = 0;
    $low = 0;
    $high = count($arr) - 1;

    while ($low <= $high) {
        $mid = floor(($low + $high) / 2);
        #找到元素
        if ($arr[$mid] == $target) {
            return $mid;
        }
        #中元素比目标大,查找左部
        if ($arr[$mid] > $target) {
            $high = $mid - 1;
        }
        #重元素比目标小,查找右部
        if ($arr[$mid] < $target) {
            $low = $mid + 1;
        }
    }

    #查找失败
    return $mid;
}

function binarySearchIn2DArray(array $arr, int $target, $col_start, $col_end, $row_start,$row_end) {

    //二分查找,找到中间的行数
    $mid = round(($col_start + $col_end) / 2);

    $result = binarySearch($arr[$mid], $target);

    if($arr[$mid][$result] == $target) {
        return true;
    }

    //对剩余的两部分分别进行递归查找
}


function find(array $arr, int $target)
{
    if (empty($arr) || empty($target)) {
        return false;
    }

    //取列数
    $col = count($arr) - 1;
    //取行数
    $row = count($arr[0]) - 1;



    return binarySearchIn2DArray($arr, $target, 0, $col, 0, $col, 0, $row);
}


$arr = [
    [1, 2, 3, 4],
    [3, 6, 9, 12],
    [5, 10, 15, 20]
];
