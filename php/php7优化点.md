1. JIT与性能
Just In Time（即时编译）是一种软件优化技术，指在运行时才会去编译字节码为机器码。
2. Zval的改变
部分变量由struct变成union
3. PHP数组的变化（HashTable和Zend Array）
    Zend Array的变化：
    （1） 数组的value默认为zval。
    
    （2） HashTable的大小从72下降到56字节，减少22%。
    
    （3） Buckets的大小从72下降到32字节，减少50%。
    
    （4） 数组元素的Buckets的内存空间是一同分配的。
    
    （5） 数组元素的key（Bucket.key）指向zend_string。
    
    （6） 数组元素的value被嵌入到Bucket中。
    
    （7） 降低CPU Cache Miss。