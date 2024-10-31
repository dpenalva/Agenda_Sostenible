document.addEventListener('DOMContentLoaded', function() {
    // Manejo de la zona de drop para imágenes
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('eventImage');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = imagePreview.querySelector('img');

    // Funciones para el drag & drop
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
            imageInput.files = e.dataTransfer.files;
            updateImagePreview(e.dataTransfer.files[0]);
        }
    });

    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            updateImagePreview(e.target.files[0]);
        }
    });

    function updateImagePreview(file) {
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    }

    // Manejo del formulario de evento
    const createEventBtn = document.getElementById('createEventBtn');
    createEventBtn.addEventListener('click', async () => {
        const formData = new FormData();
        
        // Recoger todos los datos del formulario
        const eventData = {
            title: document.getElementById('eventTitle').value,
            description: document.getElementById('eventDescription').value,
            date: document.getElementById('eventDate').value,
            time: document.getElementById('eventTime').value,
            location: document.getElementById('eventLocation').value,
            capacity: document.getElementById('eventCapacity').value,
            type: document.getElementById('eventType').value,
            image: imageInput.files[0]
        };

        // Validar datos requeridos
        if (!eventData.title || !eventData.date || !eventData.time) {
            alert('Por favor, completa los campos obligatorios (título, fecha y hora)');
            return;
        }

        // Añadir datos al FormData
        Object.keys(eventData).forEach(key => {
            if (eventData[key]) {
                formData.append(key, eventData[key]);
            }
        });

        try {
            const response = await fetch('/api/events/create', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                // Mostrar mensaje de éxito
                alert('Evento creado con éxito');
                // Cerrar el modal y recargar la página
                bootstrap.Modal.getInstance(document.getElementById('createEventModal')).hide();
                location.reload();
            } else {
                throw new Error('Error al crear el evento');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al crear el evento. Por favor, inténtalo de nuevo.');
        }
    });
});
