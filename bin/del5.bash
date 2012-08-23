#! /bin/bash
# BE CAREFUL: Delete old dumps (retain 5 days)

cd $HOME/admin/

dest=$1

find ${dest} -mtime +5 -exec rm {} \;
