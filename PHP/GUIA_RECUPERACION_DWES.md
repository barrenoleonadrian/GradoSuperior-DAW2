# 🐾 GUÍA COMPLETA - Examen de Recuperación DWES DAW2
## Clínica Veterinaria - PHP MVC + API REST

> **Antes de empezar:** Descarga el `mvcrecuperacion.zip`, colócalo en `C:\ampps\www\` (o donde tengas AMPPS) y descomprímelo. Debe quedar en `C:\ampps\www\mvcrecuperacion\`

---

## 📋 ÍNDICE

1. [Instalar y configurar el proyecto](#1-instalar-y-configurar-el-proyecto)
2. [Crear la Base de Datos](#2-crear-la-base-de-datos)
3. [Cambiar el namespace con Composer](#3-cambiar-el-namespace-con-composer)
4. [Modelo Persona](#4-modelo-persona)
5. [Modelo Mascota](#5-modelo-mascota)
6. [Modelo Veterinario (para el login)](#6-modelo-veterinario-para-el-login)
7. [Controlador Paginas (login, logout, seguridad)](#7-controlador-paginas-loginlogoutseguridad)
8. [Controlador Personas](#8-controlador-personas)
9. [Controlador Mascotas](#9-controlador-mascotas)
10. [Vistas](#10-vistas)
11. [PARTE 2 - API REST](#11-parte-2---api-rest)
12. [Archivo de pruebas API (.http)](#12-archivo-de-pruebas-api-http)
13. [Resumen de archivos creados](#13-resumen-de-archivos-creados)

---

## 1. Instalar y configurar el proyecto

### 1.1 Configurar `app/config/config.php`

Abre `app/config/config.php` y rellena tus datos de base de datos:

```php
<?php

// Datos de conexión a la base de datos
define('DB_HOST', 'localhost');
define('DB_USUARIO', 'root');      // tu usuario de MySQL
define('DB_PASSWORD', '');         // tu contraseña (en AMPPS suele ser 'mysql')
define('DB_NOMBRE', 'recuperacion');

// Ruta de la aplicación
define('RUTA_APP', (dirname(__DIR__)));

// URL base del proyecto - MUY IMPORTANTE que coincida con tu carpeta
define('RUTA_URL', 'http://localhost/mvcrecuperacion/public');

define('NOMBRESITIO', 'Clínica Veterinaria');
```

### 1.2 Comprobar el `.htaccess` de `public/`

Abre `public/.htaccess` y asegúrate de que `RewriteBase` apunta a tu carpeta:

```apache
<IfModule mod_rewrite.c>
Options -Multiviews
RewriteEngine On
RewriteBase /mvcrecuperacion/public
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
</IfModule>
```

### 1.3 Verificar que funciona

Abre el navegador y ve a: `http://localhost/mvcrecuperacion`

Si ves la página de inicio del framework, ¡todo está bien!

---

## 2. Crear la Base de Datos

Abre **phpMyAdmin** (`http://localhost/phpmyadmin`) y ejecuta el SQL que viene en `app/bd/recuperacion.sql`. Después añade la tabla VETERINARIOS con el usuario del examen:

```sql
-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS recuperacion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE recuperacion;

-- Tabla personas (dueños de mascotas)
CREATE TABLE personas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100),
    telefono VARCHAR(20),
    email VARCHAR(100)
);

-- Tabla mascotas
CREATE TABLE mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    tipo VARCHAR(30) NOT NULL,
    fecha_nacimiento DATE,
    foto_url VARCHAR(255),
    id_persona INT NOT NULL,
    FOREIGN KEY (id_persona) REFERENCES personas(id)
);

-- Tabla veterinarios (para el login)
CREATE TABLE veterinarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    clave VARCHAR(50) NOT NULL
);

-- Insertar el veterinario del examen (OBLIGATORIO)
INSERT INTO veterinarios (nombre, email, clave) 
VALUES ('Felix', 'felix@veterinarios.com', 'dwes2026');

-- Datos de prueba
INSERT INTO personas (nombre, apellidos, telefono, email) VALUES
('Juan', 'Pérez', '600111222', 'juan@test.com'),
('Ana', 'López', '600333444', 'ana@test.com');

INSERT INTO mascotas (nombre, tipo, fecha_nacimiento, foto_url, id_persona) VALUES
('Rallito', 'tortuga', '2015-09-21', '/public/img/tortuga.jpeg', 1),
('Carl', 'gato', '2013-05-07', '/public/img/gato.jpeg', 1),
('Torete', 'agaponi', '2019-01-15', '/public/img/agaponi.jpeg', 2);
```

