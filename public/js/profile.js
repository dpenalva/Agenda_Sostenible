document.addEventListener('DOMContentLoaded', function() {
    const bioDisplay = document.getElementById('bio-display');
    const bioEdit = document.getElementById('bio-edit');
    const bioTextarea = document.getElementById('bio-textarea');
    const editBioBtn = document.getElementById('edit-bio-btn');
    const saveBioBtn = document.getElementById('save-bio');
    const cancelBioBtn = document.getElementById('cancel-bio');

    // Mostrar editor
    editBioBtn.addEventListener('click', function() {
        bioDisplay.style.display = 'none';
        bioEdit.style.display = 'block';
        editBioBtn.style.display = 'none';
        // Cargar el texto actual en el textarea
        bioTextarea.value = bioDisplay.textContent.trim();
        bioTextarea.focus();
    });

    // Guardar cambios
    saveBioBtn.addEventListener('click', function() {
        bioDisplay.textContent = bioTextarea.value;
        bioDisplay.style.display = 'block';
        bioEdit.style.display = 'none';
        editBioBtn.style.display = 'block';
    });

    // Cancelar edición
    cancelBioBtn.addEventListener('click', function() {
        bioDisplay.style.display = 'block';
        bioEdit.style.display = 'none';
        editBioBtn.style.display = 'block';
    });
});
