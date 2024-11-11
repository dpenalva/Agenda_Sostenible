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

    // Hacer la función loadUserData disponible globalmente
    window.loadUserData = loadUserData;
});