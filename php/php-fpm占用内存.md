php-fpm|php-cgi占用内存

在网上翻阅资料的时候会看到有人说一个php-cgi进程大约占用系统内存20M,
但是php-cgi占用内存其实是与你的php.ini配置加载多少个.so(.dll)模块相关的,
我们在linux系统下查看,可以用pman的命令:pmap$(pgrepphp-cgi|head-1)

pmap:
用法:pmap [参数] PID
工具是linux的工具,能够查看进程用了多少内存,还能分析内存用在上面环节,对于一些长期占用内存居高不下的程序可以分析其行为,命令简单,信息简洁