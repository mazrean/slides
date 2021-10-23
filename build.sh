#!/bin/sh

for file in `\find . -maxdepth 2 -type f | grep -E .md$ --exclude-dir={.git,node_modules}`; do
  npx @marp-team/marp-cli $file -o `\dirname $file`/index.html
done
