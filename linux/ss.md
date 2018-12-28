前言
ss命令用于显示socket状态,可以当做 netstat 的替代品，更快，更好用,
它是一个非常实用、快速、有效的跟踪IP连接和sockets的新工具。
很多流行的Linux发行版都支持ss以及很多监控工具使用ss命令.熟悉这个工具有助于您更好的发现与解决系统性能问题。

**常用命令**
显示sockets简要信息,列出当前已经连接，关闭，等待的tcp连接
ss -s

看一下当前有多少redis连接（端口号为标识）
ss dport = :6379 | wc -l

查看本地端口监听情况
ss sport = :80

打印进程名和进程号
ss -ltp

列出处在 time-wait 状态的 IPv4 套接字
ss -t4 state time-wait

ss列出所有http连接中的连接
ss -o state established '( dport = :http or sport = :http )'
以上包含对外提供的80，以及访问外部的80
用以上命令完美的替代netstat获取http并发连接数，监控中常用到


ss命令帮助
ss --help
Usage: ss [ OPTIONS ]
       ss [ OPTIONS ] [ FILTER ]
   -h, --help       this message
   -V, --version    output version information
   -n, --numeric    don't resolve service names
   -r, --resolve       resolve host names
   -a, --all        display all sockets
   -l, --listening  display listening sockets
   -o, --options       show timer information
   -e, --extended      show detailed socket information
   -m, --memory        show socket memory usage
   -p, --processes  show process using socket
   -i, --info       show internal TCP information
   -s, --summary    show socket usage summary

   -4, --ipv4          display only IP version 4 sockets
   -6, --ipv6          display only IP version 6 sockets
   -0, --packet display PACKET sockets
   -t, --tcp        display only TCP sockets
   -u, --udp        display only UDP sockets
   -d, --dccp       display only DCCP sockets
   -w, --raw        display only RAW sockets
   -x, --unix       display only Unix domain sockets
   -f, --family=FAMILY display sockets of type FAMILY

   -A, --query=QUERY, --socket=QUERY
       QUERY := {all|inet|tcp|udp|raw|unix|packet|netlink}[,QUERY]

   -D, --diag=FILE  Dump raw information about TCP sockets to FILE
   -F, --filter=FILE   read filter information from FILE
       FILTER := [ state TCP-STATE ] [ EXPRESSION ]