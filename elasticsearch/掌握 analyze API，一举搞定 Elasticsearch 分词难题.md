### 初次接触 Elasticsearch 的同学经常会遇到分词相关的难题，比如如下这些场景：

+ 为什么命名有包含搜索关键词的文档，但结果里面就没有相关文档呢？
+ 我存进去的文档到底被分成哪些词(term)了？
+ 我得自定义分词规则，但感觉好麻烦呢，无从下手

**如果你遇到过类似的问题，希望本文可以解决你的疑惑。**
#### 1. 上手
让我们从一个实例出发，如下创建一个文档：
```
PUT test/doc/1
{
  "msg":"Eating an apple a day keeps doctor away"
}
```
然后我们做一个查询，我们试图通过搜索 eat这个关键词来搜索这个文档
```
POST test/_search
{
  "query":{
    "match":{
      "msg":"eat"
    }
  }
}
```
ES的返回结果为0。这不太对啊，我们用最基本的字符串查找也应该能匹配到上面新建的文档才对啊！
各位不要急，我们先来看看什么是分词。
#### 2. 分词
搜索引擎的核心是倒排索引（这里不展开讲），而倒排索引的基础就是分词。所谓分词可以简单理解为将一个完整的句子切割为一个个单词的过程。在 es 中单词对应英文为 term。我们简单看个例子：

ES 的倒排索引即是根据分词后的单词创建，即 我、爱、北京、天安门这4个单词。这也意味着你在搜索的时候也只能搜索这4个单词才能命中该文档。
实际上 ES 的分词不仅仅发生在文档创建的时候，也发生在搜索的时候。

读时分词发生在用户查询时，ES 会即时地对用户输入的关键词进行分词，分词结果只存在内存中，当查询结束时，分词结果也会随即消失。而写时分词发生在文档写入时，ES 会对文档进行分词后，将结果存入倒排索引，该部分最终会以文件的形式存储于磁盘上，不会因查询结束或者 ES 重启而丢失。
ES 中处理分词的部分被称作分词器，英文是Analyzer，它决定了分词的规则。ES 自带了很多默认的分词器，比如Standard、 Keyword、Whitespace等等，默认是 Standard。当我们在读时或者写时分词时可以指定要使用的分词器。
#### 3. 写时分词结果
回到上手阶段，我们来看下写入的文档最终分词结果是什么。通过如下 api 可以查看：
```
POST test/_analyze
{
  "field": "msg",
  "text": "Eating an apple a day keeps doctor away"
}
```
其中 test为索引名，_analyze 为查看分词结果的 endpoint，请求体中 field 为要查看的字段名，text为具体值。该 api 的作用就是请告诉我在 test 索引使用 msg 字段存储一段文本时，es 会如何分词。
返回结果如下:
```
{
  "tokens": [
    {
      "token": "eating",
      "start_offset": 0,
      "end_offset": 6,
      "type": "<ALPHANUM>",
      "position": 0
    },
    {
      "token": "an",
      "start_offset": 7,
      "end_offset": 9,
      "type": "<ALPHANUM>",
      "position": 1
    },
    {
      "token": "apple",
      "start_offset": 10,
      "end_offset": 15,
      "type": "<ALPHANUM>",
      "position": 2
    },
    {
      "token": "a",
      "start_offset": 16,
      "end_offset": 17,
      "type": "<ALPHANUM>",
      "position": 3
    },
    {
      "token": "day",
      "start_offset": 18,
      "end_offset": 21,
      "type": "<ALPHANUM>",
      "position": 4
    },
    {
      "token": "keeps",
      "start_offset": 22,
      "end_offset": 27,
      "type": "<ALPHANUM>",
      "position": 5
    },
    {
      "token": "doctor",
      "start_offset": 28,
      "end_offset": 34,
      "type": "<ALPHANUM>",
      "position": 6
    },
    {
      "token": "away",
      "start_offset": 35,
      "end_offset": 39,
      "type": "<ALPHANUM>",
      "position": 7
    }
  ]
}
```
返回结果中的每一个 token即为分词后的每一个单词，我们可以看到这里是没有 eat 这个单词的，这也解释了在上手中我们搜索 eat 没有结果的情况。如果你去搜索 eating ，会有结果返回。
写时分词器需要在 mapping 中指定，而且一经指定就不能再修改，若要修改必须新建索引。如下所示我们新建一个名为ms_english 的字段，指定其分词器为 english：
```
PUT test/_mapping/doc
{
  "properties": {
    "msg_english":{
      "type":"text",
      "analyzer": "english"
    }
  }
}
```
#### 4. 读时分词结果
由于读时分词器默认与写时分词器默认保持一致，拿 上手 中的例子，你搜索 msg 字段，那么读时分词器为 Standard ，搜索 msg_english 时分词器则为 english。这种默认设定也是非常容易理解的，读写采用一致的分词器，才能尽最大可能保证分词的结果是可以匹配的。
然后 ES 允许读时分词器单独设置，如下所示：
```
POST test/_search
  {
    "query":{
      "match":{
        "msg":{
          "query": "eating",
          "analyzer": "english"
        }
      }
    }
  }
```
如上 analyzer 字段即可以自定义读时分词器，一般来讲不需要特别指定读时分词器。
如果不单独设置分词器，那么读时分词器的验证方法与写时一致；如果是自定义分词器，那么可以使用如下的 api 来自行验证结果。
```
POST _analyze
  {
    "text":"eating",
    "analyzer":"english"
  }
```
返回结果如下：
```
{
  "tokens": [
    {
      "token": "eat",
      "start_offset": 0,
      "end_offset": 6,
      "type": "<ALPHANUM>",
      "position": 0
    }
  ]
}
```
由上可知 english分词器会将 eating处理为 eat，大家可以再测试下默认的 standard分词器，它没有做任何处理。
#### 5. 解释问题
现在我们再来看下 上手 中所遇问题的解决思路。