---

## 3. Cambiar el namespace con Composer

El examen pide que el namespace sea `dwes/clinica`. Abre `composer.json` y cámbialo:

```json
{
    "name": "dwes/clinica",
    "description": "Clínica Veterinaria DAW2 - Tu Nombre Apellido",
    "type": "project",
    "autoload": {
        "psr-4": {
            "Dwes\\Clinica\\": [
                "app/librerias",
                "app/controladores",
                "app/modelos"
            ]
        }
    },
    "authors": [
        {
            "name": "Tu Nombre",
            "email": "tu@email.com"
        }
    ],
    "require": {}
}
```

Ahora **actualiza el autoload**. Abre una terminal en la carpeta del proyecto y ejecuta:

```bash
composer dump-autoload
```

> ⚠️ **IMPORTANTE:** Al cambiar el namespace, debes actualizar TODOS los archivos PHP que usen `namespace` y `use`. En todos los archivos cambia `Usuario\Mvcrecuperacion` por `Dwes\Clinica`.

### 3.1 Actualizar archivos del framework con el nuevo namespace

**`app/librerias/Core.php`** — cambia la línea del namespace:
```php
namespace Dwes\Clinica;  // antes era: namespace Usuario\Mvcrecuperacion;
```

**`app/librerias/Controlador.php`**:
```php
namespace Dwes\Clinica;
```

**`app/librerias/Db.php`**:
```php
namespace Dwes\Clinica;
```

**`app/controladores/Paginas.php`**:
```php
namespace Dwes\Clinica;
use Dwes\Clinica\Controlador;
```

**`public/index.php`** — actualiza el use:
```php
use Dwes\Clinica\Core;
```

---

## 4. Modelo Persona

Crea el archivo `app/modelos/Persona.php`:

```php
<?php
namespace Dwes\Clinica;

// Modelo Persona: gestiona el acceso a la tabla 'personas' de la BD
class Persona extends Controlador {

    private Db $db; // objeto de conexión a la base de datos

    public function __construct() {
        // Instanciamos la clase Db que nos proporciona el framework
        $this->db = new Db();
    }

    // Devuelve TODAS las personas de la base de datos
    public function getPersonas(): array {
        $this->db->query("SELECT * FROM personas ORDER BY nombre");
        return $this->db->registros(); // devuelve array de objetos
    }

    // Devuelve UNA persona por su id
    public function getPersonaPorId(int $id): ?object {
        $this->db->query("SELECT * FROM personas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro(); // devuelve un objeto o null
    }

    // Inserta una nueva persona en la BD
    // Usamos instrucciones preparadas para evitar inyección SQL
    public function insertarPersona(string $nombre, string $apellidos, string $telefono, string $email): bool {
        $this->db->query(
            "INSERT INTO personas (nombre, apellidos, telefono, email) 
             VALUES (:nombre, :apellidos, :telefono, :email)"
        );
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':apellidos', $apellidos);
        $this->db->bind(':telefono', $telefono);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    // Elimina una persona por su id
    public function eliminarPersona(int $id): bool {
        $this->db->query("DELETE FROM personas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Devuelve todas las personas como array (para el selector del formulario)
    public function getPersonasParaSelector(): array {
        $this->db->query("SELECT id, nombre, apellidos FROM personas ORDER BY nombre");
        return $this->db->registros();
    }
}
```

---

## 5. Modelo Mascota

Crea el archivo `app/modelos/Mascota.php`:

