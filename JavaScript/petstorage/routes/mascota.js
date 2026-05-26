var express = require("express");
var router = express.Router();
const MascotaService = require("../service/mascota-service");
const Mascota = require("../models/Mascota");

// GET: Listar todos los mascotas
router.get("/", async function (req, res) {
  try {
    const mascotas = await MascotaService.getMascotas();
    return res.status(200).json(mascotas);
  } catch (error) {
    console.log(error);
    res.status(500).json({ error: "Error interno del servidor" });
  }
});

// POST: Crear un nuevo mascota
router.post("/", async function (req, res) {
  const { nombre, raza, edad, disponibilidad } = req.body;

  // Validación
  if (!nombre || !raza || !edad || !disponibilidad) {
    return res.status(400).json({
      error: "Faltan campos obligatorios",
      message: "Por favor envía nombre, raza, edad y disponibilidad",
    });
  }

  try {
    const mascota = new Mascota(nombre, raza, edad, disponibilidad);
    const result = await MascotaService.addMascota(mascota);
    res.status(201).json(result);
  } catch (error) {
    console.log(error);
    res.status(500).json({ error: "Error interno del servidor" });
  }
});

// DELETE: Borrar por ID
router.delete("/:id", async function (req, res) {
  try {
    const result = await MascotaService.deleteMascota(req.params.id);

    // Verificar si realmente se eliminó algo
    if (result.deletedCount === 0) {
      return res.status(404).json({
        error: "Mascota no encontrado",
        message: `No se encontró ningún mascota con el ID: ${req.params.id}`,
      });
    }

    res.status(200).json({
      mensaje: "Mascota eliminado correctamente",
      deletedCount: result.deletedCount,
    });
  } catch (error) {
    console.log(error);
    res.status(500).json({ error: "Error interno del servidor" });
  }
});

module.exports = router;