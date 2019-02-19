#### tcpdump

tcpdump
即dump the traffic on a network，是一个功能强大的，命令行的包分析器。它可以将网络中传送的数据包完全截获下来提供分析。它支持针对网络层、协议、主机、网络或端口的过滤，并提供and、or、not等逻辑语句来帮助去掉无用的信息。这样我们就能详细看到网络通信的过程，能帮助我们解决很多网络问题。比如可以通过tcpdump知道什么时候发起的3次握手什么时候发送FIN包，什么时候发送RST包。

官方man手册

命令格式为：
```
tcpdump [-aAbdDefhHIJKlLnNOpqRStuUvxX#] [ -B size ] [ -c count ]
           [ -C file_size ] [ -E algo:secret ] [ -F file ] [ -G seconds ]
           [ -i interface ] [ -j tstamptype ] [ -M secret ]
           [ -Q metadata-filter-expression ]
           [ -r file ] [ -s snaplen ] [ -T type ] [ --version ] [ -V file ]
           [ -w file ] [ -W filecount ] [ -y datalinktype ] [ -z command ]
           [ -Z user ] [ 表达式 ]
```