```php
<?php
namespace Dwes\Clinica;

// Modelo Mascota: gestiona el acceso a la tabla 'mascotas'
class Mascota extends Controlador {

    private Db $db;

    public function __construct() {
        $this->db = new Db();
    }

    // Devuelve todas las mascotas junto con el nombre del dueño (JOIN)
    public function getMascotas(): array {
        $this->db->query(
            "SELECT mascotas.*, personas.nombre AS nombre_duenio, personas.apellidos 
             FROM mascotas 
             JOIN personas ON mascotas.id_persona = personas.id
             ORDER BY mascotas.nombre"
        );
        return $this->db->registros();
    }

    // Devuelve una mascota por id
    public function getMascotaPorId(int $id): ?object {
        $this->db->query("SELECT * FROM mascotas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->registro();
    }

    // Inserta una mascota nueva en la BD
    // $foto_url es la ruta de la imagen subida al servidor
    public function insertarMascota(string $nombre, string $tipo, string $fecha, string $foto_url, int $id_persona): bool {
        $this->db->query(
            "INSERT INTO mascotas (nombre, tipo, fecha_nacimiento, foto_url, id_persona) 
             VALUES (:nombre, :tipo, :fecha_nacimiento, :foto_url, :id_persona)"
        );
        $this->db->bind(':nombre', $nombre);
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':fecha_nacimiento', $fecha);
        $this->db->bind(':foto_url', $foto_url);
        $this->db->bind(':id_persona', $id_persona);
        return $this->db->execute();
    }

    // Elimina una mascota por id (para la API REST)
    public function eliminarMascota(int $id): bool {
        $this->db->query("DELETE FROM mascotas WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
```

---

## 6. Modelo Veterinario (para el login)

Crea el archivo `app/modelos/Veterinario.php`:

```php
<?php
namespace Dwes\Clinica;

// Modelo Veterinario: comprueba credenciales en la BD para el login
class Veterinario extends Controlador {

    private Db $db;

    public function __construct() {
        $this->db = new Db();
    }

    // Comprueba si el email existe en la tabla veterinarios
    // y si la clave coincide. Devuelve el objeto veterinario o null.
    public function login(string $email, string $clave): ?object {
        $this->db->query(
            "SELECT * FROM veterinarios WHERE email = :email AND clave = :clave"
        );
        $this->db->bind(':email', $email);
        $this->db->bind(':clave', $clave);

        $veterinario = $this->db->registro(); // null si no existe

        return $veterinario; // devuelve el objeto si hay coincidencia, null si no
    }
}
```

---

## 7. Controlador Paginas (login, logout, seguridad)

Este es el controlador **principal**. Gestiona el login, logout y la página de inicio. Edita `app/controladores/Paginas.php`:

```php
<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

// Controlador principal: gestiona login, logout y la página de inicio
class Paginas extends Controlador {

    public function __construct() {
        // Iniciamos la sesión en el controlador principal
        // session_start() debe llamarse antes de usar $_SESSION
        session_start();
    }

    // Método que comprueba si el usuario ha iniciado sesión
    // Si no está logueado, redirige al login
    public function logged(): void {
        if (!isset($_SESSION['veterinario_id'])) {
            // Redirigir a la pantalla de login
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit(); // importante: para la ejecución aquí
        }
    }

    // Página de inicio - requiere estar logueado
    public function index(): void {
        $this->logged(); // comprobamos seguridad

        $datos = [
            'titulo' => 'Inicio - ' . NOMBRESITIO,
        ];

        $this->vista('paginas/inicio', $datos);
    }

    // Mostrar formulario de login (GET) y procesar login (POST)
    public function login(): void {
        // Si ya está logueado, no hace falta que vea el login
        if (isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/index');
            exit();
        }

        $error = '';

        // Procesamos el formulario si viene por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Limpiamos los datos (trim elimina espacios al inicio y fin)
            $email = trim($_POST['email'] ?? '');
            $clave = trim($_POST['clave'] ?? '');

            // Validación básica: campos no vacíos
            if (empty($email) || empty($clave)) {
                $error = 'Por favor, rellena todos los campos.';
            } else {
                // Usamos el modelo Veterinario para comprobar en la BD
                $modeloVet = $this->modelo('Veterinario');
                $veterinario = $modeloVet->login($email, $clave);

                if ($veterinario) {
                    // Login correcto: guardamos datos en sesión
                    $_SESSION['veterinario_id']     = $veterinario->id;
                    $_SESSION['veterinario_nombre'] = $veterinario->nombre;

                    // Redirigimos a la página principal
                    header('Location: ' . RUTA_URL . '/paginas/index');
                    exit();
                } else {
                    $error = 'Email o clave incorrectos.';
                }
            }
        }

        $datos = [
            'titulo' => 'Login - ' . NOMBRESITIO,
            'error'  => $error,
        ];

        $this->vista('paginas/login', $datos);
    }

    // Cerrar sesión
    public function logout(): void {
        // Destruimos todos los datos de sesión
        session_unset();
        session_destroy();

        // Redirigimos al login
        header('Location: ' . RUTA_URL . '/paginas/login');
        exit();
    }
}
```

