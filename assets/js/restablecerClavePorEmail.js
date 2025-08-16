document.getElementById("formRecuperar").addEventListener("submit", function(event) {
    event.preventDefault();

    const email = document.getElementById("email").value;
    
    fetch("http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=solicitarEnlace", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("mensaje").textContent = data.mensaje;
    })
    .catch(error => console.error("Error:", error));
});

