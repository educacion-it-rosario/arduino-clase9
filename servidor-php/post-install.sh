#!/bin/bash

HOME=${1}

DATABASE_NAME="giana"
MYSQL_USER="root"
MYSQL_PASS="vagrant"

echo "CREATE DATABASE ${DATABASE_NAME}" | mysql -u ${MYSQL_USER} -p${MYSQL_PASS}
mysql -u${MYSQL_USER} -p${MYSQL_PASS} ${DATABASE_NAME} < ${HOME}/mysql/db.sql

echo "${DATABASE_NAME} created"
