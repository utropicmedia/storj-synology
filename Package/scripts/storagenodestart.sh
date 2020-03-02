#!/bin/bash
# This script starts storagenode 
PKGNAME="StorJ"
LOG="/var/log/$PKGNAME"
echo `date` "Storagenode is starting" >> $LOG
IPADDRESS=$(ip -4 -o addr show eth0 | awk '{print $4}' | cut -d "/" -f 1)




cmd="docker run -d --restart unless-stopped -p ${1}:28967 -p 14002:14002 -e WALLET=${2} -e EMAIL=${3} -e ADDRESS=${8}:${1} -e BANDWIDTH=${4}TB -e STORAGE=${5}GB -v ${6}:/app/identity -v ${7}:/app/config --name storagenode storjlabs/storagenode:beta "
echo `date` " Starting Storagenode ---> " >> $LOG



docker ps -a  >> $LOG
echo " $cmd 2>&1 " >> $LOG 


output=` ${cmd} 2>&1 `
echo $output >> $LOG 
cat <<< $output 

output=`docker ps -a 2>&1 `
echo $output >> $LOG 
cat <<< $output