---

## 8. Controlador Personas

Crea `app/controladores/Personas.php`:

```php
<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

// Controlador Personas: gestiona el CRUD de personas (dueños)
class Personas extends Controlador {

    public function __construct() {
        // Comprobamos seguridad: si no está logueado, al login
        session_start();
        if (!isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    // Lista todas las personas
    public function index(): void {
        $modeloPersona = $this->modelo('Persona');
        $personas = $modeloPersona->getPersonas(); // array de objetos

        $datos = [
            'titulo'   => 'Listado de Dueños',
            'personas' => $personas,
        ];

        $this->vista('personas/index', $datos);
    }

    // Muestra la ficha de una persona por su id
    // El id llega como parámetro en la URL: /personas/ficha/3
    public function ficha(int $id): void {
        $modeloPersona = $this->modelo('Persona');
        $persona = $modeloPersona->getPersonaPorId($id);

        $datos = [
            'titulo'  => 'Ficha de Persona',
            'persona' => $persona,
        ];

        $this->vista('personas/ficha', $datos);
    }

    // Muestra el formulario (GET) y procesa el alta (POST)
    public function nueva(): void {
        $error = '';
        $ok    = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // 1ª capa de validación: trim para limpiar espacios
            $nombre    = trim($_POST['nombre']    ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $telefono  = trim($_POST['telefono']  ?? '');
            $email     = trim($_POST['email']     ?? '');

            // Validación PHP: campos obligatorios
            if (empty($nombre) || empty($apellidos)) {
                $error = 'Nombre y apellidos son obligatorios.';
            } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Validamos que el email tenga formato correcto (filter_var de los apuntes)
                $error = 'El email no tiene un formato válido.';
            } elseif (!empty($telefono) && !preg_match('/^[0-9]{9}$/', $telefono)) {
                // Validamos el teléfono con expresión regular (9 dígitos)
                $error = 'El teléfono debe tener 9 dígitos.';
            } else {
                $modeloPersona = $this->modelo('Persona');
                $resultado = $modeloPersona->insertarPersona($nombre, $apellidos, $telefono, $email);

                if ($resultado) {
                    $ok = 'Persona registrada correctamente.';
                } else {
                    $error = 'Error al guardar en la base de datos.';
                }
            }
        }

        $datos = [
            'titulo' => 'Nueva Persona',
            'error'  => $error,
            'ok'     => $ok,
        ];

        $this->vista('personas/nueva', $datos);
    }

    // Elimina una persona por id
    // URL: /personas/eliminar/3
    public function eliminar(int $id): void {
        $modeloPersona = $this->modelo('Persona');
        $modeloPersona->eliminarPersona($id);

        // Después de eliminar, redirigimos al listado
        header('Location: ' . RUTA_URL . '/personas/index');
        exit();
    }
}
```

---

## 9. Controlador Mascotas

Crea `app/controladores/Mascotas.php`:

