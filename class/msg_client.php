<?php
/**
 * 消息队列是消息的链接表 存触在内核中 最大消息队列数受系统控制
 * @author liu.lei_1206 <liu.lei_1206@immomo.com>
 * @since  2019-04-23
 */

/* Connect to message queue */
if (($msg_queue = msg_get_queue(353141087, 0666)) === false) {
    die("msg_get_queue");
}

//往消息队列ID发送数据
msg_send($msg_queue, 1, "Hello world" . time());