var express = require('express');
var router = express.Router();
// Si usas Mongoose, deberías importar tu modelo aquí:
// const User = require('../models/User'); 

/* GET users listing. */
router.get('/', function(req, res, next) {
  res.send('respond with a resource');
});

// --- RUTA DE REGISTRO ---
router.post('/register', async function(req, res) {
  try {
    const { nombre, email, password } = req.body;
    
    // Aquí iría tu lógica de MongoDB (ejemplo con Mongoose):
    // const nuevoUsuario = new User({ nombre, email, password });
    // await nuevoUsuario.save();

    console.log("Registrando a:", nombre); // Para que lo veas en la consola de Node
    
    res.status(201).json({ message: "Usuario creado correctamente", nombre });
  } catch (error) {
    res.status(500).json({ error: "Error al guardar en la base de datos" });
  }
});

// --- RUTA DE LOGIN ---
router.post('/login', async function(req, res) {
  try {
    const { email, password } = req.body;
    
    // Aquí buscarías en Mongo:
    // const user = await User.findOne({ email, password });

    // Simulando que el usuario existe para que pruebes el front:
    res.json({ nombre: "Usuario de Prueba", email: email });
  } catch (error) {
    res.status(500).json({ error: "Error en el servidor" });
  }
});

module.exports = router;