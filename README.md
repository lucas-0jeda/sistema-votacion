============================================================
INSTALACIÓN Y CONFIGURACIÓN DE PROYECTO LARAVEL EN XAMPP
============================================================

REQUISITOS PREVIOS:
----------------------
- XAMPP instalado (con Apache y MySQL activos) -> https://www.apachefriends.org/download.html
- Git instalado -> https://git-scm.com/downloads
- PHP 8.0+ (incluido en XAMPP)
- Composer instalado globalmente -> https://getcomposer.org/download/

PASOS DE INSTALACIÓN:
-----------------------
1. Clonar repositorio:
   cd C:/xampp/htdocs
   git clone https://github.com/lucas-0jeda/sistema-votacion sistema-votacion

2. Instalar dependencias:
   cd sistema-votacion
   composer install

3. Configurar entorno:
   copy .env.example .env
   php artisan key:generate

4. Configurar base de datos (en .env):
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sistema_votacion 
   DB_USERNAME=root
   DB_PASSWORD=

6. Crear base de datos en phpMyAdmin:
   - Acceder a http://localhost/phpmyadmin
   - Crear base de datos con el nombre especificado en sistema_votacion

7. Ejecutar migraciones:
   php artisan migrate --seed

MÉTODOS DE ACCESO:
---------------------
-> Usando Artisan Serve (recomendado para desarrollo)
   php artisan serve --port=8000
   URL: http://localhost:8000
