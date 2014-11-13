#!/bin/bash
cd $(dirname "$0")

FILE=$1
HASH=$(echo "$1" | md5sum | cut -d ' ' -f 1)
sleep 3 
convert "$1" -resize "100x100^" -gravity center -crop 100x100+0+0 +repage "temp_thumbnail_$HASH.jpg"
mv $1 photocall_$HASH.jpg
mv temp_thumbnail_$HASH.jpg thumbnail_$HASH.jpg
