<?php

// ============================================================
//  CONFIGURACIÓN DE LA APLICACIÓN
//  Cambia DB_USUARIO, DB_PASSWORD y DB_NOMBRE según tu entorno
// ============================================================

// Datos de conexión a la base de datos
define('DB_HOST',     'localhost');
define('DB_USUARIO',  'root');       // Cambia por tu usuario MySQL
define('DB_PASSWORD', '');           // En AMPPS suele ser 'mysql'
define('DB_NOMBRE',   'recuperacion');

// Ruta absoluta a la carpeta /app
define('RUTA_APP', dirname(__DIR__));

// URL base – ajusta el segmento si tu carpeta tiene otro nombre
define('RUTA_URL', 'http://localhost/mvcrecuperacion/public');

// Nombre del sitio que aparece en el título y cabeceras
define('NOMBRESITIO', 'Clínica Veterinaria');
