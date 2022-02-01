
# Prueba el tiempo BACK

Esta es la construccion de un api para presentar una prueba para el tiempo, segun los requerimientos solicitados.

Esta aplicación es desarrollada en php y Laravel, ademas usa.

- Laravel Passport: Esto para el proceso de Login.




## Instalación

Para instalar el proyecto se necesita Composer

Se usan los siguientes comandos: 

```bash
  git clone https://github.com/andres11362/prueba-el-tiempo_back.git
  cd prueba-el-tiempo_back
  composer install
  cp .env.example .env
```
Cuando ya tenemos estos paso completados, generamos la llave de la aplicacion con

```bash
  php artisan key:generate
```
Generamos las migraciones, generamos las llaves del passport y generamos los sembradores:

```bash
  php artisan migrate
  php artisan passport:install
  php artisan db:seed
```

Con esto ya tendriamos para probar la aplicacion.

El usuario con el correo@pruebas.com  es el superadministrador y su clave es '12345678'.



    
## Ejemplo de las variables de configuración

Para correr este proyecto en el archivo .env se pueden establecer los siguientes paramatros:


`APP_NAME` Nombre de la aplicación.

`APP_ENV` Entorno de trabajo.

`APP_KEY` Llave de la aplicación.

`APP_DEBUG` Habilitar muestra de errores (true o false).

`DB_CONNECTION` Driver de conexión de la base de datos.

`DB_HOST` Host de la base de datos.

`DB_DATABASE` Nombre de la base de datos.

`DB_USERNAME` Usuario de la base de datos

`DB_PASSWORD` Contraseña de la base de datos

Para el caso de los correo se implemento mailtrap.io

`MAIL_MAILER`Protocolo usado para el envio de correos

`MAIL_HOST` Host del servicio de correo

`MAIL_PORT` Puerto del servicio de correo

`MAIL_USERNAME` Usuario del servicio de correo

`MAIL_PASSWORD` Password del servicio de correo

`MAIL_ENCRYPTION` Tipo de cifrado que usara el servicio de correo

`MAIL_FROM_ADDRESS` Dirección por la cual se enviaran los correo.

`MAIL_FROM_NAME` Nombre del usuario que envia el correo.
## Correr localmente

Desde el localhost o servidor de su preferencia




## Authors

- [@andres11362](https://github.com/andres11362)

