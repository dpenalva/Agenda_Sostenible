<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/web.css">
</head>

<body>
    <!-- Botón de menú y overlay deben ser los primeros elementos -->
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="menu-overlay"></div>

    <div class="container-fluid d-flex main-content-wrapper">
        <aside class="sidebar p-4">
        <div class="icon mb-4">
                <img src="/uploads/images/logo1.png" alt="Logo" class="logo1">
            </div>

            <nav class="nav flex-column w-100">
                <a href="/" class="nav-link text-white active"><i class="fas fa-home"></i> Home</a>
                <a href="?r=events" class="nav-link text-white"><i class="fas fa-star"></i> Events</a>
                <a href="?r=profile" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">Cerrar Sesión</button>
        </aside>

        <main class="main-content flex-grow-1 p-4">
            <h2 class="text-white mb-3">Home</h2>

            <!-- Área de creación de evento -->
            <div class="post-creation-area">
                <div class="create-post-button" data-bs-toggle="modal" data-bs-target="#createEventModal">
                    <div class="create-event-wrapper">
                        <div class="create-event-left">
                            <img src="../../public/uploads/images/default-avatar.png" alt="Avatar" class="rounded-circle" style="width: 48px; height: 48px;">
                            <div class="create-event-placeholder">¿Tienes un evento para compartir?</div>
                        </div>
                        <div class="create-event-button">
                            <i class="fas fa-calendar-plus"></i>
                            <span>Crear evento</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feed de eventos -->
            <div class="events-feed mt-4">
                <!-- Los eventos se mostrarán aquí -->
            </div>
        </main>

        <!-- Right Sidebar with Calendar -->
        <aside class="sidebar-right p-4">
            <h4 class="text-white">Calendar</h4>
            <div id="calendar" class="text-white"></div>
        </aside>
    </div>
    <script src="/js/menu.js"></script>
    <script src="/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="/js/calendar.js"></script>
    <script src="/js/events.js"></script>
</body>
</html>
