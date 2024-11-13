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