#!/bin/bash
filename=$1
if [ -n "$filename" ];
then
    host=$(cat ~/.ssh/config | head -n 1 | awk '{if ($1=="Host") print $2}')
    user=$(cat ~/.ssh/config | head -n 4 | awk '{if ($1=="User") print $2}')
    #echo $host
    #echo $user
    if [ -z "$host" ];
    then 
        echo "user is empty"
        exit 1
    fi

    if [ -z "$user" ]
    then
        echo "host is empty"
        exit 1
    fi
    scp $filename $user@$host:/home/$user

    web_filename=$(basename $filename)
    ssh $host scp $web_filename web:/home/$user
    echo 'success'
else
    echo 'no filename'
fi
