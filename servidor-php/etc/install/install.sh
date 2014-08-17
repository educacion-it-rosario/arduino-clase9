#!/bin/bash

set -ex

HOME=${1}

DATABASE_NAME="giana"
MYSQL_USER="giana"
MYSQL_PASS="giana1234"
BITNAMI_VERSION=5.4.31-0
KERNEL_VERSION="3.13.0-33-generic"

BITNAMI_NAME="bitnami-lampstack-${BITNAMI_VERSION}-linux-installer.run"
BITNAMI_FILE="${HOME}/etc/install/${BITNAMI_NAME}"
BITNAMI_URL="https://bitnami.com/redirect/to/38786/${BITNAMI_NAME}"

if [ ! -f /home/vagrant/.locales ]; then
    # Need to fix locale so that Postgres creates databases in UTF-8
    locale-gen en_GB.UTF-8
    dpkg-reconfigure locales
    touch /home/vagrant/.locales
fi

export LANGUAGE=en_GB.UTF-8
export LANG=en_GB.UTF-8
export LC_ALL=en_GB.UTF-8

if [ ! -f /tmp/apt-get.updated ]; then
    apt-get update -y
    touch /tmp/apt-get.updated
fi

if [ ! -d /lib/modules/${KERNEL_VERSION}/ ] ; then
    apt-get install -y linux-headers-${KERNEL_VERSION} \
        linux-image-${KERNEL_VERSION} \
        linux-image-extra-${KERNEL_VERSION}
fi

if ! command -v wget ; then
    apt-get install -y wget
fi

if [ ! -f ${BITNAMI_FILE} ]; then
    wget -O ${BITNAMI_FILE} ${BITNAMI_URL}
fi

if ! command -v minicom ; then
    apt-get install -y minicom
fi

if [ ! -f /tmp/${BITNAMI_NAME} ]; then
    cp ${BITNAMI_FILE} /tmp/
    chmod +x /tmp/${BITNAMI_NAME}
fi

if [ ! -d /opt/bitnami ]; then
    /tmp/${BITNAMI_NAME} \
        --mode unattended \
        --enable-components phpmyadmin \
        --installer-language es_AR \
        --prefix /opt/bitnami \
        --base_user ${MYSQL_USER} \
        --base_password ${MYSQL_PASS} \
        --apache_server_port 8000 \
        --apache_server_ssl_port 8443 \
        --mysql_port 3306 \
        --mysql_password ${MYSQL_PASS} \
        --mysql_database_name ${DATABASE_NAME} \
        --mysql_database_username ${MYSQL_USER} \
        --mysql_database_password ${MYSQL_PASS} \
        --phpmyadmin_password ${MYSQL_PASS} \
        --launchbch 0
fi

if ! command -v mcedit ; then
    apt-get install -y mc
fi


if [ ! -f /etc/init.d/bitnami ] ; then
    cp ${HOME}/etc/install/bitnami.init /etc/init.d/bitnami
    chmod +x /etc/init.d/bitnami
    update-rc.d bitnami defaults
    service bitnami start
fi


MYSQL="/opt/bitnami/mysql/bin/mysql"

DATABASES=`echo "SHOW DATABASES;" | ${MYSQL} -u root -p${MYSQL_PASS} `

if [[ "$DATABASES" != *${DATABASE_NAME}* ]]; then

    ${MYSQL} -u root -p${MYSQL_PASS} <<EOF
CREATE USER ${MYSQL_USER}@localhost IDENTIFIED BY "${MYSQL_PASS}" ;
CREATE DATABASE IF NOT EXISTS ${DATABASE_NAME} ;
GRANT ALL PRIVILEGES ON ${DATABASE_NAME}.* TO ${MYSQL_USER}@localhost ;
EOF
    ${MYSQL} -u${MYSQL_USER} -p${MYSQL_PASS} ${DATABASE_NAME} \
        < ${HOME}/mysql/db.sql

    echo "${DATABASE_NAME} created"
else
    echo "${DATABASE_NAME} exists, ignoring"
fi

cp ${HOME}/etc/install/phpmyadmin-conf-httpd-app.conf \
    /opt/bitnami/apps/phpmyadmin/conf/httpd-app.conf

ln -s ${HOME}/app /opt/bitnami/apps/giana
echo 'Include "/opt/bitnami/apps/giana/conf/httpd-prefix.conf"' >> \
    /opt/bitnami/apache2/conf/bitnami/bitnami-apps-prefix.conf

service bitnami restart

# configure permissions and udev
usermod -a -G dialout vagrant
cp ${HOME}/etc/install/52-arduino.rules /etc/udev/rules.d
udevadm control --reload-rules
udevadm trigger --attr-match=subsystem=usb
