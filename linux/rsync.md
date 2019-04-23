###rsync



#####参数
- --verbose 详细信息
- --compress 传输过程中压缩
- --progress 显示过程
- --out-format 输出格式 %n:文件名
-e 执行ssh等其它命令

**例子**
```
rsync --verbose --compress --progress --out-format="Receiving %n" -e \
    "ssh -p 22 \
         -o CheckHostIP=no \
         -o IdentitiesOnly=yes \
         -o StrictHostKeyChecking=no \
         -o PasswordAuthentication=no \
         -o IdentityFile=/Sites/piplin/storage/app/sshkey7oVA2p" \
    /Users/MOMO/Sites/piplin/storage/app/1_20180502082836.tar.gz momo@172.16.249.135:/var/www/app/1_20180502082836.tar.gz
```