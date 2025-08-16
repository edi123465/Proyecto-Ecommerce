

// Función para cargar los roles
function mostrarRoles() {
  fetch('http://localhost:8080/Milogar/Controllers/RolController.php?action=getRoles')
      .then(response => response.json())
      .then(data => {
          console.log('Datos recibidos de la API:', data); // Ver la respuesta completa

          if (data.roles && Array.isArray(data.roles)) { // Asegúrate de que 'roles' sea un array
              const selectRol = document.getElementById('crearRol'); // Accede al select correctamente
              selectRol.innerHTML = '<option value="">Selecciona un rol</option>'; // Opción por defecto

              data.roles.forEach(rol => {
                  console.log('Agregando rol:', rol); // Ver cada rol agregado

                  const option = document.createElement('option');
                  option.value = rol.ID; // 'ID' de tu objeto JSON
                  option.textContent = rol.RolName; // 'RolName' del objeto JSON
                  selectRol.appendChild(option);
              });
          } else {
              console.error('No se encontraron roles válidos:', data);
          }
      })
      .catch(error => {
          console.error('Error al cargar los roles:', error);
      });
}

// Cargar los roles cuando se muestra el modal de crear usuario
$('#createUserModal').on('show.bs.modal', function () {
  mostrarRoles(); // Llama a cargarRoles al abrir el modal
});


// Obtener elementos del DOM
const openModalBtn = document.getElementById("openModalBtn");
const closeModalBtn = document.getElementById("closeModalBtn");
const modal = document.getElementById("createUserModal"); // Modal de creación de usuario
const createUserForm = document.getElementById("createUserForm");







const bootstrapModal = new bootstrap.Modal(document.getElementById('createUserModal'));
function guardarNuevoUsuario(event) {
  event.preventDefault(); // Evita el envío del formulario

  console.log("Formulario enviado."); // Confirmar que la función se ejecuta

  // Obtener valores del formulario
  const nombreUsuario = document.getElementById("crearNombreUsuario").value;
  const email = document.getElementById("crearEmail").value;
  const rol_id = document.getElementById("crearRol").value;
  const isActive = document.getElementById("crearIsActive").value;

  // Crear un objeto FormData
  const formData = new FormData();
  formData.append("NombreUsuario", nombreUsuario);
  formData.append("Email", email);
  formData.append("RolID", rol_id);
  formData.append("IsActive", isActive);

  // Enviar los datos con fetch
  fetch("http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=create", {
    method: "POST",
    body: formData,
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
        bootstrapModal.hide();
        setTimeout(() => {
          window.location.href = "./index.php";
        }, 500);
      } else {
        alert(data.message);
      }
    })
    .catch(error => {
      console.error("Error en la solicitud fetch:", error);
    });
}

// Asignar la función al evento `onsubmit` del formulario
document.getElementById("crearUsuarioForm").onsubmit = guardarNuevoUsuario;
