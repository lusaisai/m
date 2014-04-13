#!/usr/bin/env bash

if [[ $# < 1 ]]; then
	echo "Usage: `basename $0` password"
	exit 1
fi

PASSWORD=$1
MYSQL="mysql -u root -p$PASSWORD"

$MYSQL <<EOP
CREATE DATABASE mav;
CREATE USER 'mav'@'localhost' IDENTIFIED BY 'mav';
GRANT ALL PRIVILEGES ON mav.* TO 'mav'@'localhost';
FLUSH PRIVILEGES;
EOP

cd ../migrations
for file in logs.sql playlists.sql  song_tables.sql  users.sql pinyin.sql  poem.sql
do
	echo "use mav;" | cat - $file | $MYSQL
done

echo "run the following command to load the pinyin data"
cat <<EOP
mysql --local-infile -u mav -p
load data local infile 'utf8_pinyin.csv' into table pinyin_map CHARACTER SET UTF8 fields terminated by ',' enclosed by '"';
EOP
