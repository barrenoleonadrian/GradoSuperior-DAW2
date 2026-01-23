if (!localStorage.getItem("user")){
  window.location.href = "login.html";
}

const API_URL = "http://localhost:3000/mascotas";

function userInfo(){
  const user = localStorage.getItem("user");
  if (user){
    const userObj = JSON.parse(user);
    const userInfoElement = document.getElementById("user-info");
    userInfoElement.innerHTML = '<strong>Usuario:</strong> ${userObj.nombre}';
  }
}

// Cargar videojuegos al iniciar
document.addEventListener("DOMContentLoaded", () => {
  loadPets();
  setupForm();
});

// Cargar todos los videojuegos
async function loadPets() {
  const petsList = document.getElementById("petsList");
  petsList.innerHTML = '<div class="loading">Cargando videojuegos...</div>';

  try {
    const response = await fetch(API_URL);
    const pets = await response.json();

    if (pets.length === 0) {
      petsList.innerHTML =
        '<div class="loading">No hay videojuegos. ¡Agrega uno!</div>';
      return;
    }

    petsList.innerHTML = pets
      .map(
        (pet) => `
            <div class="pet-card">
                <h3>${pet.nombre}</h3>
                <div class="pet-info">
                    <strong>Raza:</strong> ${pet.raza}
                </div>
                <div class="pet-info">
                    <strong>Edad:</strong> ${pet.edad}
                </div>
                <div class="pet-info">
                    <strong>Disponibilidad:</strong> ${pet.disponibilidad}
                </div>
                <div class="pet-id">ID: ${pet._id}</div>
                <button class="delete-btn" onclick="deleteMascota('${pet._id}')">
                    🗑️ Eliminar
                </button>
            </div>
        `,
      )
      .join("");
  } catch (error) {
    console.error("Error al cargar videojuegos:", error);
    petsList.innerHTML =
      '<div class="error">Error al cargar los videojuegos</div>';
  }
}

async function crear(params) {
  
}

function setupForm() {
  const form = document.getElementById("addPetForm");
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombre = document.getElementById("nombre").value;
    console.log (nombre);
    const raza = document.getElementById("raza").value;
    console.log (raza);
    const edad = document.getElementById("edad").value;
    console.log(edad);
    const disponibilidad = document.getElementById("disponibilidad").value;
    console.log (disponibilidad);
    try {
      const response = await fetch(API_URL, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ nombre, raza, edad, disponibilidad }),
      });

      if (response.ok) {
        form.reset();
        loadPets();
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
async function deleteMascota(id) {
  if (!confirm("¿Estás seguro de que quieres eliminar este videojuego?")) {
    return;
  }

  try {
    const response = await fetch(`${API_URL}/${id}`, {
      method: "DELETE",
    });

    if (response.ok) {
      loadPets();
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