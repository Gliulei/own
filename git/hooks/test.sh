#!/usr/bin/env bash

if php -l test.php > /dev/null
then
    echo 'success'
else
    echo 'error'
fi

