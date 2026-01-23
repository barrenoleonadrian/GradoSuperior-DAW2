# 🎮 Guía Completa: API REST con MongoDB y Express desde Cero

Esta guía te llevará paso a paso para crear una API REST completa con MongoDB, Express y una interfaz web moderna para gestionar videojuegos.

---

## 📋 Tabla de Contenidos

1. [Requisitos Previos](#requisitos-previos)
2. [Configuración del Entorno](#configuración-del-entorno)
3. [Creación del Proyecto](#creación-del-proyecto)
4. [Configuración de MongoDB con Docker](#configuración-de-mongodb-con-docker)
5. [Estructura del Proyecto](#estructura-del-proyecto)
6. [Implementación del Backend](#implementación-del-backend)
7. [Implementación del Frontend](#implementación-del-frontend)
8. [Pruebas de la API](#pruebas-de-la-api)
9. [Ejecución del Proyecto](#ejecución-del-proyecto)

---

## 1. Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **Node.js** (versión 14 o superior)
- **npm** (viene con Node.js)
- **Docker Desktop** (para ejecutar MongoDB)
- **Editor de código** (VS Code recomendado)
- **REST Client** (extensión de VS Code) o Postman

### Verificar instalaciones

```bash
# Verificar Node.js
node --version

# Verificar npm
npm --version

# Verificar Docker
docker --version
```

---

## 2. Configuración del Entorno

### Paso 1: Crear directorio del proyecto

```bash
# Navegar a tu carpeta de proyectos
cd C:\DAW2\JavaScript\APIS\curso

# Crear carpeta del proyecto
mkdir mongoApi
cd mongoApi
```

---

## 3. Creación del Proyecto

### Paso 1: Inicializar proyecto con Express Generator

```bash
# Instalar express-generator globalmente (si no lo tienes)
npm install -g express

# Generar estructura del proyecto
express  apinombre

# Entrar a la apinombre
cd apinombre

# Instalar dependencias base dentro de la api
npm install
```

### Paso 2: Instalar dependencias adicionales

```bash
# Instalar MongoDB driver nativo
npm install mongodb

# Instalar CORS para permitir peticiones desde el frontend
npm install cors

# Instalar nodemon para desarrollo (reinicio automático)
npm install nodemon --save
```

### Paso 3: Configurar scripts en package.json

Edita el archivo `package.json` y modifica la sección `scripts`:

```json
{
  "name": "mongoapi",
  "version": "0.0.0",
  "private": true,
  "scripts": {
    "start": "nodemon ./bin/www"
  },
  "dependencies": {
    "cookie-parser": "~1.4.4",
    "cors": "^2.8.5",
    "debug": "~2.6.9",
    "express": "~4.16.1",
    "http-errors": "~1.6.3",
    "jade": "~1.11.0",
    "mongodb": "^7.0.0",
    "morgan": "~1.9.1",
    "nodemon": "^3.1.11"
  }
}
```

---

## 4. Configuración de MongoDB con Docker

### Paso 1: Crear contenedor de MongoDB

```bash
# Crear y ejecutar contenedor MongoDB (sin autenticación para desarrollo)
docker run -d --name mongodb-curso -p 27017:27017 mongo:latest
```

**Explicación de los parámetros:**

- `-d`: Ejecuta el contenedor en segundo plano (detached)
- `--name mongodb-curso`: Nombre del contenedor
- `-p 27017:27017`: Mapea el puerto 27017 del contenedor al puerto 27017 de tu máquina
- `mongo:latest`: Imagen de MongoDB (última versión)

### Paso 2: Verificar que MongoDB está corriendo

```bash
# Ver contenedores en ejecución
docker ps

# Ver logs del contenedor
docker logs mongodb-curso
```

### Comandos útiles de Docker para MongoDB

```bash
# Detener el contenedor
docker stop mongodb-curso

# Iniciar el contenedor
docker start mongodb-curso

# Eliminar el contenedor
docker rm mongodb-curso

# Ver todos los contenedores (incluso detenidos)
docker ps -a
```

---

## 5. Estructura del Proyecto

Tu proyecto debe tener la siguiente estructura:

```
mongoApi/
├── bin/
│   └── www                 # Archivo de arranque del servidor
├── models/
│   └── Videojuego.js      # Modelo de datos
├── routes/
│   ├── index.js           # Rutas principales
│   ├── users.js           # Rutas de usuarios (ejemplo)
│   └── videojuego.js      # Rutas de videojuegos
├── service/
│   └── videojuego-service.js  # Lógica de negocio y conexión a MongoDB
├── public/
│   ├── stylesheets/
│   │   └── style.css      # Estilos CSS
│   ├── index.html         # Interfaz web
│   └── main.js            # Lógica del frontend
├── views/                 # Vistas Jade (no las usaremos)
├── app.js                 # Configuración de Express
├── package.json           # Dependencias del proyecto
└── api.http              # Archivo para probar endpoints
```

### Crear carpetas necesarias

```bash
# Crear carpeta models
mkdir models

# Crear carpeta service
mkdir service
```

---

## 6. Implementación del Backend

### Paso 1: Crear el Modelo (models/Videojuego.js)

Crea el archivo `models/Videojuego.js`:

```javascript
class Videojuego {
  constructor(titulo, genero, plataforma) {
    this.titulo = titulo;
    this.genero = genero;
    this.plataforma = plataforma;
    this.fechaCreacion = new Date().toLocaleDateString();
  }
}

module.exports = Videojuego;
```

**Explicación:**

- Define la estructura de un videojuego
- El constructor recibe los datos principales
- `fechaCreacion` se genera automáticamente

---

### Paso 2: Crear el Servicio (service/videojuego-service.js)

Crea el archivo `service/videojuego-service.js`:

```javascript
const { MongoClient, ObjectId } = require("mongodb");

// CONFIGURACIÓN DE CONEXIÓN
const uri = "mongodb://localhost:27017";
const client = new MongoClient(uri);

class VideojuegoService {
  // GET: Obtener todos los videojuegos
  static async getVideojuegos() {
    try {
      await client.connect();
      const database = client.db("TiendaJuegos"); // Nombre de la BD
      const collection = database.collection("videojuegos"); // Nombre de la colección
      const videojuegos = await collection.find().toArray();
      return videojuegos;
    } catch (error) {
      throw error;
    }
  }

  // POST: Añadir un nuevo videojuego
  static async addVideojuego(videojuego) {
    try {
      await client.connect();
      const database = client.db("TiendaJuegos");
      const collection = database.collection("videojuegos");
      const result = await collection.insertOne(videojuego);
      return result;
    } catch (error) {
      throw error;
    }
  }

  // DELETE: Borrar por ID
  static async deleteVideojuego(id) {
    try {
      await client.connect();
      const database = client.db("TiendaJuegos");
      const collection = database.collection("videojuegos");
      const result = await collection.deleteOne({ _id: new ObjectId(id) });
      return result;
    } catch (error) {
      throw error;
    }
  }
}

module.exports = VideojuegoService;
```

**Explicación:**

- **MongoClient**: Cliente nativo de MongoDB
- **ObjectId**: Para trabajar con IDs de MongoDB
- **uri**: Cadena de conexión a MongoDB local
- **Métodos estáticos**: No necesitan instanciar la clase
- **TiendaJuegos**: Nombre de la base de datos (se crea automáticamente)
- **videojuegos**: Nombre de la colección (se crea automáticamente)

---

### Paso 3: Crear las Rutas (routes/videojuego.js)

Crea el archivo `routes/videojuego.js`:

```javascript
var express = require("express");
var router = express.Router();
const VideojuegoService = require("../service/videojuego-service");
const Videojuego = require("../models/Videojuego");

// GET: Listar todos los videojuegos
router.get("/", async function (req, res) {
  try {
    const videojuegos = await VideojuegoService.getVideojuegos();
    return res.status(200).json(videojuegos);
  } catch (error) {
    console.log(error);
    res.status(500).json({ error: "Error interno del servidor" });
  }
});

// POST: Crear un nuevo videojuego
router.post("/", async function (req, res) {
  const { titulo, genero, plataforma } = req.body;

  // Validación
  if (!titulo || !genero || !plataforma) {
    return res.status(400).json({
      error: "Faltan campos obligatorios",
      message: "Por favor envía titulo, genero y plataforma",
    });
  }

  try {
    const videojuego = new Videojuego(titulo, genero, plataforma);
    const result = await VideojuegoService.addVideojuego(videojuego);
    res.status(201).json(result);
  } catch (error) {
    console.log(error);
    res.status(500).json({ error: "Error interno del servidor" });
  }
});

// DELETE: Borrar por ID
router.delete("/:id", async function (req, res) {
  try {
    const result = await VideojuegoService.deleteVideojuego(req.params.id);

    // Verificar si realmente se eliminó algo
    if (result.deletedCount === 0) {
      return res.status(404).json({
        error: "Videojuego no encontrado",
        message: `No se encontró ningún videojuego con el ID: ${req.params.id}`,
      });
    }

    res.status(200).json({
      mensaje: "Videojuego eliminado correctamente",
      deletedCount: result.deletedCount,
    });
  } catch (error) {
    console.log(error);
    res.status(500).json({ error: "Error interno del servidor" });
  }
});

module.exports = router;
```

**Explicación:**

- **GET /**: Devuelve todos los videojuegos
- **POST /**: Crea un nuevo videojuego (con validación)
- **DELETE /:id**: Elimina un videojuego por su ID
- **Status codes**:
  - 200: OK
  - 201: Created
  - 400: Bad Request (datos inválidos)
  - 404: Not Found
  - 500: Internal Server Error

---

### Paso 4: Configurar Express (app.js)

Edita el archivo `app.js` para incluir las rutas de videojuegos:

```javascript
var createError = require("http-errors");
var express = require("express");
var path = require("path");
var cookieParser = require("cookie-parser");
var logger = require("morgan");
var cors = require("cors");

var indexRouter = require("./routes/index");
var usersRouter = require("./routes/users");
var videojuegoRouter = require("./routes/videojuego");

var app = express();

// view engine setup
app.set("views", path.join(__dirname, "views"));
app.set("view engine", "jade");

app.use(logger("dev"));
app.use(express.json());
app.use(express.urlencoded({ extended: false }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, "public")));

app.use("/", indexRouter);
app.use("/users", usersRouter);
app.use("/videojuegos", videojuegoRouter);

// catch 404 and forward to error handler
app.use(function (req, res, next) {
  next(createError(404));
});

// error handler
app.use(function (err, req, res, next) {
  // set locals, only providing error in development
  res.locals.message = err.message;
  res.locals.error = req.app.get("env") === "development" ? err : {};

  // render the error page
  res.status(err.status || 500);
  res.render("error");
});

module.exports = app;
```

**Cambios importantes:**

- Línea 6: Importar `cors`
- Línea 10: Importar las rutas de videojuegos
- Línea 26: Montar las rutas en `/videojuegos`

---

### Paso 5: Crear archivo de pruebas (api.http)

Crea el archivo `api.http` en la raíz del proyecto:

```http
### GET: Listar todos los videojuegos
GET http://localhost:3000/videojuegos

### POST: Crear un nuevo videojuego
POST http://localhost:3000/videojuegos
Content-Type: application/json

{
    "titulo": "The Witcher 3",
    "genero": "RPG",
    "plataforma": "PC"
}

### POST: Crear otro videojuego
POST http://localhost:3000/videojuegos
Content-Type: application/json

{
    "titulo": "God of War",
    "genero": "Acción",
    "plataforma": "PlayStation"
}

### DELETE: Borrar por ID (cambiar el ID por uno real de tu BD)
DELETE http://localhost:3000/videojuegos/AQUI_VA_EL_ID_REAL
```

**Nota:** Para usar este archivo necesitas la extensión **REST Client** en VS Code.

---

## 7. Implementación del Frontend

### Paso 1: Crear la interfaz HTML (public/index.html)

Crea el archivo `public/index.html`:

```html
<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tienda de Videojuegos</title>
    <link rel="stylesheet" href="stylesheets/style.css" />
    <script src="main.js"></script>
  </head>
  <body>
    <div class="container">
      <header>
        <h1>🎮 Tienda de Videojuegos</h1>
        <p>Gestiona tu colección de videojuegos</p>
      </header>

      <!-- Formulario para agregar videojuegos -->
      <section class="add-game-section">
        <h2>Agregar Nuevo Videojuego</h2>
        <form id="addGameForm">
          <input
            type="text"
            id="titulo"
            placeholder="Título del juego"
            required
          />
          <input type="text" id="genero" placeholder="Género" required />
          <input
            type="text"
            id="plataforma"
            placeholder="Plataforma"
            required
          />
          <button type="submit">Agregar Juego</button>
        </form>
      </section>

      <!-- Lista de videojuegos -->
      <section class="games-section">
        <h2>Mis Videojuegos</h2>
        <div id="gamesList" class="games-grid">
          <!-- Los juegos se cargarán aquí dinámicamente -->
        </div>
      </section>
    </div>
  </body>
</html>
```

---

### Paso 2: Crear los estilos CSS (public/stylesheets/style.css)

Reemplaza todo el contenido del archivo `public/stylesheets/style.css`:

```css
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  padding: 20px;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
}

header {
  text-align: center;
  color: white;
  margin-bottom: 40px;
}

header h1 {
  font-size: 3rem;
  margin-bottom: 10px;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

header p {
  font-size: 1.2rem;
  opacity: 0.9;
}

.add-game-section {
  background: white;
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  margin-bottom: 40px;
}

.add-game-section h2 {
  color: #667eea;
  margin-bottom: 20px;
}

#addGameForm {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr auto;
  gap: 15px;
}

#addGameForm input {
  padding: 12px 20px;
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  font-size: 1rem;
  transition: border-color 0.3s;
}

#addGameForm input:focus {
  outline: none;
  border-color: #667eea;
}

#addGameForm button {
  padding: 12px 30px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  transition:
    transform 0.2s,
    box-shadow 0.2s;
}

#addGameForm button:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.games-section h2 {
  color: white;
  margin-bottom: 20px;
  font-size: 2rem;
}

.games-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 25px;
}

.game-card {
  background: white;
  padding: 25px;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
  transition:
    transform 0.3s,
    box-shadow 0.3s;
  position: relative;
}

.game-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.game-card h3 {
  color: #667eea;
  font-size: 1.5rem;
  margin-bottom: 15px;
}

.game-info {
  margin-bottom: 10px;
  color: #555;
}

.game-info strong {
  color: #333;
}

.delete-btn {
  margin-top: 15px;
  padding: 10px 20px;
  background: #ff4757;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s;
  width: 100%;
}

.delete-btn:hover {
  background: #ff3838;
}

.game-id {
  font-size: 0.8rem;
  color: #999;
  margin-top: 10px;
  word-break: break-all;
}

.loading {
  text-align: center;
  color: white;
  font-size: 1.5rem;
  padding: 40px;
}

.error {
  background: #ff4757;
  color: white;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 20px;
}

@media (max-width: 768px) {
  #addGameForm {
    grid-template-columns: 1fr;
  }

  header h1 {
    font-size: 2rem;
  }
}
```

---

### Paso 3: Crear la lógica JavaScript (public/main.js)

Crea el archivo `public/main.js`:

```javascript
const API_URL = "http://localhost:3000/videojuegos";

// Cargar videojuegos al iniciar
document.addEventListener("DOMContentLoaded", () => {
  loadGames();
  setupForm();
});

// Cargar todos los videojuegos
async function loadGames() {
  const gamesList = document.getElementById("gamesList");
  gamesList.innerHTML = '<div class="loading">Cargando videojuegos...</div>';

  try {
    const response = await fetch(API_URL);
    const games = await response.json();

    if (games.length === 0) {
      gamesList.innerHTML =
        '<div class="loading">No hay videojuegos. ¡Agrega uno!</div>';
      return;
    }

    gamesList.innerHTML = games
      .map(
        (game) => `
            <div class="game-card">
                <h3>${game.titulo}</h3>
                <div class="game-info">
                    <strong>Género:</strong> ${game.genero}
                </div>
                <div class="game-info">
                    <strong>Plataforma:</strong> ${game.plataforma}
                </div>
                <div class="game-info">
                    <strong>Fecha:</strong> ${game.fechaCreacion}
                </div>
                <button class="delete-btn" onclick="deleteGame('${game._id}')">
                    🗑️ Eliminar
                </button>
                <div class="game-id">ID: ${game._id}</div>
            </div>
        `,
      )
      .join("");
  } catch (error) {
    console.error("Error al cargar videojuegos:", error);
    gamesList.innerHTML =
      '<div class="error">Error al cargar los videojuegos</div>';
  }
}

// Configurar formulario
function setupForm() {
  const form = document.getElementById("addGameForm");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const titulo = document.getElementById("titulo").value;
    const genero = document.getElementById("genero").value;
    const plataforma = document.getElementById("plataforma").value;

    try {
      const response = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ titulo, genero, plataforma }),
      });

      if (response.ok) {
        form.reset();
        loadGames();
        alert("✅ Videojuego agregado correctamente");
      } else {
        alert("❌ Error al agregar el videojuego");
      }
    } catch (error) {
      console.error("Error:", error);
      alert("❌ Error al conectar con el servidor");
    }
  });
}

// Eliminar videojuego
async function deleteGame(id) {
  if (!confirm("¿Estás seguro de que quieres eliminar este videojuego?")) {
    return;
  }

  try {
    const response = await fetch(`${API_URL}/${id}`, {
      method: "DELETE",
    });

    if (response.ok) {
      loadGames();
      alert("✅ Videojuego eliminado correctamente");
    } else {
      const error = await response.json();
      alert(`❌ ${error.message || "Error al eliminar el videojuego"}`);
    }
  } catch (error) {
    console.error("Error:", error);
    alert("❌ Error al conectar con el servidor");
  }
}
```

**Explicación:**

- **loadGames()**: Carga todos los videojuegos desde la API
- **setupForm()**: Configura el formulario para agregar videojuegos
- **deleteGame(id)**: Elimina un videojuego por su ID
- **fetch API**: Para hacer peticiones HTTP asíncronas

---

## 8. Pruebas de la API

### Opción 1: Usando REST Client (VS Code)

1. Abre el archivo `api.http`
2. Haz clic en "Send Request" sobre cada petición
3. Verás la respuesta en el panel derecho

### Opción 2: Usando la interfaz web

1. Abre el navegador en `http://localhost:3000`
2. Usa el formulario para agregar videojuegos
3. Haz clic en "Eliminar" para borrar videojuegos

### Opción 3: Usando Postman

1. Crea una nueva colección
2. Agrega las peticiones GET, POST y DELETE
3. Configura los headers y body según sea necesario

---

## 9. Ejecución del Proyecto

### Paso 1: Asegurarse de que MongoDB está corriendo

```bash
# Verificar que el contenedor está activo
docker ps

# Si no está corriendo, iniciarlo
docker start mongodb-curso
```

### Paso 2: Iniciar el servidor

```bash
# Desde la carpeta del proyecto
npm start
```

Deberías ver:

```
[nodemon] 3.1.11
[nodemon] to restart at any time, enter `rs`
[nodemon] watching path(s): *.*
[nodemon] watching extensions: js,mjs,cjs,json
[nodemon] starting `node ./bin/www`
```

### Paso 3: Abrir la aplicación

Abre tu navegador y ve a:

```
http://localhost:3000
```

---

## 🎯 Resumen de Endpoints

| Método | Endpoint           | Descripción                   | Body                             |
| ------ | ------------------ | ----------------------------- | -------------------------------- |
| GET    | `/videojuegos`     | Obtener todos los videojuegos | -                                |
| POST   | `/videojuegos`     | Crear un nuevo videojuego     | `{ titulo, genero, plataforma }` |
| DELETE | `/videojuegos/:id` | Eliminar un videojuego por ID | -                                |

---

## 🔧 Solución de Problemas Comunes

### Error: "Port 3000 is already in use"

**Solución:**

```bash
# En Windows PowerShell
Get-Process -Id (Get-NetTCPConnection -LocalPort 3000).OwningProcess | Stop-Process -Force
```

### Error: "MongoServerError: connect ECONNREFUSED"

**Solución:**

```bash
# Verificar que MongoDB está corriendo
docker ps

# Iniciar MongoDB si no está corriendo
docker start mongodb-curso
```

### Error: "Cannot GET /"

**Solución:**

- Asegúrate de que el archivo `index.html` está en la carpeta `public`
- Verifica que Express está configurado para servir archivos estáticos

---

## 📚 Conceptos Clave Aprendidos

1. **API REST**: Arquitectura para servicios web
2. **CRUD**: Create, Read, Update, Delete
3. **MongoDB**: Base de datos NoSQL orientada a documentos
4. **Express**: Framework web para Node.js
5. **Async/Await**: Manejo de operaciones asíncronas
6. **Docker**: Contenedorización de aplicaciones
7. **Fetch API**: Peticiones HTTP desde el navegador
8. **Patrón MVC**: Modelo-Vista-Controlador (adaptado)

---

## 🚀 Próximos Pasos

1. **Agregar más endpoints**: PUT para actualizar videojuegos
2. **Validación avanzada**: Usar bibliotecas como Joi o express-validator
3. **Autenticación**: Implementar JWT para proteger la API
4. **Paginación**: Limitar resultados en GET
5. **Búsqueda y filtros**: Buscar por género, plataforma, etc.
6. **Testing**: Implementar pruebas con Jest o Mocha
7. **Deploy**: Subir a Heroku, Railway o Render

---

## 📖 Recursos Adicionales

- [Documentación de MongoDB](https://www.mongodb.com/docs/)
- [Express.js Guide](https://expressjs.com/es/guide/routing.html)
- [Docker Documentation](https://docs.docker.com/)
- [MDN Web Docs - Fetch API](https://developer.mozilla.org/es/docs/Web/API/Fetch_API)

---

**¡Felicidades! 🎉 Has creado una API REST completa con MongoDB y Express desde cero.**
