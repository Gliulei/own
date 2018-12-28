前言

TCP协议是一个基础知识，理解TCP协议，了解客户端TCP状态的变化对于编码，排查系统问题有很大帮助。

参考链接:
https://www.cnblogs.com/Jessy/p/3535612.html
https://www.kancloud.cn/wizardforcel/linux-c-book/134964

三次握手：
是指建立一个TCP连接时，需要客户端和服务器总共发送3个包

1.第一次握手：服务器调用socket()、bind()、listen()完成初始化后，调用accept()阻塞等待，处于监听端口的状态，客户端调用socket()初始化后，调用connect()发出SYN段告诉服务器我要建立连接你准备好没，此时客户端进入SYN_SEND状态，等待服务器确认
2.第二次握手：服务器收到SYN包应答一个SYN-ACK段，告诉客户端我准备好了你连接吧，服务器进入SYN_RECV状态。
3.第三次握手：客户端收到SYN＋ACK包后从connect()返回，同时应答一个ACK段，告诉服务器我要传送数据了你准备下，服务器收到后会从accept()返回新的描述用来通信
此时客户端A和服务器B进入ESTABLISHED状态，完成三次握手。


数据传输的过程：
建立连接后，TCP协议提供全双工的通信服务，但是一般的客户端/服务器程序的流程是由客户端主动发起请求，服务器被动处理请求，一问一答的方式。因此，服务器从accept()返回后立刻调用read()，读socket就像读管道一样，如果没有数据到达就阻塞等待，这时客户端调用write()发送请求给服务器，服务器收到后从read()返回，对客户端的请求进行处理，在此期间客户端调用read()阻塞等待服务器的应答，服务器调用write()将处理结果发回给客户端，再次调用read()阻塞等待下一条请求，客户端收到后从read()返回，发送下一条请求，如此循环下去。


四次挥手：
Client调用close()函数，给Server发送FIN，请求关闭连接；Server收到FIN之后给Client返回确认ACK，同时关闭读通道（不清楚就去看一下shutdown和close的差别），也就是说现在不能再从这个连接上读取东西，现在read返回0。此时Server的TCP状态转化为CLOSE_WAIT状态。
Client收到对自己的FIN确认后，关闭 写通道，不再向连接中写入任何数据。
接下来Server调用close()来关闭连接，给Client发送FIN，Client收到后给Server回复ACK确认，同时Client关闭读通道，进入TIME_WAIT状态。
Server接收到Client对自己的FIN的确认ACK，关闭写通道，TCP连接转化为CLOSED，也就是关闭连接。
Client在TIME_WAIT状态下要等待最大数据段生存期的两倍，然后才进入CLOSED状态，TCP协议关闭连接过程彻底结束。

CLOSE_WAIT
发起TCP连接关闭的一方称为client，被动关闭的一方称为server。被动关闭的server收到FIN后，但未发出ACK的TCP状态是CLOSE_WAIT。出现这种状况一般都是由于server端代码的问题，如果你的服务器上出现大量CLOSE_WAIT，应该要考虑检查代码。

TIME_WAIT
根据TCP协议定义的3次握手断开连接规定,发起socket主动关闭的一方 socket将进入TIME_WAIT状态。TIME_WAIT状态将持续2个MSL(Max Segment Lifetime),在Windows下默认为4分钟，即240秒。TIME_WAIT状态下的socket不能被回收使用. 具体现象是对于一个处理大量短连接的服务器,如果是由服务器主动关闭客户端的连接，将导致服务器端存在大量的处于TIME_WAIT状态的socket， 甚至比处于Established状态下的socket多的多,严重影响服务器的处理能力，甚至耗尽可用的socket，停止服务。

