#!/bin/bash
# http://dev.mysql.com/doc/refman/5.5/en/mysqldump.html
# ( 1 ) Backs up all info to time stamped individual directories, which makes it easier to track
# ( 2 ) Now maintains a single log that contains additional information
# ( 3 ) Includes a file comment header inside each compressed file
# ( 4 ) Used more variables instead of hard-code to make routine easier to use for something else
#
# Posted by Ryan Haynes on July 11 2007 6:29pm

# DO NOT DELETE AUTOMATICALLY FOR NOW, MAYBE LATER

DELETE_EXPIRED_AUTOMATICALLY="TRUE"
expire_minutes=$(( 60 * 24 * 7 )) # 7 days old

if [ $expire_minutes -gt 1440 ]; then
    expire_days=$(( $expire_minutes /1440 ))
else
    expire_days=0
fi

function pause(){
 read -p "$*"
}

mysql_username="root"
mysql_password="zcurQCdg"
current_dir=`pwd`
echo -n "Current working directory is : "
echo $current_dir
echo "------------------------------------------------------------------------"

TIME_1=`date +%s`
TS=$(date +%Y.%m.%d\-%I.%M.%p)

BASE_DIR=./mysql
BACKUP_DIR=${BASE_DIR}/$TS
BACKUP_LOG_NAME=mysql_dump_runtime.log
BACKUP_LOG=${BASE_DIR}/${BACKUP_LOG_NAME}

mkdir -p $BACKUP_DIR
chown demo:demo $BACKUP_DIR
chmod 755 $BASE_DIR
chmod -R 755 $BACKUP_DIR

cd $BACKUP_DIR
echo -n "Changed working directory to : "
pwd

echo "Saving the following backups..."
echo "------------------------------------------------------------------------"

DBS="$(mysql --user=${mysql_username} --password=${mysql_password} -Bse 'show databases')"
for db in ${DBS[@]}
do
    normal_output_filename=${db}.sql
    compressed_output_filename=${normal_output_filename}.bz2
    echo $compressed_output_filename
    
    echo "-- $compressed_output_filename - $TS" > $normal_output_filename
    echo "-- Logname : `logname`" >> $normal_output_filename
    # mysqldump --user=${mysql_username} --password=${mysql_password} $db --single-transaction -R | bzip2 -c > $compressed_output_filename
    mysqldump --user=${mysql_username} --password=${mysql_password} $db --single-transaction -R >> $normal_output_filename
    bzip2 -c $normal_output_filename > $compressed_output_filename
    rm $normal_output_filename
done
echo "------------------------------------------------------------------------"

TIME_2=`date +%s`

elapsed_seconds=$(( ( $TIME_2 - $TIME_1 ) ))
elapsed_minutes=$(( ( $TIME_2 - $TIME_1 ) / 60 ))

cd $BASE_DIR
echo -n "Changed working directory to : "
pwd
echo "Making log entries..."

if [ ! -f $BACKUP_LOG ]; then
    echo "------------------------------------------------------------------------" > ${BACKUP_LOG_NAME}
    echo "THIS IS A LOG OF THE MYSQL DUMPS..." >> ${BACKUP_LOG_NAME}
    echo "DATE STARTED : [${TS}]" >> ${BACKUP_LOG_NAME}
    echo "------------------------------------------------------------------------" >> ${BACKUP_LOG_NAME}
    echo "[BACKUP DIRECTORY ] [ELAPSED TIME]" >> ${BACKUP_LOG_NAME}
    echo "------------------------------------------------------------------------" >> ${BACKUP_LOG_NAME}
fi
    echo "[${TS}] This mysql dump ran for a total of $elapsed_seconds seconds." >> ${BACKUP_LOG_NAME}
    echo "------------------------------------------------------------------------" >> ${BACKUP_LOG_NAME}

# delete old databases. I have it setup on a daily cron so anything older than 60 minutes is fine
if [ $DELETE_EXPIRED_AUTOMATICALLY == "TRUE" ]; then
    counter=0
    for del in $(find $BASE_DIR -name '*-[0-9][0-9].[0-9][0-9].[AP]M' -mmin +${expire_minutes})
    do
        counter=$(( counter + 1 ))
        echo "[${TS}] [Expired Backup - Deleted] $del" >> ${BACKUP_LOG_NAME}
    done
    echo "------------------------------------------------------------------------"
    if [ $counter -lt 1 ]; then
        if [ $expire_days -gt 0 ]; then
            echo There were no backup directories that were more than ${expire_days} days old:
        else
            echo There were no backup directories that were more than ${expire_minutes} minutes old:
        fi
    else
        echo "------------------------------------------------------------------------" >> ${BACKUP_LOG_NAME}
        if [ $expire_days -gt 0 ]; then
            echo These directories are more than ${expire_days} days old and they are being removed:
        else
            echo These directories are more than ${expire_minutes} minutes old and they are being removed:
        fi
        echo "------------------------------------------------------------------------"
        echo "\${expire_minutes} = ${expire_minutes} minutes"
        counter=0
        for del in $(find $BASE_DIR -name '*-[0-9][0-9].[0-9][0-9].[AP]M' -mmin +${expire_minutes})
        do
        counter=$(( counter + 1 ))
           echo $del
           rm -R $del
        done
    fi
fi
echo "------------------------------------------------------------------------"
cd `echo $current_dir`
echo -n "Restored working directory to : "
pwd
