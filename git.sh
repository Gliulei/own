#!/bin/bash

read -p "请输入备注：" commit_content
git add -A *
echo "add OK\n"
git commit -m "$commit_content"
echo "commit OK\n"
git push
echo "push OK\n"
exit 0