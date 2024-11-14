document.addEventListener('DOMContentLoaded', function() {
    // Obtener referencias a los elementos
    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.menu-overlay');

    // Verificar que todos los elementos necesarios existan
    if (!menuToggle || !sidebar || !overlay) {
        console.warn('Elementos del menú no encontrados:', {
            menuToggle: !!menuToggle,
            sidebar: !!sidebar,
            overlay: !!overlay
        });
        return; // Salir si falta algún elemento
    }

    // Función para alternar el menú
    function toggleMenu() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        menuToggle.classList.toggle('active');
        document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
    }

    // Agregar event listeners
    menuToggle.addEventListener('click', function(e) {
        e.preventDefault();
        toggleMenu();
    });

    overlay.addEventListener('click', toggleMenu);

    // Cerrar menú al cambiar el tamaño de la ventana
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992 && sidebar.classList.contains('active')) {
            toggleMenu();
        }
    });
}); 