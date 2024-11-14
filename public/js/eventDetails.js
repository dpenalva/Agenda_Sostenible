document.addEventListener('DOMContentLoaded', function() {
    // Manejar clicks en el botón de like
    const likeButton = document.querySelector('.btn-like');
    if (likeButton) {
        likeButton.addEventListener('click', async function() {
            const eventId = this.dataset.eventId;
            try {
                const response = await fetch(`?r=toggleLike&id=${eventId}`);
                const data = await response.json();
                
                if (data.success) {
                    // Actualizar el estado del botón
                    const heartIcon = this.querySelector('.fa-heart');
                    heartIcon.classList.toggle('text-danger');
                    
                    // Actualizar el contador de likes
                    const likesCount = document.querySelector('.badge .fa-heart').parentElement;
                    likesCount.textContent = `${data.likesCount} likes`;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar tu like');
            }
        });
    }
}); 