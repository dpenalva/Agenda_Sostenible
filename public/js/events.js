window.toggleLike = async function(eventId) {
    try {
        const response = await fetch('?r=toggleEventLike', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ eventId: eventId })
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const data = await response.json();
        console.log('Respuesta toggleLike:', data);
        
        if (data.success) {
            console.log('Calendar instance:', window.calendarInstance);
            
            if (window.calendarInstance) {
                const event = {
                    id: eventId,
                    event_date: data.event.data_esdeveniment,
                    title: data.event.titol,
                    description: data.event.descripcio
                };
                console.log('Evento a añadir/remover:', event);

                if (data.isLiked) {
                    console.log('Añadiendo evento a savedEvents');
                    window.calendarInstance.savedEvents = window.calendarInstance.savedEvents.filter(
                        e => e.id !== event.id
                    );
                    window.calendarInstance.savedEvents.push(event);
                } else {
                    console.log('Removiendo evento de savedEvents');
                    window.calendarInstance.savedEvents = window.calendarInstance.savedEvents.filter(
                        e => e.id !== event.id
                    );
                }
                
                // Guardar en localStorage
                localStorage.setItem('interestedEvents', JSON.stringify(window.calendarInstance.savedEvents));
                console.log('SavedEvents actualizados:', window.calendarInstance.savedEvents);
                
                // Redibujar el calendario
                window.calendarInstance.renderCalendar();
            }

            // Actualizar el botón de like
            const button = document.querySelector(`.like-button[data-event-id="${eventId}"]`);
            if (button) {
                button.classList.toggle('liked');
                const icon = button.querySelector('i.fa-heart');
                if (icon) {
                    icon.style.color = data.isLiked ? '#53d5cd' : '#6c757d';
                }
            }
        }
    } catch (error) {
        console.error('Error en toggleLike:', error);
    }
};

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

    // Funcionalidad del buscador
    const searchInput = document.getElementById('searchEvents');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const eventCards = document.querySelectorAll('.event-card');
            
            eventCards.forEach(card => {
                const title = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
                const description = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
                const date = card.querySelector('.detail:nth-child(1)')?.textContent.toLowerCase() || '';
                const time = card.querySelector('.detail:nth-child(2)')?.textContent.toLowerCase() || '';
                const location = card.querySelector('.detail:nth-child(3)')?.textContent.toLowerCase() || '';
                
                if (title.includes(searchTerm) || 
                    description.includes(searchTerm) || 
                    date.includes(searchTerm) ||
                    time.includes(searchTerm) ||
                    location.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    } else {
        console.error('Search input not found');
    }
});