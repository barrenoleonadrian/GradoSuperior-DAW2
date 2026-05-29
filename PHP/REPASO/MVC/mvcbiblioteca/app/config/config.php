<?php

//Configuración acceso a base de datos
define('DB_HOST', 'localhost'); //tu servidor de BD.
define('DB_USUARIO', 'root');
define('DB_PASSWORD', '');
define('DB_NOMBRE', 'biblioteca'); // Tu base de datos



//Ruta de la aplicación. /app o /src
define('RUTA_APP', (dirname(__DIR__)));

//Ruta url Ejemplo: http://localhost/ud5/mvc2app
//define ('RUTA_URL', '_URL_');
define ('RUTA_URL', 'http://localhost/mvcbiblioteca/public');

//define ('NOMBRESITIO', '_NOMBRE_SITIO');
define ('NOMBRESITIO', 'MVC con Composer Examen de Recuperación');

// Cargar archivo INI si es necesario.

// Otras configuraciones iniciales pueden ir aquí