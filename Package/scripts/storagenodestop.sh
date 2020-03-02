#!/bin/bash
# This script Stops the docker image of storagenode and removes it
PKGNAME="StorJ"
LOG="/var/log/$PKGNAME"
echo `date` "Storagenode is stopping" >> $LOG
output=`docker stop storagenode 2>&1 `
if [[ "x$output" == "xstoragenode" ]]
then
	output="Success in stopping storagenode "
fi
echo $output >> $LOG
echo $output
output=`docker rm -f storagenode 2>&1 `
if [[ "x$output" == "xstoragenode" ]]
then
	output="Success in removing storagenode "
fi
echo $output >> $LOG
echo $output