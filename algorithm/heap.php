<?php

/**
 * @author liu.lei_1206 <liu.lei_1206@immomo.com>
 * @since  2019-01-08
 */
class Heap {

    /**
     * 交换元素的位置
     *
     * @param $arr
     * @param $i
     * @param $j
     */
    private function swap(&$arr, $i, $j) {
        $temp    = $arr[$i];
        $arr[$i] = $arr[$j];
        $arr[$j] = $temp;
    }

    /**
     * 最大堆调整
     *
     * @param array $arr
     * @param       $i
     * @return array
     */
    public function maxHeapAdjust(&$arr = [], $i) {

        $parent      = $i;
        $left_child  = 2 * $i + 1;
        $right_child = 2 * $i + 2;

        $child = $left_child;
        //如果左子树不存在
        if (!isset($arr[$left_child])) {
            return $arr;
        }
        if (isset($arr[$right_child])) {
            if ($arr[$left_child] < $arr[$right_child]) {
                //$child = $right_child;
                $this->swap($arr, $left_child, $right_child);
            }
        }

        if ($arr[$parent] < $arr[$child]) {
            $this->swap($arr, $parent, $child);
            if (isset($arr[$right_child])) {
                if ($arr[$left_child] < $arr[$right_child]) {
                    //$child = $right_child;
                    $this->swap($arr, $left_child, $right_child);
                }
            }
        }
        //递归调整左子树
        $this->maxHeapAdjust($arr, $left_child);
        //递归调整右子树
        $this->maxHeapAdjust($arr, $right_child);

        return $arr;
    }

    public function maxHeapSet($arr, $i) {
        while ($i >= 0) {
            //file_put_contents('./tmp.txt', 'main' . $i . PHP_EOL, FILE_APPEND);
            $this->maxHeapAdjust($arr, $i);
            $i--;
        }

        return $arr;
    }
}

$heap   = new Heap();
$arr    = [53, 17, 78, 9, 45, 65, 87, 23];
$length = count($arr);
$index  = ceil(($length - 2)) / 2;
$return = $heap->maxHeapSet($arr, $index);

print_r($return);
