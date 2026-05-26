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