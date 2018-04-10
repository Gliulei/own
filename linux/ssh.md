**ssh**

```
ssh host -o StrictHostKeyChecking=no 2>&1
-o StrictHostKeyChecking=no 表示:第一次不用确认,直接连上,实现任务自动化

2>&1 表示:标准输出重定向到标准输入 只有加了它,exec才能获取到错误信息
```
