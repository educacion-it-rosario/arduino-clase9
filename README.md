# arduino-clase9

En esta clase vamos a trabajar con Giana, un framework Php para domotica, el
c&oacute;digo contenido en est&eacute; repositorio simplifica el uso de Giana,
al encapsular el host de Giana en una m&aacute;niva virtual auto-contenible y
auto-configurable.

## Arduino

En el directorio ```arduino/giana``` encontrar&aacute;s el c&oacute; necesario
para poder conectar tu Arduino al framework Giana, dicho c&oacute;digo
deber&aacute; ser abierto con el IDE de Arduino y cargado a la placa como
cualquier otro sketch.

## Servidor PHP

Para esta clase he preparado una m&aactue;quina virtual Virtual Box la
cu&aacute;l corre Ubuntu 14.04.01 LTS de 32 bits, y el stack LAMP de Bitnami,
todo dentro del control de Vagrant, lo cu&aacute;l lo hace a&uacute;n m&aacute;s
sensillo y simple de usar.

### Requisitos [Ejecutar por &uacute;nica vez]

Para poder utilizar dicho sistema deber&aacute;s tener instalado Vagrant
primeramente, ingresar http://www.vagrantup.com/ de all&iacute; podr&aacute;s
bajar el instalador que mejor se ajuste a tu sistema operativo y arquitectura de
procesador.

### Primer arranque

La primera vez que arranquen el sistema puede parecer lento, esto se debe a que
va a instalar varios paquetes, descargar cosas, y ejecutar varios procesos,
deber&aacute; dejarlo correr, y si ten&eacute;s alg&uacute;n incoveniente
escribir un mensaje al grupo tanto de Facebook como de Google Groups as&iacute;
te podemos ayudar.

Primero ten&eacute;s que bajar el siguiente [paquete](./archive/master.zip),
luego ten&eacute;s que descomprimirlo en una carpeta cuya ruta no tenga
espacios, o sea no en "Mis Documentos", vamos a tomar como referencia
```c:\arduino-clase9```, luego ten&eacute;s que abrir una consola (abrir el
men&uacute; de aplicaciones, escribir cmd y apretar ENTER), en dicha consola
deber&aacute;s ejecutar (estas instrucciones son para Windows, pero
tambi&eacute;n aplican a linux o mac):

    $ cd c:\arduino-clase9
    $ vagrant up

Esperar y prepararse unos mates, caf&eacute;, etc.

S&iacute; todo sale bien, deber&iacute;s ver algo as&iacute;:

    ==> default: /opt/bitnami/mysql/scripts/ctl.sh : mysql  started at port 3306
    ==> default: Syntax OK
    ==> default: /opt/bitnami/apache2/scripts/ctl.sh: httpd started at port 8000
    ==> default: + usermod -a -G dialout vagrant
    ==> default: + cp /vagrant/etc/install/52-arduino.rules /etc/udev/rules.d
    ==> default: + udevadm control --reload-rules
    ==> default: + udevadm trigger --attr-match=subsystem=usb

Si tu ejecuci&oacute;n termino bien, apunt&aacute; el navegador a la siguiente
direcci&oacute;n:

    http://127.0.0.1:38000
