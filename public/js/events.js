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

    // Función para manejar los likes
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
            
            if (data.success) {
                // Si estamos en la página de eventos favoritos
                if (window.location.href.includes('?r=events')) {
                    const eventCard = document.querySelector(`.event-card[data-event-id="${eventId}"]`);
                    if (eventCard) {
                        // Animación de desvanecimiento
                        eventCard.style.transition = 'opacity 0.3s ease';
                        eventCard.style.opacity = '0';
                        setTimeout(() => {
                            eventCard.remove();
                            // Si no quedan eventos, mostrar mensaje
                            const savedEvents = document.querySelector('.saved-events');
                            if (savedEvents && !savedEvents.querySelector('.event-card')) {
                                savedEvents.innerHTML = '<p class="text-muted">No tienes eventos guardados como favoritos.</p>';
                            }
                        }, 300);
                    }
                } else {
                    // Si estamos en otra página (como index.php)
                    const button = document.querySelector(`.like-button[data-event-id="${eventId}"]`);
                    if (button) {
                        button.classList.toggle('liked');
                        const icon = button.querySelector('i.fa-heart');
                        if (icon) {
                            icon.style.color = data.isLiked ? '#53d5cd' : '#6c757d';
                        }
                    }
                }
            } else {
                console.error('Error:', data.message);
                alert(data.message || 'Error al procesar la acción');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al procesar la acción');
        }
    };

    // Función para manejar las calificaciones
    async function rateEvent(eventId, rating) {
        try {
            const response = await fetch('?r=rateEvent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ eventId, rating })
            });

            const data = await response.json();
            
            if (data.success) {
                // Actualizar las estrellas visuales
                updateRatingStars(eventId, rating, data.averageRating);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al calificar el evento');
        }
    }

    // Función para actualizar las estrellas visuales
    function updateRatingStars(eventId, userRating, averageRating) {
        const eventCard = document.querySelector(`.event-card[data-event-id="${eventId}"]`);
        const stars = eventCard.querySelectorAll('.rating-star');
        const averageSpan = eventCard.querySelector('.average-rating');

        stars.forEach((star, index) => {
            star.style.color = index < userRating ? '#ffc107' : '#6c757d';
        });

        if (averageSpan) {
            averageSpan.textContent = `(${averageRating.toFixed(1)})`;
        }
    }

    // Función para actualizar eventos guardados
    function updateSavedEvents(eventId, isLiked) {
        let savedEvents = JSON.parse(localStorage.getItem('savedEvents') || '[]');
        
        if (isLiked) {
            if (!savedEvents.includes(eventId)) {
                savedEvents.push(eventId);
            }
        } else {
            savedEvents = savedEvents.filter(id => id !== eventId);
        }
        
        localStorage.setItem('savedEvents', JSON.stringify(savedEvents));
    }

    // Añadir estilos CSS para el botón de like
    const style = document.createElement('style');
    style.textContent = `
        .like-button {
            border: none;
            background: none;
            cursor: pointer;
            padding: 5px;
            transition: all 0.3s ease;
        }
        .like-button i {
            color: #6c757d;
            font-size: 1.2em;
            transition: all 0.3s ease;
        }
        .like-button.liked i {
            color: #53d5cd;
        }
        .event-card {
            transition: opacity 0.3s ease;
        }
    `;
    document.head.appendChild(style);
});
