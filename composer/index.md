### 中国全量镜像
https://www.phpcomposer.com/5-features-to-know-about-composer-php

### 阿里云 Composer 全量镜像
https://mirrors.aliyun.com/composer/index.html

```
{
    "require": {
        "nmred/kafka-php": "0.2.*" //例子
    },
    "config": {
        "vendor-dir": "vendor" //指定目录
    },
    "repositories": [
        {"type": "composer", "url": "www.baidu.com"},
        {"type": "composer", "url": "www.baidu.com"}
    ]
}
```

composer update nmred/kafka-php


##composer - github 以tag为 包版本

所以如果不提交tag的话  可能导致你的  包无法使用composer安装
http://www.mamicode.com/info-detail-1449764.html

#### 参数
```
dump-autoload :执行此命令会把自动加载写入自动加载的配置文件中
```