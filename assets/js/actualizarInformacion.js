document.addEventListener("DOMContentLoaded", function () {
    const openModalButton = document.getElementById("openModalButton");
    const saveChangesButton = document.getElementById("saveChangesButton");

    // Referencias a los campos del formulario
    const userIDField = document.getElementById("user_id"); // Asegúrate de tener este campo oculto en el formulario
    const userNameField = document.getElementById("user_name");
    const emailField = document.getElementById("email");

    // Referencias a los campos en el modal
    const modalUserNameField = document.getElementById("modal_user_name");
    const modalEmailField = document.getElementById("modal_email");

    // Abrir el modal y cargar los datos en los campos del modal
    openModalButton.addEventListener("click", function () {
        modalUserNameField.value = userNameField.value;
        modalEmailField.value = emailField.value;

        // Mostrar el modal
        const modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    });

    // Guardar los cambios y enviar los datos al servidor
    saveChangesButton.addEventListener("click", function () {
        // Obtener los valores actualizados
        const userID = userIDField.value;
        const updatedUserName = modalUserNameField.value;
        const updatedEmail = modalEmailField.value;

        // Mostrar una alerta de confirmación
        const confirmUpdate = window.confirm("¿Estás seguro de que deseas actualizar tus datos?");

        if (confirmUpdate) {
            // Enviar datos al servidor con fetch
            fetch('http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=updateClient', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    userID: userID,
                    nombreUsuario: updatedUserName,
                    email: updatedEmail
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Actualizar los valores en el formulario
                        userNameField.value = updatedUserName;
                        emailField.value = updatedEmail;

                        // Cerrar el modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                        modal.hide();

                        alert("Los datos han sido actualizados correctamente.");
                        location.reload();  // Esto recargará la página y actualizará la sesión con el nuevo nombre de usuario
                    } else {
                        alert("Error al actualizar los datos: " + data.message);
                    }
                })
                .catch(error => console.error("Error:", error));
        } else {

        }
    });
});

function cambiarContrasenia(event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    // Capturamos los valores
    const id = document.getElementById("user_id").value;
    const contrasenia_actual = document.getElementById("contrasenia_actual").value;
    const nueva_contrasenia = document.getElementById("nueva_contrasenia").value;
    const confirmar_contrasenia = document.getElementById("confirmar_contrasenia").value;

    // Expresión regular para validar que la contraseña tenga al menos un carácter especial
    const caracterEspecial = /[!@#$%^&*(),.?":{}|<>]/;

    // Validación de la contraseña
    if (nueva_contrasenia.length < 8) {
        document.getElementById("mensaje").innerText = "La contraseña debe tener al menos 8 caracteres.";
        return;
    }

    if (!caracterEspecial.test(nueva_contrasenia)) {
        document.getElementById("mensaje").innerText = "La contraseña debe contener al menos un carácter especial (!@#$%^&*(),.?\":{}|<>).";
        return;
    }

    if (nueva_contrasenia !== confirmar_contrasenia) {
        document.getElementById("mensaje").innerText = "Las contraseñas no coinciden.";
        return;
    }

    // Enviar datos al backend con fetch()
    fetch("http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=cambiarContrasenia", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: id,
            contrasenia_actual: contrasenia_actual,
            nueva_contrasenia: nueva_contrasenia
        })
    })
        .then(response => response.json()) // Convertimos la respuesta en JSON
        .then(data => {
            document.getElementById("mensaje").innerText = data.mensaje; // Mostramos el mensaje recibido
        })
        .catch(error => {
            console.error("Error:", error);
            document.getElementById("mensaje").innerText = "Error en la solicitud.";
        });
}

document.getElementById("eliminarCuenta").addEventListener("click", function () {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esta acción eliminará tu cuenta y no se podrá recuperar.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, eliminar cuenta",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("http://localhost:8080/Milogar/Controllers/UsuarioController.php?action=eliminarCuenta", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire({
                    title: "Cuenta eliminada",
                    text: data.mensaje,
                    icon: data.success ? "success" : "error",
                    timer: 2000,
                    showConfirmButton: false
                });

                if (data.success) {
                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 2000);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire("Error", "Hubo un problema al eliminar la cuenta.", "error");
            });
        }
    });
});

