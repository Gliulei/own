#### ES多索引

这种是multi-fields 对一个field设置多种索引

```
"productID": {
    "type": "text", //默认的type类型
    "fields": {
        "keyword": { // 字段名称 可以是任意你想要的单词 比如 raw
            "type": "keyword", //keyword 类型 不分词 用于聚合或排序
            "ignore_above": 256
        }
    }
}

查询 
{
  "query":{
    "term": {
      "productID": "haha" // 分词
    }
  }
}

{
  "query":{
    "term": {
      "productID.keyword": "haha" // 不分词
    }
  }
}

```


####  参考文档
(官方文档)[https://www.elastic.co/guide/en/elasticsearch/reference/current/multi-fields.html]
(elasticsearch.cn)[https://elasticsearch.cn/question/3869]