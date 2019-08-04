## HTTP 请求头 Range

> 请求资源的部分内容（不包括响应头的大小），单位是byte，即字节，从0开始.
  
>  如果服务器能够正常响应的话，服务器会返回 206 Partial Content 的状态码及说明.
  
>  如果不能处理这种Range的话，就会返回整个资源以及响应状态码为 200 OK .（这个要注意，要分段下载时，要先判断这个）

## 参考文章
[Http 请求头 Range](https://www.cnblogs.com/1995hxt/p/5692050.html)