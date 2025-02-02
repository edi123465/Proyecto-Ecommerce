// Obtener elementos del DOM
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");
const modal = document.getElementById("createUserModal"); // Modal de creación de usuario
const createUserForm = document.getElementById("createUserForm");

// Mostrar el modal cuando se haga clic en el botón
openModalBtn.onclick = function () {
  console.log("Modal abierto"); // Log para verificar que se abrió el modal
  modal.classList.add("show");
};

// Cerrar el modal cuando se haga clic en la "X"
closeModalBtn.onclick = function () {
  console.log("Modal cerrado por el botón de cierre."); // Log para verificar cierre
  modal.classList.remove("show");
};

// Cerrar el modal si se hace clic fuera del contenido del modal
window.onclick = function (event) {
  if (event.target === modal) {
    console.log("Modal cerrado al hacer clic fuera del contenido."); // Log para confirmar cierre
    modal.classList.remove("show");
  }
};

// Escuchar el evento de la tecla "Escape"
window.addEventListener("keydown", function (event) {
  if (event.key === "Escape") {
    console.log("Modal cerrado por la tecla Escape."); // Log para confirmar cierre con Escape
    modal.classList.remove("show");
  }
});

// Manejo del formulario de creación de usuario
createUserForm.onsubmit = function (event) {
  event.preventDefault(); // Evita el envío por defecto del formulario

  console.log("Formulario enviado."); // Log para confirmar el evento onsubmit

  // Obtener los valores del formulario
  const nombreUsuario = document.getElementById("NombreUsuario").value;
  const email = document.getElementById("Email").value;
  const rol_id = document.getElementById("RolID").value; // Asumiendo que tienes un campo para el rol
  const imagen = document.getElementById("Imagen").files[0]; // Obtener el archivo de imagen (si es que se ha seleccionado)

  // Mostrar valores capturados
  console.log("NombreUsuario:", nombreUsuario);
  console.log("Email:", email);
  console.log("RolID:", rol_id);
  console.log("Imagen seleccionada:", imagen ? imagen.name : "No se seleccionó ninguna imagen");

  // Crear un objeto FormData para enviar los datos incluyendo archivos
  const formData = new FormData();
  formData.append("NombreUsuario", nombreUsuario);
  formData.append("Email", email);
  formData.append("RolID", rol_id);
  if (imagen) {
    formData.append("Imagen", imagen); // Solo se agrega la imagen si se ha seleccionado
  }

  // Enviar los datos al servidor ausando fetch con el parámetro 'action=create'
  fetch("http://localhost:8088/Milogar/Controllers/UsuarioController.php?action=create", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      console.log("Respuesta recibida del servidor:", response); // Verificar la respuesta inicial
      return response.json(); // Convertir la respuesta a JSON
    })
    .then((data) => {
      console.log("Datos procesados del servidor:", data); // Mostrar la respuesta procesada

      if (data.success) {
        // Si la creación fue exitosa, muestra un mensaje y cierra el modal
        console.log("Usuario creado exitosamente:", data.message); // Confirmar éxito
        alert(data.message);
        modal.classList.remove("show");
        // Aquí puedes hacer algo más, como actualizar la lista de usuarios si es necesario
        // Redirigir después de cerrar el modal y mostrar la alerta
        setTimeout(() => {
          window.location.href = "./index.php"; // Cambia la ruta a donde desees redirigir
        }, 500); // Esperar 500ms antes de redirigir para dar tiempo a la alerta
      } else {
        // Si hubo un error al crear el usuario, muestra el mensaje de error
        console.warn("Error en la creación del usuario:", data.message); // Log de advertencia
        alert(data.message);
      }
    })
    .catch((error) => {
      // Manejar cualquier error que ocurra durante la solicitud
      console.error("Error en la solicitud fetch:", error); // Log de error detallado
      alert("Hubo un problema con la solicitud. Intenta nuevamente.");
    });
};
