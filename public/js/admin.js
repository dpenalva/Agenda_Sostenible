document.addEventListener('DOMContentLoaded', function () {
    async function loadUserData(userId) {
        try {
            console.log('Iniciando loadUserData con ID:', userId);
            
            const response = await fetch(`?r=admin/getUser&id=${encodeURIComponent(userId)}`);
            
            // Verificar el tipo de contenido
            console.log('Content-Type:', response.headers.get('Content-Type'));
            
            // Ver la respuesta en texto plano
            const textResponse = await response.text();
            console.log('Respuesta en texto plano:', textResponse);
            
            // Intentar parsear como JSON
            let data;
            try {
                data = JSON.parse(textResponse);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
                throw new Error('La respuesta del servidor no es JSON válido');
            }

            console.log('Datos parseados:', data);

            if (!data.success) {
                throw new Error(data.error || 'Error al cargar los datos del usuario');
            }

            // Rellenar los campos del formulario
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
        if (!confirm('Estás seguro de que deseas eliminar este usuario?')) {
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

    async function loadEventData(eventId) {
        try {
            console.log('Cargando evento:', eventId);
            
            const url = `?r=admin/getEvent&id=${parseInt(eventId, 10)}`;
            console.log('URL de petición:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (!data.success) {
                throw new Error(data.error || 'Error al cargar los datos del evento');
            }

            // Rellenar el formulario con todos los campos
            document.getElementById('editEventId').value = data.event.id;
            document.getElementById('editEventTitle').value = data.event.titol || '';
            document.getElementById('editEventDescription').value = data.event.descripcio || '';
            document.getElementById('editEventDate').value = data.event.data_esdeveniment || '';
            document.getElementById('editEventTime').value = data.event.hora_esdeveniment || '';
            document.getElementById('editEventLatitude').value = data.event.latitud || '';
            document.getElementById('editEventLongitude').value = data.event.longitud || '';
            document.getElementById('editEventVisibility').value = data.event.visibilitat_esdeveniment || '0';

            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('editEventModal'));
            modal.show();

        } catch (error) {
            console.error('Error completo:', error);
            alert('Error al cargar los datos del evento: ' + error.message);
        }
    }

    // Función para guardar cambios del evento
    document.getElementById('saveEventChanges')?.addEventListener('click', async function() {
        try {
            const form = document.getElementById('editEventForm');
            const formData = new FormData(form);
            const eventData = Object.fromEntries(formData.entries());

            console.log('Enviando datos:', eventData); // Para depuración

            const response = await fetch('?r=admin/updateEvent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(eventData)
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                alert('Evento actualizado correctamente');
                window.location.reload();
            } else {
                throw new Error(data.error || 'Error al actualizar el evento');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al guardar los cambios: ' + error.message);
        }
    });

    // Función para eliminar evento
    async function deleteEvent(eventId) {
        if (!confirm('¿Estás seguro de que deseas eliminar este evento?')) {
            return;
        }

        try {
            console.log('Eliminando evento:', eventId); // Para depuración

            const response = await fetch('?r=admin/deleteEvent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: eventId })
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                alert('Evento eliminado correctamente');
                window.location.reload();
            } else {
                throw new Error(data.error || 'Error al eliminar el evento');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al eliminar el evento: ' + error.message);
        }
    }

    // Hacer las funciones disponibles globalmente
    window.loadEventData = loadEventData;
    window.deleteEvent = deleteEvent;

    // Función para crear nuevo evento
    document.getElementById('saveNewEvent')?.addEventListener('click', async function() {
        try {
            const form = document.getElementById('createEventForm');
            const formData = new FormData(form);
            const eventData = Object.fromEntries(formData.entries());

            console.log('Creando nuevo evento:', eventData); // Para depuración

            const response = await fetch('?r=admin/createEvent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(eventData)
            });

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                alert('Evento creado correctamente');
                window.location.reload();
            } else {
                throw new Error(data.error || 'Error al crear el evento');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al crear el evento: ' + error.message);
        }
    });
});