```php
<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

// Controlador Mascotas: gestiona el alta y listado de mascotas
class Mascotas extends Controlador {

    public function __construct() {
        // Seguridad: solo veterinarios logueados
        session_start();
        if (!isset($_SESSION['veterinario_id'])) {
            header('Location: ' . RUTA_URL . '/paginas/login');
            exit();
        }
    }

    // Lista todas las mascotas con el nombre de su dueño
    public function index(): void {
        $modeloMascota = $this->modelo('Mascota');
        $mascotas = $modeloMascota->getMascotas();

        $datos = [
            'titulo'   => 'Listado de Mascotas',
            'mascotas' => $mascotas,
        ];

        $this->vista('mascotas/index', $datos);
    }

    // Formulario de alta de mascota (GET) y procesa alta (POST)
    public function nueva(): void {
        // Necesitamos la lista de personas para el selector <select>
        $modeloPersona = $this->modelo('Persona');
        $personas = $modeloPersona->getPersonasParaSelector();

        $error = '';
        $ok    = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Limpiamos datos del formulario
            $nombre     = trim($_POST['nombre']     ?? '');
            $tipo       = trim($_POST['tipo']        ?? '');
            $fecha      = trim($_POST['fecha_nacimiento'] ?? '');
            $id_persona = (int)($_POST['id_persona'] ?? 0);

            // Validación PHP
            if (empty($nombre) || empty($tipo) || $id_persona <= 0) {
                $error = 'Nombre, tipo y dueño son obligatorios.';
            } elseif (strlen($nombre) > 50) {
                $error = 'El nombre no puede superar 50 caracteres (límite de la BD).';
            } else {

                // ---- SUBIDA DE IMAGEN ----
                $foto_url = ''; // valor por defecto si no se sube foto

                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {

                    $archivo     = $_FILES['foto'];
                    $extension   = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
                    $permitidas  = ['jpg', 'jpeg', 'png', 'gif'];

                    if (!in_array($extension, $permitidas)) {
                        $error = 'Solo se permiten imágenes JPG, PNG o GIF.';
                    } elseif ($archivo['size'] > 2 * 1024 * 1024) {
                        $error = 'La imagen no puede superar 2MB.';
                    } else {
                        // Nombre único para evitar sobreescribir fotos
                        $nombreArchivo = uniqid('mascota_') . '.' . $extension;
                        $rutaDestino   = __DIR__ . '/../../public/img/' . $nombreArchivo;

                        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                            $foto_url = '/public/img/' . $nombreArchivo;
                        } else {
                            $error = 'Error al subir la imagen al servidor.';
                        }
                    }
                }

                // Solo insertamos si no hubo error en la imagen
                if (empty($error)) {
                    $modeloMascota = $this->modelo('Mascota');
                    $resultado = $modeloMascota->insertarMascota($nombre, $tipo, $fecha, $foto_url, $id_persona);

                    if ($resultado) {
                        $ok = 'Mascota registrada correctamente.';
                    } else {
                        $error = 'Error al guardar en la base de datos.';
                    }
                }
            }
        }

        $datos = [
            'titulo'   => 'Nueva Mascota',
            'personas' => $personas, // para el <select> del formulario
            'error'    => $error,
            'ok'       => $ok,
        ];

        $this->vista('mascotas/nueva', $datos);
    }
}
```

---

## 10. Vistas

### 10.1 Actualizar `app/vistas/inc/header.php`

```php
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= RUTA_URL ?>/css/estilos.css">
    <title><?= NOMBRESITIO ?></title>
</head>
<body>

<nav>
    <?php if (isset($_SESSION['veterinario_id'])): ?>
        <!-- Solo mostramos el menú si el usuario está logueado -->
        <a href="<?= RUTA_URL ?>/paginas/index">Inicio</a> |
        <a href="<?= RUTA_URL ?>/personas/index">Personas</a> |
        <a href="<?= RUTA_URL ?>/mascotas/index">Mascotas</a> |
        <span>Hola, <?= htmlspecialchars($_SESSION['veterinario_nombre']) ?></span> |
        <a href="<?= RUTA_URL ?>/paginas/logout">Cerrar sesión</a>
    <?php endif; ?>
</nav>
<hr>
```

### 10.2 Vista `app/vistas/paginas/inicio.php`

```php
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>
<h2>Bienvenido a la Clínica Veterinaria</h2>

<ul>
    <li><a href="<?= RUTA_URL ?>/personas/index">Ver listado de dueños</a></li>
    <li><a href="<?= RUTA_URL ?>/mascotas/index">Ver listado de mascotas</a></li>
    <li><a href="<?= RUTA_URL ?>/personas/nueva">Registrar nuevo dueño</a></li>
    <li><a href="<?= RUTA_URL ?>/mascotas/nueva">Registrar nueva mascota</a></li>
</ul>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
```

### 10.3 Vista `app/vistas/paginas/login.php`

```php
<?php // No incluimos header completo para la pantalla de login ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - <?= NOMBRESITIO ?></title>
    <link rel="stylesheet" href="<?= RUTA_URL ?>/css/estilos.css">
</head>
<body>

<h1>Login Veterinarios</h1>

<?php if (!empty($error)): ?>
    <!-- Mostramos el error si lo hay -->
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<!-- Formulario de login: action apunta al método login del controlador Paginas -->
<!-- method POST: los datos NO van en la URL (más seguro para contraseñas) -->
<form method="POST" action="<?= RUTA_URL ?>/paginas/login">

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Clave:</label><br>
    <input type="password" name="clave" required><br><br>

    <button type="submit">Entrar</button>
</form>

</body>
</html>
```

### 10.4 Vista `app/vistas/personas/index.php`

