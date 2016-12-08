#!/bin/bash

uname=$2
passwd=$3
/usr/bin/expect <<EOF
set timeout 15
log_user 0
spawn su - $uname
expect "*?assword:"
send -- "$passwd\r"
send -- "\r"
expect "*?$uname*"
send -- "sudo -u root /bin/bash /opt/common/RA/showSessions.sh -s '$1' -d '$4'\r"
expect "*?assword*"
send -- "$passwd\r"
send -- "\r"
send -- "exit\r"
expect eof
EOF
