#!/bin/bash -eu

music_dir=/var/www/music/./
force_refresh=`false`

# find $music_dir | perl -e "while(<>) { if ( m/\.tm\.jpg$/i) {print $_;}}" | while read file; do
# 	mv "$file" ~/tmp
# done

# exit

find $music_dir | perl -e "while(<>) { if ( m/\.(jpg|jpeg|png)$/i) {print $_;}}" | while read file; do
	if [ $force_refresh -o ! -f "$file.tm.gif" -o "$file" -nt "$file.tm.gif" ]; then
		echo "conveting file $file ..."
		convert "$file" -resize 120X120 "$file.tm.gif"
	fi
done
