#!/bin/bash
set -e

cleanup() {
    if [[ -f "/home/symphony_lesson/xdebug-3.4.7.tgz" ]]; then
        rm -f "/home/symphony_lesson/xdebug-3.4.7.tgz"
    fi
    if [[ -d "/home/symphony_lesson/xdebug-3.4.7" ]]; then
        rm -R "/home/symphony_lesson/xdebug-3.4.7"
    fi
}

trap cleanup SIGINT
trap cleanup ERR
  
ROOT="/home/symphony_lesson"

tar -xvzf "/home/symphony_lesson/xdebug-3.4.7.tgz" --directory "$ROOT"
cd "/home/symphony_lesson/xdebug-3.4.7"

phpize
./configure --enable-xdebug --with-php-config=/bin/php-config
make
  
trap cleanup EXIT

PATH_TO_XDEBUG_SO="/usr/lib/php/20230831"
ZEND_EXTENSION='zend_extension = xdebug'
FILE="/etc/php/8.3/cli/conf.d/99-xdebug.ini"

sudo mkdir -p "$PATH_TO_XDEBUG_SO"

if [[ -f "$PATH_TO_XDEBUG_SO/xdebug.so" ]]; then
    sudo rm "$PATH_TO_XDEBUG_SO/xdebug.so"
fi

sudo cp /home/symphony_lesson/xdebug-3.4.7/modules/xdebug.so "$PATH_TO_XDEBUG_SO"
if [[ ! -f "${FILE}" ]]; then
  sudo touch "${FILE}"
fi
LAST_LINE=$(tail -n 1 "${FILE}")
if [[ "$LAST_LINE" != "${ZEND_EXTENSION}" ]]; then
  echo "${ZEND_EXTENSION}" | sudo tee -a "${FILE}"
fi
