**ssh**

```
ssh host -o StrictHostKeyChecking=no 2>&1
-o StrictHostKeyChecking=no 表示:第一次不用确认,直接连上,实现任务自动化

2>&1 表示:标准输出重定向到标准输入 只有加了它,exec才能获取到错误信息
```

###参数介绍
CheckHostIP=no 设置ssh是否查看连接到服务器的主机的IP地址以防止DNS欺骗。建议设置为“yes

IdentitiesOnly=yes

PasswordAuthentication=no 设置是否使用口令验证

IdentityFile= ~/.ssh/identity 设置从哪个文件读取用户的RSA安全验证标识

IdentitiesOnly=yes 只接受SSH key 登录