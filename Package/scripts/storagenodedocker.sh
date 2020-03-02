#!/bin/bash

# This script downloads the docker image of storagenode and verifies that it is available
#SYNOPKG_PKGNAME="StorJ"
#LOG="/var/log/$SYNOPKG_PKGNAME"
#echo `date` $SYNOPKG_PKGNAME  "is getting downloaded" >> $LOG

#ERRLOG="$LOG"_ERR
#rm -f "$ERRLOG"

if [[ "$docker image inspect storjlabs/storagenode:latest)" == "" ]]; then
  # download the docker image
  docker pull storjlabs/storagenode:latest
  echo `date`  " docker image is getting downloaded" >> $LOG
fi
#docker pull storjlabs/storagenode:beta

if [[ "$docker image inspect storjlabs/storagenode:latest)" != "" ]]; then
  # download the docker image
  #docker pull storjlabs/storagenode:beta
  echo `date` " Docker image is already there " >> $LOG
fi








# if [ -s "$ERRLOG" ]; then
#   echo `date` "----------------------------------------------------"
#   cat $ERRLOG
#   echo `date` "----------------------------------------------------"
#   # Add infor to the log to be displayed by the Catalog Manager
#   echo `date` "Adding info to the  POST INSTALL log file"
#   sed -i 's/$/<br>/' "$ERRLOG"
#   cat $ERRLOG >> $SYNOPKG_TEMP_LOGFILE
#   exit 1
# fi

