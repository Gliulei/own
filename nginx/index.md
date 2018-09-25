nginx 配置指令

**try_files指令** 

```
location ^~ /tmp/ {
   root /home/deploy/frontend;
   try_files $uri $uri/ /error#/404; 尝试跳转$uri/  没有则跳转/error#/404
}
```