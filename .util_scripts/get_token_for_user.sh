#!/bin/sh

usage="Usage: $0 -p [-v]"
unset USER_TYPE EMAIL PRINT_CURL

TMP_EMAIL='admin@boilerplate.com'
EMAIL=''
while getopts t:e:v option
do
  case "${option}"
  in
    t) USER_TYPE=${OPTARG};;
    e) EMAIL=${OPTARG};;
    v) PRINT_CURL=true;;
  esac
done

[ "$USER_TYPE" = "ssh" ] && TMP_EMAIL='ssh@boilerplate.com'
[ -z "$EMAIL" ] && EMAIL="$TMP_EMAIL"

CURL_COMMAND="curl -X POST -H \"Content-Type: application/json\" http://localhost:8080/login_check -d '{\"username\":\"$EMAIL\",\"password\":\"password\"}'"

[ -n "${PRINT_CURL}" ] && echo $CURL_COMMAND

eval $CURL_COMMAND
