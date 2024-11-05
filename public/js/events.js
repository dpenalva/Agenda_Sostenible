document.addEventListener('DOMContentLoaded', function() {
    const eventForm = document.getElementById('createEventForm');
    const dropZone = document.querySelector('.drop-zone');
    const imageInput = document.getElementById('eventImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview.querySelector('img');

    // Manejar drag & drop
    dropZone.addEventListener('click', () => imageInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            handleImageUpload(e.dataTransfer.files[0]);
        }
    });

    // Manejar selección de archivo
    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleImageUpload(e.target.files[0]);
        }
    });

    // Función para manejar la imagen
    function handleImageUpload(file) {
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
                dropZone.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    }

    // Manejar envío del formulario
    eventForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Validar que haya una imagen
        if (!previewImg.src) {
            alert('Por favor, selecciona una imagen para el evento');
            return;
        }
        
        const eventData = {
            title: document.getElementById('eventTitle').value,
            description: document.getElementById('eventDescription').value,
            event_date: document.getElementById('eventDate').value,
            event_time: document.getElementById('eventTime').value,
            location: document.getElementById('eventLocation').value,
            capacity: document.getElementById('eventCapacity').value,
            event_type: document.getElementById('eventType').value,
            image_url: previewImg.src,
            username: 'Usuario Actual',
            created_at: new Date().toISOString()
        };

        // Crear y añadir la tarjeta del evento
        const eventCard = createEventCard(eventData);
        const eventsFeed = document.querySelector('.events-feed');
        
        if (eventsFeed.firstChild) {
            eventsFeed.insertBefore(eventCard, eventsFeed.firstChild);
        } else {
            eventsFeed.appendChild(eventCard);
        }

        // Cerrar modal y limpiar formulario
        const modal = bootstrap.Modal.getInstance(document.getElementById('createEventModal'));
        modal.hide();
        eventForm.reset();
        imagePreview.classList.add('d-none');
        dropZone.style.display = 'block';
    });

    function createEventCard(event) {
        console.log('Evento recibido:', event);
        console.log('Fecha del evento:', event.event_date);
        console.log('Tipo de fecha:', typeof event.event_date);

        const card = document.createElement('div');
        card.className = 'event-card';
        
        const formattedDate = new Date(event.event_date).toLocaleDateString('es-ES', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });

        card.innerHTML = `
            <div class="event-card-content">
                <div class="event-header">
                    <img src="../../public/uploads/images/default-avatar.png" 
                         alt="Avatar" 
                         class="rounded-circle" 
                         style="width: 40px; height: 40px; object-fit: cover;">
                    <div class="event-author">
                        <strong class="text-white">${event.username}</strong>
                        <span class="text-muted small">ha creado un evento</span>
                    </div>
                </div>
                
                ${event.image_url ? `
                    <div class="event-image">
                        <img src="${event.image_url}" 
                             alt="${event.title}" 
                             class="event-main-image">
                    </div>
                ` : ''}
                
                <div class="event-details">
                    <h3 class="event-title">${event.title}</h3>
                    <p class="event-description">${event.description || ''}</p>
                    
                    <div class="event-info">
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>${formattedDate}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <span>${event.event_time}</span>
                        </div>
                        ${event.location ? `
                            <div class="info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>${event.location}</span>
                            </div>
                        ` : ''}
                        ${event.capacity ? `
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span>${event.capacity} personas</span>
                            </div>
                        ` : ''}
                        <div class="info-item">
                            <i class="fas fa-tag"></i>
                            <span>${event.event_type}</span>
                        </div>
                    </div>
                    
                    <div class="event-actions mt-3">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-star"></i> Me interesa
                        </button>
                        <button class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-share"></i> Compartir
                        </button>
                    </div>
                </div>
            </div>
        `;

        const interestButton = card.querySelector('.btn-outline-primary');
        
        // Siempre empezar sin marcar
        interestButton.classList.remove('active');
        interestButton.style.backgroundColor = '';
        interestButton.style.color = '';

        interestButton.addEventListener('click', function() {
            const isInterested = this.classList.contains('active');
            
            if (!isInterested) {
                // Marcar como interesado
                this.classList.add('active');
                this.style.backgroundColor = '#53d5cd';
                this.style.color = '#15181c';
                
                saveEventToCalendar({
                    id: event.id,
                    title: event.title,
                    event_date: event.event_date,
                    event_time: event.event_time
                });
            } else {
                // Desmarcar interés
                this.classList.remove('active');
                this.style.backgroundColor = '';
                this.style.color = '';
                
                removeEventFromCalendar(event.id);
            }
        });

        return card;
    }

    // Funciones auxiliares para manejar eventos en el calendario
    function saveEventToCalendar(event) {
        // Asegurarnos de que la fecha está en el formato correcto
        let eventToSave = {
            ...event,
            event_date: new Date(event.event_date).toISOString().split('T')[0]
        };

        console.log('Guardando evento formateado:', eventToSave);

        let savedEvents = JSON.parse(localStorage.getItem('interestedEvents') || '[]');
        if (!savedEvents.some(e => e.id === eventToSave.id)) {
            savedEvents.push(eventToSave);
            localStorage.setItem('interestedEvents', JSON.stringify(savedEvents));
            console.log('Estado actual del localStorage:', localStorage.getItem('interestedEvents'));
        }

        // Forzar actualización del calendario
        if (window.calendarInstance) {
            window.calendarInstance.loadSavedEvents();
            window.calendarInstance.renderCalendar();
            console.log('Calendario actualizado');
        } else {
            console.log('No se encontró instancia del calendario');
        }
    }

    function removeEventFromCalendar(eventId) {
        let savedEvents = JSON.parse(localStorage.getItem('interestedEvents') || '[]');
        savedEvents = savedEvents.filter(e => e.id !== eventId);
        console.log('Eventos después de eliminar:', savedEvents);
        localStorage.setItem('interestedEvents', JSON.stringify(savedEvents));
        
        if (window.calendarInstance) {
            window.calendarInstance.loadSavedEvents();
            window.calendarInstance.renderCalendar();
        }
    }

    // Actualizar el estilo del dropZone para indicar que es obligatorio
    dropZone.style.borderColor = '#53d5cd';
});
