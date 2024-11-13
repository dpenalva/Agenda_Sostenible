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

async function saveEventChanges() {
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
}

async function deleteEvent(eventId) {
    if (!confirm('¿Estás seguro de que deseas eliminar este evento?')) {
        return;
    }

    try {
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

// Función para guardar cambios
function initializeEventHandlers() {
    const saveButton = document.getElementById('saveEventChanges');
    if (saveButton) {
        saveButton.addEventListener('click', async function() {
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
    }
}

// Inicializar los event handlers cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', initializeEventHandlers);

// Hacer las funciones disponibles globalmente
window.loadEventData = loadEventData;
window.deleteEvent = deleteEvent; 
