### 1.PUT 创建和更新
```
PUT /{index}/{type}/{id}
{
  "field": "value",
  ...
}
```
_id 必须自己指定id

注意ES7.0以上 不允许指定type 统一用_doc
### .POST 创建和更新 _id 由系统生成
```
POST /website/blog/
{
  "title": "My second blog entry",
  "text":  "Still trying this out...",
  "date":  "2014/01/01"
}
```
### 3.删除文档 DELETE
DELETE /website/blog/123

### 4.分析字段分词命令
```
POST '/index/_analyze?pretty' -H 'Content-Type: application/json' -d 
'{"field": "title","text":"我爱中国","analyzer":"ik_max_word"}'
```

### 5.查看字段mapping
```
curl -XGET 'host/index/_mapping?pretty'
```

### 6.删除索引

```
curl–XDELETE localhost:9200/index1
```