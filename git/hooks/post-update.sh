#!/usr/bin/env bash

ROOT=`pwd`

while read -r file;
do
    file=${file:1}
    echo $file
done < <(git diff --cached --name-status --diff-filter=ACM)