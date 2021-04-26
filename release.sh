#!/bin/bash
echo "Test Release"
echo "extension=grpc.so" > "/conf/php/8/php-fpm.conf"
echo "extension=grpc.so" > "/conf/php/php-fpm.conf"
