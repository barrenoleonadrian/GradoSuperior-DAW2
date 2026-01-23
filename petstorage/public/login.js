const API_AUTH = "http://localhost:3000/users";

// Alternar entre Login y Registro
function toggleAuth() {
    const l = document.getElementById("login-section");
    const r = document.getElementById("register-section");
    l.style.display = l.style.display === "none" ? "block" : "none";
    r.style.display = r.style.display === "none" ? "block" : "none";
}

// --- MANEJAR LOGIN ---
document.getElementById("loginForm").addEventListener("submit", async (e) => {
    e.preventDefault();
    const email = document.getElementById("login-email").value;
    const password = document.getElementById("login-pass").value;

    try {
        const res = await fetch(`${API_AUTH}/login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password }),
        });

        if (res.ok) {
            const user = await res.json();
            localStorage.setItem("user", JSON.stringify(user));
            window.location.href = "index.html";
        } else {
            alert("❌ Email o contraseña incorrectos");
        }
    } catch (error) {
        console.error("Error en login:", error);
    }
});

// --- MANEJAR REGISTRO (ESTA ES LA PARTE QUE FALTABA) ---
document.getElementById("registerForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const nombre = document.getElementById("reg-nombre").value;
    const email = document.getElementById("reg-email").value;
    const password = document.getElementById("reg-pass").value;

    try {
        const res = await fetch(`${API_AUTH}/register`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nombre, email, password }),
        });

        const data = await res.json();

        if (res.ok) {
            alert("✅ Usuario creado con éxito. Ahora puedes iniciar sesión.");
            toggleAuth(); // Volver al formulario de login
            document.getElementById("login-email").value = email; // Autocompletar email
        } else {
            alert("❌ Error: " + (data.error || "No se pudo crear el usuario"));
        }
    } catch (error) {
        console.error("Error en registro:", error);
        alert("❌ Error al conectar con el servidor");
    }
});