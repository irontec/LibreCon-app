#!/bin/bash
cd $(dirname "$0")

while true; do
	sh process.sh "$(inotifywait -e create /opt/librecon/klear-web/public/img/photocall/ | grep CREATE | cut -d ' ' -f 3)"
done
