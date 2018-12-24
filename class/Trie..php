<?php

/**
 * Trie 树
 * 用于词频统计、字符串查询和模糊匹配（比如关键词的模糊匹配）、字符串排序等任务。
 * @author liu.lei_1206 <liu.lei_1206@immomo.com>
 * @since  2018-12-24
 */
class Trie {
    /**
     * node struct
     * node = array(
     * val->word
     * next->array(node)/null
     * depth->int
     * )
     */
    private $root    = [
        'depth' => 0,
        'next'  => [],
    ];
    private $matched = [];

    public function append($keyword) {
        $words = preg_split('/(?<!^)(?!$)/u', $keyword);
        array_push($words, '`');
        $this->insert($this->root, $words);
    }

    public function match($str) {
        $this->matched = [];
        $words         = preg_split('/(?<!^)(?!$)/u', $str);
        while (count($words) > 0) {
            $matched = [];
            $res     = $this->query($this->root, $words, $matched);
            if ($res) {
                $this->matched[] = implode('', $matched);
            }
            array_shift($words);
        }

        return $this->matched;
    }

    private function insert(&$node, $words) {
        if (empty($words)) {
            return;
        }
        $word = array_shift($words);
        if (isset($node['next'][$word])) {
            $this->insert($node['next'][$word], $words);
        } else {
            $tmp_node            = [
                'depth' => $node['depth'] + 1,
                'next'  => [],
            ];
            $node['next'][$word] = $tmp_node;
            $this->insert($node['next'][$word], $words);
        }
    }

    private function query($node, $words, &$matched) {
        $word = array_shift($words);
        if (isset($node['next'][$word])) {
            array_push($matched, $word);
            if (isset($node['next'][$word]['next']['`'])) {
                return true;
            }

            return $this->query($node['next'][$word], $words, $matched);
        } else {
            $matched = [];

            return false;
        }
    }
}

$keys = ['东方','科技','科学家','科学知识','思考','下载'];
$trie = new Trie();
foreach ($keys as $key) {
    $trie->append($key);
}

$word = '这位科学家很了不起！';
$match = $trie->match($word);
var_dump($match);
