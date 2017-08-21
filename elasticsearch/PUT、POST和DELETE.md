**1.PUT 创建和更新**
```
PUT /{index}/{type}/{id}
{
  "field": "value",
  ...
}
```
_id 必须自己指定id

**2.POST 创建和更新 _id 由系统生成**
```
POST /website/blog/
{
  "title": "My second blog entry",
  "text":  "Still trying this out...",
  "date":  "2014/01/01"
}
```
**3.删除文档 DELETE**
DELETE /website/blog/123