#### phpstrace
因为strace只能追踪到系统调用信息，而拿不到php代码层的调用信息。phptrace扩展就是为了解决这个问题，phptrace包含两个功能：1. 打印当前PHP调用栈，2. 实时追踪PHP调用。这样就能更方便我们去查看到我们需要的信息。phptrace wiki➫

#### GDB
gdb是一个由GNU开源组织发布运行在UNIX/LINUX操作系统下功能强大的程序调试工具。使用gdb可以在程序运行时观察程序的内部结构和内存的使用情况，当程序dump时还可以通过gdb观察到程序dump前发生了什么。主要来说gdb具有以下2个功能：

跟踪和变更执行计算机程序
断点功能
因为php语言是c写的，那么使用gdb也就能很方便的去调试php代码。举例，我们通过gdb来调试一个简单的php程序index.php：

```
// 程序代码：
<?php
for ($i = 0; $i < 3; $i ++) {
    echo $i . PHP_EOL;
    if ($i == 2) {
        $j = $i + 1;
        var_dump($j);
    }
    sleep(1);
}
gdb开始调试：

[root@syyong home]$ sudo gdb php
(gdb)run index.php
...
0
1
2
int(3)
[Inferior 1 (process 577) exited normally]
```
注：如果mac下使用gdb时报：“...please check gdb is codesigned - see taskgated(8)...”时可参考https://leandre.cn/search/gdb/➫。gdb在调试程序时，如果ulimit打开则会把错误信息打印到当前目录下的core.*文件中。ulimit -c如果为0则表示没打开，可以执行ulimit -c unlimited或者ulimit -c 大于0的数字。

常用命令：
* p：print，打印C变量的值
* c：continue，继续运行被中止的程序
* b：breakpoint，设置断点，可以按照函数名设置，如b zif_php_function，也可以按照源代码的行数指定断点，如b src/networker/Server.c:1000
* t：thread，切换线程，如果进程拥有多个线程，可以使用t指令，切换到不同的线程
* ctrl + c：中断当前正在运行的程序，和c指令配合使用
* n：next，执行下一行，单步调试
* info threads：查看运行的所有线程
* l：list，查看源码，可以使用l 函数名 或者 l 行号
* bt：backtrace，查看运行时的函数调用栈。当程序出错后用于查看调用栈信息
* finish：完成当前函数
* f：frame，与bt配合使用，可以切换到函数调用栈的某一层
* r：run，运行程序
使用.gdbinit脚本：
除了在gdb shell里输入命令，也可以预先编写好脚本让gdb执行。当gdb启动的时候会在当前目录下查找“.gdbinit”文件并加载，作为gdb命令进行执行。这样就可以不用在命令行中做一些重复的事，比如设定多个断点等操作。另外在gdb运行时也可以通过执行“(gdb) source [-s] [-v] filename”来解释gdb命令脚本文件。一个.gdbinit文件例子：
```
file index.php
set args hello
b main
b foo
r
```
php源码中提供的一个.gdbinit示例➫

其他gdb常用命令可以参考：

http://linuxtools-rst.readthedocs.io/zh_CN/latest/tool/gdb.html➫
http://coolshell.cn/articles/3643.html➫
http://www.cnblogs.com/xuqiang/archive/2011/05/02/2034583.html
http://blog.csdn.net/21cnbao/article/details/7385161➫
https://sourceware.org/gdb/current/onlinedocs/gdb/➫
所有gdb命令索引➫
gdb 调试php：
gdb有3种使用方式：

跟踪正在运行的PHP程序，使用 “gdb -p 进程ID” 进行附加到进程上
运行并调试PHP程序，使用 “gdb php -> run server.php” 进行调试
当PHP程序发生coredump后使用gdb加载core内存镜像进行调试 gdb php core
php在解释执行过程中，zend引擎用executor_globals变量保存了执行过程中的各种数据，包括执行函数、文件、代码行等。zend虚拟机是使用C编写，gdb来打印PHP的调用栈时，实际是打印的虚拟机的执行信息。

使用zbacktrace更简单的调试：
php源代码中还提供了zbacktrace这样的方便的对gdb命令的封装的工具。zbacktrace是PHP源码包提供的一个gdb自定义指令，功能与bt指令类似，与bt不同的是zbacktrace看到的调用栈是PHP函数调用栈，而不是c函数。zbacktrace可以直接看到当前执行函数、文件名和行数，简化了直接使用gdb命令的很多步骤。在php-src➫的根目录中有一个.gdbinit文件，下载后再gdb shell中输入：

(gdb) source .gdbinit
(gdb) zbacktrace
##### 基于gdb的功能特点，我们可以使用gdb来排查比如这些问题：

1. 某个php进程占用cpu 100%问题
2. 出现core dump问题，比如“Segmentation fault”
3. php扩展出现错误
4. 死循环问题
一些使用gdb排查问题例子：
 * [更简单的重现PHP Core的调用栈](http://www.laruence.com/2011/12/02/2333.html/number-of-function-calls-2-3)
 * [更简单的重现PHP Core的调用栈](http://www.laruence.com/2010/09/27/1754.html)
 * [一个低概率的PHP Core dump](http://www.laruence.com/2008/12/31/647.html)