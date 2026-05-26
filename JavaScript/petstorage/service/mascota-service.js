const { MongoClient, ObjectId } = require("mongodb");

// CONFIGURACIÓN DE CONEXIÓN
const uri = "mongodb://localhost:27017";
const client = new MongoClient(uri);

class MascotaService {
  // GET: Obtener todos los mascotas
  static async getMascotas() {
    try {
      await client.connect();
      const database = client.db("TiendaMascotas"); // Nombre de la BD
      const collection = database.collection("mascotas"); // Nombre de la colección
      const mascotas = await collection.find().toArray();
      return mascotas;
    } catch (error) {
      throw error;
    }
  }

  // POST: Añadir un nuevo mascota
  static async addMascota(mascota) {
    try {
      await client.connect();
      const database = client.db("TiendaMascotas");
      const collection = database.collection("mascotas");
      const result = await collection.insertOne(mascota);
      return result;
    } catch (error) {
      throw error;
    }
  }

  // DELETE: Borrar por ID
  static async deleteMascota(id) {
    try {
      await client.connect();
      const database = client.db("TiendaMascotas");
      const collection = database.collection("mascotas");
      const result = await collection.deleteOne({ _id: new ObjectId(id) });
      return result;
    } catch (error) {
      throw error;
    }
  }
}

module.exports = MascotaService;