```php
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<a href="<?= RUTA_URL ?>/personas/nueva">+ Añadir persona</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Teléfono</th>
        <th>Email</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($personas as $persona): ?>
        <!-- Recorremos el array de objetos que nos devuelve el modelo -->
        <tr>
            <td><?= $persona->id ?></td>
            <td><?= htmlspecialchars($persona->nombre) ?></td>
            <td><?= htmlspecialchars($persona->apellidos ?? '') ?></td>
            <td><?= htmlspecialchars($persona->telefono ?? '') ?></td>
            <td><?= htmlspecialchars($persona->email ?? '') ?></td>
            <td>
                <!-- Enlace a la ficha: pasamos el id como parámetro en la URL -->
                <a href="<?= RUTA_URL ?>/personas/ficha/<?= $persona->id ?>">Ver ficha</a> |
                <!-- Enlace para eliminar -->
                <a href="<?= RUTA_URL ?>/personas/eliminar/<?= $persona->id ?>"
                   onclick="return confirm('¿Seguro que quieres eliminar esta persona?')">
                   Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
```

### 10.5 Vista `app/vistas/personas/ficha.php`

```php
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if ($persona): ?>
    <p><strong>ID:</strong> <?= $persona->id ?></p>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($persona->nombre) ?></p>
    <p><strong>Apellidos:</strong> <?= htmlspecialchars($persona->apellidos ?? '') ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($persona->telefono ?? '') ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($persona->email ?? '') ?></p>
<?php else: ?>
    <p>Persona no encontrada.</p>
<?php endif; ?>

<a href="<?= RUTA_URL ?>/personas/index">← Volver al listado</a>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
```

### 10.6 Vista `app/vistas/personas/nueva.php`

```php
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p style="color:green;"><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<!-- Formulario alta persona: POST al mismo método -->
<form method="POST" action="<?= RUTA_URL ?>/personas/nueva">

    <label>Nombre: *</label><br>
    <input type="text" name="nombre" maxlength="50" required><br><br>

    <label>Apellidos: *</label><br>
    <input type="text" name="apellidos" maxlength="100" required><br><br>

    <label>Teléfono:</label><br>
    <input type="text" name="telefono" maxlength="20"><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" maxlength="100"><br><br>

    <button type="submit">Guardar</button>
    <a href="<?= RUTA_URL ?>/personas/index">Cancelar</a>
</form>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
```

### 10.7 Vista `app/vistas/mascotas/index.php`

```php
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<a href="<?= RUTA_URL ?>/mascotas/nueva">+ Registrar mascota</a>

<table border="1" cellpadding="8">
    <tr>
        <th>Nombre</th>
        <th>Tipo</th>
        <th>F. Nacimiento</th>
        <th>Foto</th>
        <th>Dueño</th>
    </tr>

    <?php foreach ($mascotas as $mascota): ?>
        <tr>
            <td><?= htmlspecialchars($mascota->nombre) ?></td>
            <td><?= htmlspecialchars($mascota->tipo) ?></td>
            <td><?= htmlspecialchars($mascota->fecha_nacimiento ?? '') ?></td>
            <td>
                <?php if (!empty($mascota->foto_url)): ?>
                    <!-- Mostramos la foto si existe -->
                    <img src="<?= htmlspecialchars($mascota->foto_url) ?>" 
                         alt="<?= htmlspecialchars($mascota->nombre) ?>" 
                         style="width:80px; height:80px; object-fit:cover;">
                <?php else: ?>
                    Sin foto
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($mascota->nombre_duenio . ' ' . ($mascota->apellidos ?? '')) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
```

### 10.8 Vista `app/vistas/mascotas/nueva.php`

