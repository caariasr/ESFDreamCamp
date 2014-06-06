#!/bin/bash

if [ ! -f /mongostatus.txt ];
then
    pecl install mongo
    echo "mongo extension installed" > /mongostatus.txt
    apachectl restart
fi
