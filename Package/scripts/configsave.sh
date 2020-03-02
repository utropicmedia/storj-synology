#!/bin/bash

# This script  stores the configuration file in the *.yml file

echo 'Hello Synology'

# SYNOPKG_PKGNAME="StorJ"
# LOG="/var/log/$SYNOPKG_PKGNAME"
# DESTDIR=/volume1/@appstore/StorJ/scripts/docker-compose.yml
# echo `date` $SYNOPKG_PKGNAME  "is updating" >> $LOG

# ERRLOG="$LOG"_ERR
# rm -f "$ERRLOG"


# if [ -f "$DESTDIR" ]
# then 
#     echo "${WALLET}" > "$DESTDIR"
#     echo "${EMAIL}">"$DESTDIR"
#     echo "${ADDRESS}">"$DESTDIR"
#     echo "${BANDWIDTH}">"$DESTDIR"
#     echo "${STORAGE}">"$DESTDIR"
# fi






# if [ -s "$ERRLOG" ]; then
#   echo `date` "----------------------------------------------------"
#   cat $ERRLOG
#   echo `date` "----------------------------------------------------"
#   # Add infor to the log to be displayed by the Catalog Manager
#   echo `date` "Adding info to the  log file"
#   sed -i 's/$/<br>/' "$ERRLOG"
#   cat $ERRLOG >> $SYNOPKG_TEMP_LOGFILE
#   exit 1
# fi
