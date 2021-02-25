#!/usr/bin/env bash

COMMAND=${1:-"bash"}

HOST_USER_ID=`id -u`
IMAGE_TAG="hgraca/cli-sms:PHP_7.2-alpine"

while getopts ":u:i:" opt; do
  case ${opt} in
  u)
    HOST_USER_ID=$OPTARG
    ;;
  i)
    IMAGE_TAG=$OPTARG
    ;;
  \?)
    echo "Invalid option: ${OPTARG}" 1>&2
    help
    exit 1
    ;;
  :)
    echo "Invalid option: ${OPTARG} requires an argument" 1>&2
    help
    exit 2
    ;;
  esac
done
shift $((OPTIND - 1))

DOCKER_USER_ID=${HOST_USER_ID} IMAGE_TAG=${IMAGE_TAG} docker-compose -f build/container/docker-compose.yml run app bash -c "${COMMAND}"
