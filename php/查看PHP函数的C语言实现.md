#### 查看PHP函数的C语言实现:
+ cd php-src
+ 查找 grep -rn "PHP_FUNCTION(socket_accept)" ./ext
+ 返回 ./ext/sockets/sockets.c:938:PHP_FUNCTION(socket_accept)
+ 查找 grep -rn "PHP_FUNCTION(array_merge)" ./ext
+ 返回 ./ext/standard/array.c:2266:PHP_FUNCTION(array_merge)
+ 可以看出,PHP库函数的基本都在php-src/ext目录下,里面有具体函数库比如socket,一般的函数基本都在标准库standard.