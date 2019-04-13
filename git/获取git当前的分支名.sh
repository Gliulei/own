#!/usr/bin/env bash
#方法1
function git.branch {
  br=`git branch | grep "*"`
  echo ${br/* /}
}

#方法2
git symbolic-ref --short -q HEAD