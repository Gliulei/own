# composer

### composer安装目录
+ 如果不指定环境变量COMPOSER_HOME, 一般会安装在$HOME/.composer里

### composer安装目录介绍
+ config.json 配置文件
+ cache 缓存目录
    + files: dist对应源下载的zip包
    + repo: 包缓存 存放包的descption, 如liulei/multi-review对应:provider-liulei\$multi-review.json
    + vcs: vcs下载的缓存,一般是git clone下来的

### 例子
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


### composer - github 以tag为 包版本

所以如果不提交tag的话  可能导致你的  包无法使用composer安装
http://www.mamicode.com/info-detail-1449764.html

#### 参数
```
dump-autoload :执行此命令会把自动加载写入自动加载的配置文件中
```

### 文档
[中国全量镜像](https://www.phpcomposer.com/5-features-to-know-about-composer-php)
[composer教程](https://learnku.com/docs/composer/2018/scripts/2095)
[阿里云 Composer 全量镜像](https://mirrors.aliyun.com/composer/index.html)