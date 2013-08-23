#!/bin/bash -eu

music_dir=/var/www/music/./

find $music_dir | perl -e "while(<>) { if ( m/\.(jpg|jpeg|png)$/i) {print $_;}}" | while read file; do
	if [ ! -f "$file.tm.gif" -o "$file" -nt "$file.tm.gif" ]; then
		echo "conveting file $file ..."
		convert "$file" -resize 120X120 "$file.tm.gif"
	fi
done
