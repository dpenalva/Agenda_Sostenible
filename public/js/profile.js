document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editPhotoBtn = document.querySelector('.edit-photo-btn');
    const editBannerBtn = document.querySelector('.edit-banner-btn');
    const profileImageInput = document.getElementById('profileImageInput');
    const bannerInput = document.getElementById('bannerInput');
    const profileEditForm = document.getElementById('profileEditForm');

    // Manejador para el botón de foto de perfil
    editPhotoBtn.addEventListener('click', () => {
        console.log('Clic en editar foto');
        profileImageInput.click();
    });

    // Manejador para el botón de banner
    editBannerBtn.addEventListener('click', () => {
        console.log('Clic en editar banner');
        bannerInput.click();
    });

    // Manejador para cambios en la imagen de perfil
    profileImageInput.addEventListener('change', async function() {
        console.log('Cambio en input de perfil');
        if (this.files && this.files[0]) {
            const formData = new FormData();
            formData.append('profileImage', this.files[0]);

            try {
                const response = await fetch('?r=updateProfileImage', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    document.querySelector('.profile-image').src = data.imageUrl + '?v=' + new Date().getTime();
                } else {
                    alert(data.message || 'Error al actualizar la imagen');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            }
        }
    });

    // Manejador para cambios en el banner
    bannerInput.addEventListener('change', async function() {
        console.log('Cambio en input de banner');
        if (this.files && this.files[0]) {
            const formData = new FormData();
            formData.append('bannerImage', this.files[0]);

            try {
                const response = await fetch('?r=updateBanner', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                if (data.success) {
                    document.querySelector('.banner-image').src = data.imageUrl + '?v=' + new Date().getTime();
                } else {
                    alert(data.message || 'Error al actualizar el banner');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error al procesar la solicitud');
            }
        }
    });

    // Manejador para el formulario de edición
    profileEditForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Enviando formulario de edición');

        const formData = {
            name: document.getElementById('editName').value,
            bio: document.getElementById('editBio').value
        };

        try {
            const response = await fetch('?r=updateProfile', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();
            if (data.success) {
                // Actualizar la información en la página
                document.getElementById('displayName').textContent = formData.name;
                document.getElementById('displayBio').textContent = formData.bio || 'No hay biografía aún...';
                
                // Cerrar el modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                modal.hide();
            } else {
                alert(data.message || 'Error al actualizar el perfil');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        }
    });
});
