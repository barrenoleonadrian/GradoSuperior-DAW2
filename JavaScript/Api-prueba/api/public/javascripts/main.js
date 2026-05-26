const API_URL = "http://localhost:3000/motos";

document.addEventListener("DOMContentLoaded", () => {
    loadMotos();
    setupForm();
});

async function loadMotos() {
    const motosList = document.getElementById("motosList");
    motosList.innerHTML = '<div class="loading">Cargando lista...</div>';

    try {
        const response = await fetch(API_URL);
        const motos = await response.json();

        if (motos.length === 0) {
            motosList.innerHTML = '<div class="loading">No hay ninguna moto brumbrum</div>';
            return;
        }

        motosList.innerHTML = motos
            .map(
                (moto) => `
                    <div class="motos-card">
                        <h3>Lista de motos</h3>
                        <p>Marca: ${moto.marca}</p>
                        <p>Modelo: ${moto.modelo}</p>
                        <p>Caballos: ${moto.caballos}</p>
                    </div>
                `
            ).join('');
    } catch (error) {
        console.error("Error al cargar motos:", error);
        motosList.innerHTML = '<div>Error al cargar los datos.</div>';
    }
}