#!/bin/bash
echo "Test Release"
echo "extension=grpc.so" > "/vendor/heroku/heroku-buildpack-php/conf/php/8/php-fpm.conf"
echo "extension=grpc.so" > "/vendor/heroku/heroku-buildpack-php/conf/php/php-fpm.conf"
