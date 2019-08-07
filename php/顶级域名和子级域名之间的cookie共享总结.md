####前言
cookie是程序员刚开始学习时就会遇到的问题,了解cookie对设计用户登陆系统有很大帮助。
代码都是PHP语言,其它的场景类似。

####设置COOKIE

**顶级域名**

顶级域名只能设置domain为顶级域名，不能设置为二级域名或者三级域名等等，否则cookie无法生成。
如a.com能设置domain为a.com或者www.a.com，但不能设置domain为login.a.com，这样cookie不会生成。

代码示例:

```
setcookie("name1", "test1", time() + 1000);//a.com自己可以看到
setcookie("name2", "test2", time() + 1000, "/", "www.a.com");//*.www.a.com都可以看到
setcookie("name3", "test3", time() + 1000, "/", "a.com");//*.a.com都可以看到
setcookie("name4", "test4", time() + 1000, "/", "login.a.com");//设置无效
```
设置domain的时候，.a.com和a.com是一样的。
未指定domain时，默认的domain为用哪个域名访问就是哪个。

总的来说，顶级域名设置的cookie可以共享【需要指定domain主域名的host】给二级域名，也可以自己私有【不指定domain】。


**二级域名**
```
setcookie("key1", "value1");//只有自己可以看到
setcookie("key2", "value2", time() + 1000, "/", "a.com");//*.a.com都可以看到
setcookie("key3", "value3", time() + 1000, "/", "test.game.a.com");//设置无效
```

结论 设置cookie的话只能在本域名下或者domain级别高于自身的域名下才会生效！

####读取COOKIE
二级域名能读取设置了domain为顶级域名或者自身的cookie，不能读取其他二级域名domain的cookie。例如：要想cookie在多个二级域名中共享，需要设置domain为顶级域名，这样就可以在所有二级域名里面或者到这个cookie的值了。

顶级域名只能获取到domain设置为顶级域名的cookie，domain设置为其他子级域名的无法获取。

####删除COOKIE
删除cookie理解为是修改cookie的一种特殊场景，只需将expire设置为过期、值设置为null即可，代码如下：
删除a.com下面的cookie值
setcookie("name", null, time() - 1000, "/", "a.com");

删除game.a.com下面自身的cookie值
setcookie("game", null, time() - 1000);

### 参考
[cookie文章](https://segmentfault.com/a/1190000006932934)