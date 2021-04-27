#!/bin/bash
echo "Test Release"
echo "extension_dir='./ext'"
echo "extension=php_grpc.dll" > "/app/.heroku/php/etc/php/php.ini"
echo "extension=php_grpc.dll" > "/app/.heroku/php/etc/php/conf.d/000-heroku.ini" 
echo "extension=php_grpc.dll" > "/app/.heroku/php/etc/php/conf.d/010-ext-zend_opcache.ini"
