<?php
/**
 * @since  2019-04-23
 */

$key = 111111;
/* Connect to message queue */
if (($msg_queue = msg_get_queue($key)) === false) {
    die("msg_get_queue");
}
while (1) {
    //从消息队列中读取一条消息。
    msg_receive($msg_queue, 1, $message_type, 1024, $message1);
    var_dump($message1);
}