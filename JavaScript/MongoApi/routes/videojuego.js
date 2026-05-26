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