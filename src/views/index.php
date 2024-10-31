<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/web.css">
</head>

<body>
    <!-- Botón de menú y overlay deben ser los primeros elementos -->
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="menu-overlay"></div>
    <!-- Modal de Inicio de Sesión -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-0">
                    <h3 class="fw-bold mx-auto">Inicia sesión</h3>
                </div>
                <div class="modal-body text-center">
                    <input type="text" id="username-input" class="form-control bg-dark text-white border-secondary mb-3" placeholder="Teléfono, correo electrónico o nombre de usuario" style="border-radius: 5px;">
                    <button id="btn-siguiente" class="btn btn-light w-100 mb-3" style="border-radius: 25px;">Siguiente</button>
                    <a href="#" class="text-decoration-none text-primary">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="modal-footer border-0 text-center d-block">
                    <p class="mb-0 text-light">¿No tienes una cuenta? <a href="#" class="text-primary">Regístrate</a></p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid d-flex main-content-wrapper">
        <aside class="sidebar p-4 d-flex flex-column align-items-center">
        <div class="icon mb-4">
            <img src="../../public/uploads/images/logo1.png" alt="Logo" class="logo1">
        </div>

            <nav class="nav flex-column w-100">
                <a href="/src/views/index.php" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
                <a href="/src/views/events.php" class="nav-link text-white"><i class="fas fa-star"></i> Events</a>
                <a href="/src/views/profile.php" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100">Cerrar Sesión</button>
        </aside>

        <main class="main-content flex-grow-1 p-4">
            <h2 class="text-white mb-3">Home</h2>

            <!-- Área de creación de evento rediseñada -->
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

            <!-- Modal para crear evento -->
            <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title" id="createEventModalLabel">Crear Evento</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex mb-3">
                                <img src="../../public/uploads/images/default-avatar.png" alt="Avatar" class="rounded-circle me-2" style="width: 48px; height: 48px;">
                                <div class="flex-grow-1">
                                    <input type="text" class="form-control bg-dark text-white border-0" id="eventTitle" placeholder="Título del evento" style="font-size: 1.2em;">
                                </div>
                            </div>
                            
                            <div class="event-details">
                                <!-- Descripción -->
                                <div class="mb-3">
                                    <textarea class="form-control bg-dark text-white border-secondary" id="eventDescription" rows="3" placeholder="Describe tu evento..."></textarea>
                                </div>

                                <div class="row mb-3">
                                    <!-- Fecha -->
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark border-secondary">
                                                <i class="fas fa-calendar"></i>
                                            </span>
                                            <input type="date" class="form-control bg-dark text-white border-secondary" id="eventDate">
                                        </div>
                                    </div>
                                    <!-- Hora -->
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark border-secondary">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <input type="time" class="form-control bg-dark text-white border-secondary" id="eventTime">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Ubicación -->
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark border-secondary">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <input type="text" class="form-control bg-dark text-white border-secondary" id="eventLocation" placeholder="Ubicación">
                                        </div>
                                    </div>
                                    <!-- Capacidad -->
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text bg-dark border-secondary">
                                                <i class="fas fa-users"></i>
                                            </span>
                                            <input type="number" class="form-control bg-dark text-white border-secondary" id="eventCapacity" placeholder="Capacidad máxima">
                                        </div>
                                    </div>
                                </div>

                                <!-- Tipo de evento -->
                                <div class="mb-3">
                                    <select class="form-select bg-dark text-white border-secondary" id="eventType">
                                        <option value="" disabled selected>Selecciona el tipo de evento</option>
                                        <option value="presencial">Presencial</option>
                                        <option value="virtual">Virtual</option>
                                        <option value="hibrido">Híbrido</option>
                                    </select>
                                </div>

                                <!-- Área para subir imagen -->
                                <div class="mb-3">
                                    <div class="drop-zone bg-dark border border-secondary rounded p-4 text-center" id="dropZone">
                                        <i class="fas fa-image mb-2"></i>
                                        <p class="mb-0">Arrastra una imagen o haz clic para seleccionar</p>
                                        <input type="file" id="eventImage" class="d-none" accept="image/*">
                                    </div>
                                    <div id="imagePreview" class="mt-2 d-none">
                                        <img src="" alt="Preview" class="img-fluid rounded">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="createEventBtn">Publicar evento</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección donde aparecerán los posts creados -->
            <div id="posts-container"></div>
        </main>

        <!-- Right Sidebar with Calendar -->
        <aside class="sidebar-right p-4">
            <h4 class="text-white">Calendar</h4>
            <div id="calendar" class="text-white"></div>
        </aside>
    </div>
    <script src="../../public/js/menu.js"></script>
    <script src="../../public/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../../public/js/calendar.js"></script>
</body>
</html>
