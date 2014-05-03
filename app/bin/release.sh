#!/usr/bin/env bash

PROJECT_HOME=/var/www/html/m
TARGET_DIR=~/tmp/m
TARGET_ZIP=~/tmp/m.tar.gz

rm -rf $TARGET_DIR
mkdir $TARGET_DIR
cp -r $PROJECT_HOME/app $TARGET_DIR
cp -r $PROJECT_HOME/assets $TARGET_DIR
cp -r $PROJECT_HOME/vendor $TARGET_DIR
cp -r $PROJECT_HOME/index.php $TARGET_DIR

rm -f $TARGET_DIR/app/storage/cache/*
rm -f $TARGET_DIR/app/storage/logs/*
rm -f $TARGET_DIR/app/storage/sessions/*
rm -f $TARGET_DIR/app/storage/templates/*

cd $TARGET_DIR/..
tar -zcvf $TARGET_ZIP `basename $TARGET_DIR`

echo "Please upload and extract $TARGET_ZIP using file manager of godaddy"
echo "Please modify .htaccess of url rewrite if it is the first release"
