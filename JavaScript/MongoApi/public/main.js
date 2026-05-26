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