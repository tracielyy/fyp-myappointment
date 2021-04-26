#!/bin/bash
echo "Test Release"
echo "extension=grpc.so" > "/app/.heroku/php/etc/php/php.ini"
echo "extension=grpc.so" > "/app/.heroku/php/etc/php"
