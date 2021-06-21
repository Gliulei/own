**安装elasticearch环境**

1.需要java环境

2.官网下载对应系统jdk 安装就可

3.下载elastisearch
进入到bin目录 -d 表示后台运行
./elasticsearch -d

4.curl -XGET http://localhost:9200/
<pre>
{
  "name" : "rskCqGL",
  "cluster_name" : "elasticsearch",
  "cluster_uuid" : "OEFB8NRlSHa1oxayZ4H5XA",
  "version" : {
    "number" : "5.5.0",
    "build_hash" : "260387d",
    "build_date" : "2017-06-30T23:16:05.735Z",
    "build_snapshot" : false,
    "lucene_version" : "6.6.0"
  },
  "tagline" : "You Know, for Search"
}
</pre>
看到json结果集 安装成功

【中文文档 】
https://www.elastic.co/guide/cn/elasticsearch/guide/current/index.html