```php
<?php require_once RUTA_APP . '/vistas/inc/header.php'; ?>

<h1><?= htmlspecialchars($titulo) ?></h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($ok)): ?>
    <p style="color:green;"><?= htmlspecialchars($ok) ?></p>
<?php endif; ?>

<!-- enctype="multipart/form-data" es OBLIGATORIO para subir archivos (de los apuntes) -->
<form method="POST" action="<?= RUTA_URL ?>/mascotas/nueva" enctype="multipart/form-data">

    <label>Nombre: *</label><br>
    <input type="text" name="nombre" maxlength="50" required><br><br>

    <label>Tipo: *</label><br>
    <input type="text" name="tipo" maxlength="30" required><br><br>

    <label>Fecha de nacimiento:</label><br>
    <input type="date" name="fecha_nacimiento"><br><br>

    <!-- Selector de dueño: los datos vienen del modelo Persona -->
    <label>Dueño: *</label><br>
    <select name="id_persona" required>
        <option value="">-- Selecciona un dueño --</option>
        <?php foreach ($personas as $persona): ?>
            <option value="<?= $persona->id ?>">
                <?= htmlspecialchars($persona->nombre . ' ' . ($persona->apellidos ?? '')) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <!-- Campo para subir imagen -->
    <label>Foto de la mascota:</label><br>
    <input type="file" name="foto" accept="image/*"><br><br>

    <button type="submit">Guardar</button>
    <a href="<?= RUTA_URL ?>/mascotas/index">Cancelar</a>
</form>

<?php require_once RUTA_APP . '/vistas/inc/footer.php'; ?>
```

---

## 11. PARTE 2 - API REST

La API REST no usa vistas HTML, responde en JSON. Crea `app/controladores/Api.php`:

```php
<?php
namespace Dwes\Clinica;

use Dwes\Clinica\Controlador;

// Controlador API REST: expone recursos en formato JSON
// Sigue el patrón MVC pero sin vistas HTML
class Api extends Controlador {

    public function __construct() {
        // La API usa autenticación HTTP Basic en lugar de sesiones
        $this->autenticarBasic();
    }

    // Autenticación HTTP Basic
    // El cliente envía usuario:contraseña codificado en Base64 en la cabecera
    private function autenticarBasic(): void {
        // Credenciales definidas para la API (hardcoded según el enunciado)
        $usuario_api = 'apiclinica';
        $clave_api   = 'clinicaapi';

        // Comprobamos si vienen las credenciales en la cabecera HTTP
        $usuario = $_SERVER['PHP_AUTH_USER'] ?? '';
        $clave   = $_SERVER['PHP_AUTH_PW']   ?? '';

        if ($usuario !== $usuario_api || $clave !== $clave_api) {
            // Si no coinciden, devolvemos error 401 (no autorizado)
            http_response_code(401);
            header('WWW-Authenticate: Basic realm="API Clínica Veterinaria"');
            echo json_encode(['error' => 'No autorizado']);
            exit();
        }
    }

    // Enviamos la cabecera JSON - todas las respuestas serán JSON
    private function jsonResponse(array $datos, int $codigo = 200): void {
        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
        exit();
    }

    // GET  /api/personas  -> lista todos los dueños
    // POST /api/personas  -> crea un nuevo dueño
    public function personas(): void {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET') {
            // Devolvemos todas las personas en JSON
            $modeloPersona = $this->modelo('Persona');
            $personas = $modeloPersona->getPersonas();

            // Convertimos los objetos a arrays para json_encode
            $resultado = [];
            foreach ($personas as $p) {
                $resultado[] = (array)$p;
            }

            $this->jsonResponse($resultado, 200);

        } elseif ($metodo === 'POST') {
            // Leemos el cuerpo de la petición (JSON enviado por el cliente)
            $body = json_decode(file_get_contents('php://input'), true);

            $nombre    = trim($body['nombre']    ?? '');
            $apellidos = trim($body['apellidos'] ?? '');
            $telefono  = trim($body['telefono']  ?? '');
            $email     = trim($body['email']     ?? '');

            // Validación básica
            if (empty($nombre) || empty($apellidos)) {
                $this->jsonResponse(['error' => 'Nombre y apellidos son obligatorios'], 400);
            }

            $modeloPersona = $this->modelo('Persona');
            $ok = $modeloPersona->insertarPersona($nombre, $apellidos, $telefono, $email);

            if ($ok) {
                // 201 Created: recurso creado correctamente
                $this->jsonResponse(['mensaje' => 'Dueño creado correctamente'], 201);
            } else {
                $this->jsonResponse(['error' => 'Error al crear el dueño'], 500);
            }

        } else {
            // Método no permitido
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
        }
    }

    // DELETE /api/mascota/{id}  -> elimina la mascota con ese id
    // El id llega como parámetro: /api/mascota/5
    public function mascota(int $id): void {
        $metodo = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'DELETE') {
            // Comprobamos que existe la mascota antes de eliminar
            $modeloMascota = $this->modelo('Mascota');
            $mascota = $modeloMascota->getMascotaPorId($id);

            if (!$mascota) {
                // 404 Not Found: recurso no encontrado
                $this->jsonResponse(['error' => 'Mascota no encontrada'], 404);
            }

            $ok = $modeloMascota->eliminarMascota($id);

            if ($ok) {
                // 200 OK: eliminado correctamente
                $this->jsonResponse(['mensaje' => "Mascota con id $id eliminada correctamente"], 200);
            } else {
                $this->jsonResponse(['error' => 'Error al eliminar la mascota'], 500);
            }

        } else {
            $this->jsonResponse(['error' => 'Método no permitido'], 405);
        }
    }
}
```

