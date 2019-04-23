#!/usr/bin/env bash
ROOT=`pwd`

echo $ROOT
cd dir
while read -r file;
do
    op=${file:0:1}
    file=${file:1}
    echo $op
    if [ $op = 'M' ]
    then
        fun=`git diff $file | grep 'public function'`
        echo $fun
    else
        echo $file;
    fi

done < <(git diff --name-status --diff-filter=ACM)