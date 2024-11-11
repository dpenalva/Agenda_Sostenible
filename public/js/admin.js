document.addEventListener('DOMContentLoaded', function () {
    async function loadUserData(userId) {
        try {
            if (!userId) {
                throw new Error('ID de usuario no proporcionado');
            }

            console.log('Cargando datos para usuario ID:', userId);

            const response = fetch(`?r=admin/getUser&id=${encodeURIComponent(userId)}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('userId').value = data.user.id;
                    document.getElementById('userNom').value = data.user.nom || '';
                    document.getElementById('userCognoms').value = data.user.cognoms || '';
                    document.getElementById('userNomUsuari').value = data.user.nom_usuari || '';
                    document.getElementById('userEmail').value = data.user.email || '';
                    document.getElementById('userBiografia').value = data.user.biografia || '';
                    document.getElementById('userRol').value = data.user.rol || 'user';

                    // Mostrar el modal
                    const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    editModal.show();
                });

        } catch (error) {
            console.error('Error completo:', error);
            alert('Error al cargar los datos del usuario: ' + error.message);
        }
    }

    // Añadir el evento click al botón de guardar
    document.getElementById('saveUserChanges').addEventListener('click', async function() {
        try {
            const userData = {
                id: document.getElementById('userId').value,
                nom: document.getElementById('userNom').value,
                cognoms: document.getElementById('userCognoms').value,
                nom_usuari: document.getElementById('userNomUsuari').value,
                email: document.getElementById('userEmail').value,
                biografia: document.getElementById('userBiografia').value,
                rol: document.getElementById('userRol').value
            };

            const response = await fetch('?r=admin/updateUser', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (data.success) {
                // Cerrar el modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                modal.hide();
                
                // Recargar la página para ver los cambios
                window.location.reload();
            } else {
                throw new Error(data.error || 'Error al actualizar el usuario');
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Error al guardar los cambios: ' + error.message);
        }
    });

    async function deleteUser(userId) {
        if (!confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
            return;
        }

        try {
            const response = await fetch('?r=admin/deleteUser', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: userId })
            });

            const data = await response.json();

            if (data.success) {
                // Recargar la página para ver los cambios
                window.location.reload();
            } else {
                throw new Error(data.error || 'Error al eliminar el usuario');
            }

        } catch (error) {
            console.error('Error:', error);
            alert('Error al eliminar el usuario: ' + error.message);
        }
    }

    // Hacer la función deleteUser disponible globalmente
    window.deleteUser = deleteUser;

    // Hacer la función loadUserData disponible globalmente
    window.loadUserData = loadUserData;

    // Manejador para añadir nuevo usuario
    document.getElementById('saveNewUser')?.addEventListener('click', async function(e) {
        e.preventDefault();
        try {
            const form = document.getElementById('addUserForm');


            const formData = new FormData(form);
            const userData = Object.fromEntries(formData.entries());

            const response = await fetch('?r=admin/addUser', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            });

            const data = await response.json();

            if (data.success) {
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                modal.hide();
                
                // Limpiar formulario
                form.reset();
                
                // Mostrar mensaje de éxito
                alert('Usuario creado correctamente');
                
                // Recargar página
                window.location.reload();
            } else {
                // Mostrar mensaje de error
                throw new Error(data.error || 'Error al crear el usuario');
            }
        } catch (error) {
            console.error('Error:', error);
            alert(error.message);
        }
    });
});