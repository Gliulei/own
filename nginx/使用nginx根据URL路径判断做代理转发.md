## 使用nginx根据URL路径判断做代理转发

### 需求
**根据URL对不同的访问需求转发到后端服务器**
#### 功能描述
1. www.a.com/buyer    转发到192.168.3.164
2. www.a.com/user        转发到192.168.3.167 192.168.3.168
#### 环境
1. www.a.com域名指向192.168.3.166
2. 192.168.3.164处理www.a.com/buyer的请求
3. 192.168.3.167 192.168.3.168处理www.a.com/user的请求
#### 解决
使用proxy_pass和upstream 命令处理代理转发
域名指向www.a.com 192.168.3.166
3.166的nginx配置需要添加一下内容
在/etc/nginx/conf.d/中创建新的配置文件，a.conf
基本配置不变
添加以下配置
在server前添加两个upstream
```
upstream user {
        server 192.168.3.167;
        server 192.168.3.168
        }
upstream buyer {
        server 192.168.3.164;
        }
```
upstream段中可添加多个server用以实现负载均衡，也可以用weight来改变权重

在server段中添加location用以指向上文的两个upstream

```
        location /buyer {
        #代理出错error、超时timeout情况下转至下一服务器
                proxy_next_upstream error timeout;
        #超时时间15s。默认60s
                proxy_connect_timeout 15s；
                proxy_pass http://buyer;
        }
        location /user {
                proxy_next_upstream error timeout;
                proxy_connect_timeout 15s；
                proxy_pass http://user;
        }
 ```