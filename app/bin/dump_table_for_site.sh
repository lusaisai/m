#!/usr/bin/env bash

FILE=~/tmp/mav.sql
>$FILE

for table in album artist image song
do
	mysqldump mav $table -u mav -pmav >> $FILE
done

echo "Please import $FILE use phpmyadmin of godaddy"
