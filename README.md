# kulko-app-be
Kulko App Backend


### Requirements
Server
- PHP 7.1 or higher and these PHP extensions (which are installed and enabled by default in most PHP 7 installations): Ctype, iconv, JSON, PCRE, Session, SimpleXML, IntL, and Tokenizer;
- Composer, which is used to install PHP packages.
- Permissions: https://symfony.com/doc/4.4/setup/file_permissions.html#permissions-required-by-symfony-applications
- php-mysql

Symfony:
- symfony/orm-pack
- symfony/maker-bundle --dev 
- doctrine/annotations

### VPS Setup
El VPS tiene instalado Debian 10 Buster

- Setup inicial del Debian Server
https://www.digitalocean.com/community/tutorials/initial-server-setup-with-debian-10
- Instalar Docker Engine:
https://docs.docker.com/engine/install/debian/
- Instalar docker-compose 
https://docs.docker.com/compose/install/

- Paquetes utiles:

sudo apt-get install acl

##### Instalar nginx en el servidor:
Conectarse vía ssh y ejecutar `apt-get install`

#### Conectarse via SSH:

    User: kulko/root
    
    password: 

#### Setup de la aplicación
Ver archivo deploy.sh

- Para crear los planes en payku:

`sudo docker exec -it php bash`

Desde el directorio /symfony ejecutar el comando creado para crear los planes:

`php bin/console app:payku:create-plans`

#####Los nombre de los planes NO se pueden repetir! (no deben haber sido usador previamente)
Para cambiarlos, modificar las constantes en `symfony/src/Enum` 

Esto crea los tres planes por defecto en la API y en la DB (no se pueden repetir los nombres de los planes)
Luego, desde la página de payku hay que configurar la callback url para que se habiliten las suscripciones una vez
realizado el pago.

`http://66.29.133.226/api/v1/payku/suscription/callback`

 



