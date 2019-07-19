<?php
/**
 * 二维数组中的查找
 * 在一个二维数组中（每个一维数组的长度相同），每一行都按照从左到右递增的顺序排序，
 * 每一列都按照从上到下递增的顺序排序。请完成一个函数，输入这样的一个二维数组和一个整数，
 * 判断数组中是否含有该整数。
 * Created by PhpStorm.
 * User: liulei
 * Date: 19/7/18
 * Time: 下午10:10
 */

/**
 * 二分查找
 *
 * @param array $arr
 * @param int   $target
 * @return float|int
 * @author liu.lei
 */
function binarySearch(Array $arr, int $target, int $low, int $high) {

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

    //返回小值
    return floor(($low + $high) / 2);
}

/**
 * 递归搜索
 *
 * @param array $arr
 * @param int   $target
 * @param int   $row_start
 * @param int   $row_end
 * @return bool
 * @author liu.lei
 */
function binarySearchIn2DArray(array $arr, int $target, int $row_start, int $row_end, int $col_start, int $col_end) {

    //查找失败
    if ($row_start > $row_end || $col_start > $col_end) {
        return false;
    }
    //二分查找,找到中间的行数
    $mid = round(($row_start + $row_end) / 2);

    //找到的值位于x行,result列
    $result = binarySearch($arr[$mid], $target, $col_start, $col_end);

    if ($arr[$mid][$result] == $target) {
        return true;
    }

    //对剩余的两部分分别进行递归查找
    return (binarySearchIn2DArray($arr, $target, $row_start, $mid - 1, $result + 1, $col_end) || binarySearchIn2DArray($arr, $target, $mid + 1, $row_end, $col_start, $result));
}


/**
 * 二维数组查找元素
 *
 * @param array $arr
 * @param int   $target
 * @return bool
 * @author liu.lei
 */
function find(array $arr, int $target) {
    if (empty($arr) || empty($target)) {
        return false;
    }

    //数组的行数
    $row = count($arr) - 1;
    //数组的列数
    $col = count($arr[0]) - 1;

    //如果目标值小于最小值或者目标值大于最大值,那肯定不存在
    if ($target < $arr[0][0] || $target > $arr[$row][$col]) {
        return false;
    }

    return binarySearchIn2DArray($arr, $target, 0, $row, 0, $col);
}


$arr = [
    [1, 2, 3, 4],
    [3, 6, 9, 12],
    [5, 10, 15, 20]
];

$a = find($arr, 10);
var_dump($a);
