<?php

/**
 * 首先，我们有一个固定容量的桶，桶里存放着令牌（token）。桶一开始是空的，token以 一个固定的速率r往桶里填充，
 * 直到达到桶的容量，多余的令牌将会被丢弃。每当一个请求过来时，就会尝试从桶里移除一个令牌，
 * 如果没有令牌的话，请求无法通 过。
 *
 * @since  2019-01-01
 */
class TokenBucket {

    private $timeStamp;
    public  $capacity = 1000;// 桶的容量
    public  $rate     = 100; // 令牌放入的速度QPS
    public  $tokens   = 100;// 当前令牌的数量

    private $redis;


    public function __construct($capacity, $rate) {
        /* Tokens is the total tokens in the bucket. fill_rate is the
		 * rate in tokens/second that the bucket will be refilled. */
        $this->capacity  = $capacity;
        $this->rate      = $rate;
        $this->timeStamp = time();
    }

    /**
     * 获取桶中的令牌数
     *
     * @return mixed
     * @author liu.lei
     */
    private function getTokens() {
        return $this->redis->get('tokens');
    }

    /**
     * 以一定速率生成令牌
     *
     * @return mixed
     * @author liu.lei
     */
    private function createTokens() {
        $now          = time();
        $this->tokens = $this->tokens + ($now - $this->timeStamp) * $this->rate;// 先生成令牌
        $tokens       = min($this->capacity, $this->tokens);

        return $this->redis->set('tokens', $tokens);
    }

    private function decrTokens() {
        return $this->redis->decr('tokens');
    }

    public function consume($tokens) {
        /* Consume tokens from the bucket. Returns True if there were
         * sufficient tokens, otherwise False. */
        if ($tokens <= $this->getTokens()) {
            $this->decrTokens();

            return true;
        }

        return false;
    }
}

$bucket = new TokenBucket(1000, 10);
$bucket->consume(1);