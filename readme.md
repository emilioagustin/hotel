##Software y Librerías

- Instalar PHP 7.1 y además de las librerías base de Laravel, instalar librería GD y sqlite
- Configurar archivo .env con los parámetros de configuración propios

##Instalación

- composer install
- npm install
- php artisan migrate
- php artisan db:seed

##Ejecución

- php artisan serve
- npm run watch

##Testing

composer test

--------------------------------------------------------------------------------------------

##Problemas

El faker de imágenes actualmente no funciona y por eso no mete imágenes de prueba, es un 
problema debido a que no responde la web lorempixel.com
Una vez vuelva a funcionar modificar el archivo ContentTranslationsTableSeeder.php:

'image_path' => $faker->image($dir = $filepath, $width = 1024, $height = 1024, null, false)