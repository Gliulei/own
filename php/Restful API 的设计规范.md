####前言
一个良好的API设计规范对我们代码的可维护性,可读性是很重要的,特别是大型团队,显得更加重要。
**一、URL**
RESTful 的核心思想就是，客户端发出的数据操作指令都是"动词 + 宾语"的结构。
比如，GET /zoos这个命令，GET是动词，/zoos是宾语。

**URL规范**
    不用大写；
    用中杠-不用下杠_；
    参数列表要encode；
    URI中的名词表示资源集合，使用复数形式。

**资源集合 vs 单个资源**
URL表示资源的两种方式：资源集合、单个资源。
资源集合：
    /zoos //所有动物园
    /zoos/1/animals //id为1的动物园中的所有动物

单个资源：
    /zoos/1 //id为1的动物园
    /zoos/1;2;3 //id为1，2，3的动物园

避免层级过深的URL
    过深的导航容易导致url膨胀，不易维护，如 GET /zoos/1/areas/3/animals/4，尽量使用查询参数代替路径中的实体导航，如GET /animals?zoo=1&area=3
    
####二、Request
**HTTP方法**
通过标准HTTP方法对资源CRUD,通常是5种：
GET：读取（Read）
POST：新建（Create）
PUT：更新（Update）
PATCH：更新（Update），通常是部分更新
DELETE：删除（Delete）

**安全性和幂等性**

安全性：不会改变资源状态，可以理解为只读的；
幂等性：执行1次和执行N次，对资源状态改变的效果是等价的。
.    安全性 幂等性
GET    √    √
POSt   x    ×
PUT    x    √
DELETE ×    √
安全性和幂等性均不保证反复请求能拿到相同的response。以 DELETE 为例，第一次DELETE返回200表示删除成功，第二次返回404提示资源不存在，这是允许的。

####三、Response
1.不要发生了错误但给2xx响应，客户端可能会缓存成功的http请求；
2.正确设置http状态码，状态码必须精确；
HTTP 状态码就是一个三位数，分成五个类别。
    1xx：相关信息
    2xx：操作成功
    3xx：重定向
    4xx：客户端错误
    5xx：服务器错误
常用response状态码总结
    GET: 200 OK
    POST: 201 Created
    PUT: 200 OK
    PATCH: 200 OK
    DELETE: 204 No Content
上面代码中，POST返回201状态码，表示生成了新的资源；DELETE返回204状态码，表示资源已经不存在。

此外，202 Accepted状态码表示服务器已经收到请求，但还未进行处理，会在未来再处理，通常用于异步操作。

**4xx状态码**
4xx状态码表示客户端错误，主要有下面几种。

400 Bad Request：可用作一般性错误返回。

401 Unauthorized：用户未提供身份验证凭据，或者没有通过身份验证。

403 Forbidden：用户通过了身份验证，但是不具有访问资源所需的权限。

404 Not Found：所请求的资源不存在，或不可用。

405 Method Not Allowed：用户已经通过身份验证，但是所用的 HTTP 方法不在他的权限之内。

410 Gone：所请求的资源已从这个地址转移，不再可用。

415 Unsupported Media Type：客户端要求的返回格式不支持。比如，API 只能返回 JSON 格式，但是客户端要求返回 XML 格式。

422 Unprocessable Entity ：客户端上传的附件无法处理，导致请求失败。

429 Too Many Requests：客户端的请求次数超过限额。

**5xx状态码**
5xx状态码表示服务端错误。一般来说，API 不会向用户透露服务器的详细信息，所以只要两个状态码就够了。
500 Internal Server Error：客户端请求有效，服务器处理时发生了意外。
503 Service Unavailable：服务器无法处理请求，一般用于网站维护状态。
    