---

## 12. Archivo de pruebas API (.http)

Actualiza `api_tests.http` con las pruebas completas:

```http
@baseUrl = http://localhost/mvcrecuperacion/public

### GET - Listar todos los dueños de mascotas
GET {{baseUrl}}/api/personas
Authorization: Basic YXBpY2xpbmljYTpjbGluaWNhYXBp

### POST - Crear un nuevo dueño
POST {{baseUrl}}/api/personas
Authorization: Basic YXBpY2xpbmljYTpjbGluaWNhYXBp
Content-Type: application/json

{
    "nombre": "Pedro",
    "apellidos": "Sánchez",
    "telefono": "611222333",
    "email": "pedro@test.com"
}

### DELETE - Eliminar mascota con id 1
DELETE {{baseUrl}}/api/mascota/1
Authorization: Basic YXBpY2xpbmljYTpjbGluaWNhYXBp
```

> 💡 **El valor de Authorization:** `YXBpY2xpbmljYTpjbGluaWNhYXBp` es `apiclinica:clinicaapi` en Base64. Puedes generarlo con: `echo -n "apiclinica:clinicaapi" | base64`

### Llamada desde el terminal (para la captura de pantalla del examen):

```bash
# GET /api/personas con autenticación Basic
curl -u apiclinica:clinicaapi http://localhost/mvcrecuperacion/public/api/personas
```

---

## 13. Resumen de archivos creados

```
mvcrecuperacion/
├── app/
│   ├── config/
│   │   └── config.php          ← MODIFICADO (datos BD y RUTA_URL)
│   ├── controladores/
│   │   ├── Paginas.php         ← MODIFICADO (login, logout, logged)
│   │   ├── Personas.php        ← CREADO
│   │   ├── Mascotas.php        ← CREADO
│   │   └── Api.php             ← CREADO (API REST)
│   ├── modelos/
│   │   ├── Persona.php         ← CREADO
│   │   ├── Mascota.php         ← CREADO
│   │   └── Veterinario.php     ← CREADO
│   ├── librerias/
│   │   ├── Core.php            ← MODIFICADO (namespace)
│   │   ├── Controlador.php     ← MODIFICADO (namespace)
│   │   └── Db.php              ← MODIFICADO (namespace)
│   └── vistas/
│       ├── inc/
│       │   ├── header.php      ← MODIFICADO (menú de navegación)
│       │   └── footer.php
│       ├── paginas/
│       │   ├── inicio.php      ← MODIFICADO
│       │   └── login.php       ← CREADO
│       ├── personas/
│       │   ├── index.php       ← CREADO
│       │   ├── ficha.php       ← CREADO
│       │   └── nueva.php       ← CREADO
│       └── mascotas/
│           ├── index.php       ← CREADO
│           └── nueva.php       ← CREADO
├── composer.json               ← MODIFICADO (namespace dwes/clinica)
└── api_tests.http              ← MODIFICADO (pruebas completas)
```

---

## ⚠️ Errores más comunes y cómo evitarlos

| Problema | Solución |
|---|---|
| Página en blanco | Activa `display_errors = On` en `php.ini` |
| 404 al navegar | Revisa `RewriteBase` en `public/.htaccess` |
| Error de namespace | Ejecuta `composer dump-autoload` después de cambiar el namespace |
| La sesión no persiste | Asegúrate de que `session_start()` está en el constructor del controlador |
| No sube la imagen | Comprueba que la carpeta `public/img/` tiene permisos de escritura |
| API devuelve HTML en vez de JSON | Revisa que la URL llega al controlador Api y no a otro |
| `session_start()` ya iniciada | Usa `session_status() === PHP_SESSION_NONE` antes de llamar `session_start()` |

---

*Guía elaborada para el examen de recuperación DWES DAW2 - IES Comercio, La Rioja*
