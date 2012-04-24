#!/usr/bin/sh

expect -c "
set timeout 30
spawn php a.php
expect \"Enter PEM pass phrase:\"
send \"password\r\"
expect eof
"

echo "\n"
