class Videojuego {
  constructor(titulo, genero, plataforma) {
    this.titulo = titulo;
    this.genero = genero;
    this.plataforma = plataforma;
    this.fechaCreacion = new Date().toLocaleDateString();
  }
}
module.exports = Videojuego;