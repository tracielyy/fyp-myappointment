#!/bin/bash
echo "Test Release"
echo "extension=grpc.so" > "/app/.heroku/php/etc/php/php.ini"
echo "extension=grpc.so" > "/app/.heroku/php/etc/php/conf.d/000-heroku.ini" 
echo "extension=grpc.so" > "/app/.heroku/php/etc/php/conf.d/010-ext-zend_opcache.ini"
