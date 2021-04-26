#!/bin/bash
echo "Test Release"
echo "extension=grpc.so" > "vendor/heroku/heroku-buildpack-php/support/build/_conf/php/7/0/conf.d/000-heroku.ini"
echo "extension=grpc.so" > "vendor/heroku/heroku-buildpack-php/support/build/_conf/php/conf.d/000-heroku.ini"
