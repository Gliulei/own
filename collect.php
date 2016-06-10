<?php
/**
 * Created by PhpStorm.
 * User: shenwang
 * Date: 16/3/13
 * Time: 下午6:34
 * 收藏的php功能
 */

//导出信息csv
function actionExport()
{
    $dataList = array('a'=>'b');

    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=" . time() . '.csv');
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    print(chr(0xEF) . chr(0xBB) . chr(0xBF));

    foreach ($dataList as $item) {
        echo $item;
    }
}