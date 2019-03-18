## php Session存储到Redis的方法

1. 修改php.ini配置
增加如下内容( [PHP]模块下)
extension=redis.so
修改以下两项([session]模块下)
```
session.save_handler = redis
session.save_path = "tcp://127.0.0.1:6379"
```
2. 重启php-fpm