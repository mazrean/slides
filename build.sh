#!/bin/sh

for file in `\find . -maxdepth 2 -type f | grep -E .md$`; do
  npx @marp-team/marp-cli@latest $file -o `\dirname $file`/index.html
done