查看文档写时分词结果
查看查询关键词的读时分词结果
匹对两者是否有命中

#### 6. 解决需求
由于 eating只是 eat的一个变形，我们依然希望输入 eat时可以匹配包含 eating的文档，那么该如何解决呢？
答案很简单，既然原因是在分词结果不匹配，那么我们就换一个分词器呗~ 我们可以先试下 ES 自带的 english分词器，如下：
###### 增加字段 msg_english，与 msg 做对比
```
PUT test/_mapping/doc
{
  "properties": {
    "msg_english":{
      "type":"text",
      "analyzer": "english"
    }
  }
}
```
# 写入相同文档
```
PUT test/doc/1
{
  "msg":"Eating an apple a day keeps doctor away",
  "msg_english":"Eating an apple a day keeps doctor away"
}
```
# 搜索 msg_english 字段
```
POST test/_search
{
  "query": {
    "match": {
      "msg_english": "eat"
    }
  }
}
```
执行上面的内容，我们会发现结果有内容了，原因也很简单
english分词器会将 eating分词为 eat，此时我们搜索 eat或者 eating肯定都可以匹配对应的文档了。至此，需求解决。
7. 深入分析
最后我们来看下为什么english分词器可以解决我们遇到的问题。一个分词器由三部分组成：char filter、tokenizer 和 token filter。各部分的作用我们这里就不展开了，我们来看下 standard和english分词器的区别。

english分词器在 Token Filter 中和 Standard不同，而发挥主要作用的就是 stemmer，感兴趣的同学可以自行去看起它的作用。
#### 8. 自定义分词
如果我们不使用 english分词器，自定义一个分词器来实现上述需求也是完全可行的，这里不详细讲解了，只给大家讲一个快速验证自定义分词器效果的方法，如下：
```
POST _analyze
{
  "char_filter": [], 
  "tokenizer": "standard",
  "filter": [
    "stop",
    "lowercase",
    "stemmer"
  ],
  "text": "Eating an apple a day keeps doctor away"
}
```
通过上面的 api 你可以快速验证自己要定制的分词器，当达到自己需求后，再将这一部分配置加入索引的配置。
至此，我们再看开篇的三个问题，相信你已经心里有答案了，赶紧上手去自行测试下吧！

### 参考
[掌握 analyze API，一举搞定 Elasticsearch 分词难题](https://www.jianshu.com/p/40e33c84693d)