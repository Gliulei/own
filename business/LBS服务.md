一、LBS是什么
LBS(Location Based Services)是基于地理位置的服务,我们常见的有附近的商家,附近的人。我们就拿附近的商家来举例看看它是怎么搞的。


二、方案一 mongodb地理位置空间索引(不是此文重点简单描述下)
#### 数据采集
商家发布时插入数据
```
db.places.insert(
   {
      loc : { type: "Point", coordinates: [ -73.97, 40.77 ] },
      name: "Central Park",
      category : "Parks"
   }
)
```
#### 创建索引 注意是`2dsphere` 不是1和-1
db.places.ensureIndex( { loc : "2dsphere" } )

#### 查询服务
使用$near来查询附近的地点。
```
db.places.find( { loc :
                        { $near :
                          { $geometry :
                             { type : "Point" ,
                               coordinates : [ <longitude> , <latitude> ] } ,
                            $maxDistance : <distance in meters>
                     } } } )
```


三、方案二 Geohash
##### 1. geohash是什么
+ geohash算法是一种地址编码，它能把二维的经纬度编码成一维的字符串，每个字符串代表一个矩形区域。
+ 每个矩形区域内共享相同的Geohash字符串
+ 字符串越长，表示的精度越高。5位的编码能表示10平方千米范围的矩形区域，而6位编码能表示更精细的区域（约0.34平方千米）
+ 字符串相似的表示距离相近，这样可以利用字符串前缀匹配来查询附近的POI信息。

##### 2、实现方案
1.数据采集
+ 商家发布，通过经纬度，算出geohash值存入mysql数据库

2.查询服务
+ 在查询附近商家的时候，利用SQL中 like 'wx4g0e%' 进行查询
+ 如果查询量比较大，可以加一层cache

3.存在的问题
+ 查询出来的结果，如果要根据距离大小进行排序 结果比较多就GG了，目前想不出怎么优化排序
