PHP uniqid()函数可用于生成不重复的唯一标识符，该函数基于微秒级当前时间戳。在高并发或者间隔时长极短（如循环代码）的情况下，会出现大量重复数据。即使使用了第二个参数，也会重复，最好的方案是结合md5函数来生成唯一ID。
PHP uniqid() 生成不重复唯一标识方法一
这种方法会产生大量的重复数据，运行如下PHP代码会数组索引是产生的唯一标识，对应的元素值是该唯一标识重复的次数。
```
<?php
        $units = array();
        for($i=0;$i<1000000;$i++){
                $units[] = uniqid();
        }
        $values  = array_count_values($units);
        $duplicates = [];
        foreach($values as $k=>$v){
                if($v>1){
                        $duplicates[$k]=$v;
                }
        }
        echo '<pre>';
        print_r($duplicates);
        echo '</pre>';
?>
```

PHP uniqid() 生成不重复唯一标识方法二
这种方法生成的唯一标识重复量明显减少。
```
<?php
        $units = array();
        for($i=0;$i<1000000;$i++){
                $units[] = uniqid('',true);
        }
        $values  = array_count_values($units);
        $duplicates = [];
        foreach($values as $k=>$v){
                if($v>1){
                        $duplicates[$k]=$v;
                }
        }
        echo '<pre>';
        print_r($duplicates);
        echo '</pre>';
?>
```

PHP uniqid() 生成不重复唯一标识方法三
这种方法生成的唯一标识中没有重复。
```
<?php
        $units = array();
        for($i=0;$i<1000000;$i++){
                $units[]=md5(uniqid(md5(microtime(true)),true));
        }
        $values  = array_count_values($units);
        $duplicates = [];
        foreach($values as $k=>$v){
                if($v>1){
                        $duplicates[$k]=$v;
                }
        }
        echo '<pre>';
        print_r($duplicates);
        echo '</pre>';
?>
```

PHP uniqid() 生成不重复唯一标识方法四
使用session_create_id()函数生成唯一标识符，经过实际测试发现，即使循环调用session_create_id()一亿次，都没有出现过重复。
php session_create_id()是php 7.1新增的函数，用来生成session id，低版本无法使用。