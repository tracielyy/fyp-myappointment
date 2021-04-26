#!/bin/bash
echo "Test Release"
echo "extension_dir=.'/ext'"
echo "extension=grpc" > "/app/.heroku/php/etc/php/php.ini"
echo "extension=grpc" > "/app/.heroku/php/etc/php/conf.d/000-heroku.ini" 
echo "extension=grpc" > "/app/.heroku/php/etc/php/conf.d/010-ext-zend_opcache.ini"
