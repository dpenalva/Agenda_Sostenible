document.addEventListener('DOMContentLoaded', function() {
    // Función para crear eventos
    function initializeEventCreation() {
        const createEventForm = document.getElementById('createEventForm');
        const saveEventButton = document.getElementById('saveEventButton');

        if (!createEventForm || !saveEventButton) {
            console.log('Elementos del formulario no encontrados');
            return;
        }

        saveEventButton.addEventListener('click', async function(e) {
            e.preventDefault();

            if (!createEventForm.checkValidity()) {
                createEventForm.reportValidity();
                return;
            }

            // Recoger los datos del formulario
            const formData = new FormData(createEventForm);
            const eventData = {};

            // Convertir FormData a un objeto plano
            formData.forEach((value, key) => {
                eventData[key] = value;
            });

            // Manejar específicamente el checkbox
            eventData.visibilitat_esdeveniment = document.getElementById('visibilitat_esdeveniment').checked;

            try {
                const response = await fetch('?r=createEvent', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(eventData)
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    // Cerrar el modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createEventModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Recargar la página para mostrar el nuevo evento
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al crear el evento');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            }
        });
    }

    // Inicializar la creación de eventos
    initializeEventCreation();
});
