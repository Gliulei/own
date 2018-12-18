**前端xss** 
跨站脚本功攻击是指恶意攻击者在web页面上插入html代码,当用户浏览网页时,网页中的代码就会执行,
从而达到恶意用户的特殊目的。
数据流程如下：攻击者的Html输入—>web程序—>进入数据库—>web程序—>用户浏览器

比如:
<form>
    <input name="name" type="text">
</form>

恶意用户填写:
<script type="text/javascript">console.log("You are a fool fish")</script>
这样用户访问页面的时候就会弹出这个内容。

**防范**
后端对输入参数进行过滤，过滤敏感字符，替换敏感字符
PHP的htmlentities()或是htmlspecialchars()来过滤html