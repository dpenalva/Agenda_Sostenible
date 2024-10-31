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

    <!-- Modal para crear evento (añadir después del loginModal, antes del container-fluid) -->
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="createEventModalLabel">Crear Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createEventForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Título -->
                        <div class="mb-3">
                            <label for="eventTitle" class="form-label">Título del evento *</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="eventTitle" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label for="eventDescription" class="form-label">Descripción</label>
                            <textarea class="form-control bg-dark text-white border-secondary" id="eventDescription" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <!-- Fecha -->
                            <div class="col-md-6 mb-3">
                                <label for="eventDate" class="form-label">Fecha *</label>
                                <input type="date" class="form-control bg-dark text-white border-secondary" id="eventDate" required>
                            </div>
                            <!-- Hora -->
                            <div class="col-md-6 mb-3">
                                <label for="eventTime" class="form-label">Hora *</label>
                                <input type="time" class="form-control bg-dark text-white border-secondary" id="eventTime" required>
                            </div>
                        </div>

                        <!-- Ubicación -->
                        <div class="mb-3">
                            <label for="eventLocation" class="form-label">Ubicación</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="eventLocation">
                        </div>

                        <!-- Capacidad -->
                        <div class="mb-3">
                            <label for="eventCapacity" class="form-label">Capacidad máxima</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" id="eventCapacity" min="1">
                        </div>

                        <!-- Tipo de evento -->
                        <div class="mb-3">
                            <label for="eventType" class="form-label">Tipo de evento</label>
                            <select class="form-select bg-dark text-white border-secondary" id="eventType">
                                <option value="presencial">Presencial</option>
                                <option value="virtual">Virtual</option>
                                <option value="hibrido">Híbrido</option>
                            </select>
                        </div>

                        <!-- Imagen -->
                        <div class="mb-3">
                            <label for="eventImage" class="form-label">Imagen del evento *</label>
                            <div class="drop-zone bg-dark border border-secondary rounded p-4 text-center" id="dropZone">
                                <i class="fas fa-image mb-2"></i>
                                <p class="mb-0">Arrastra una imagen o haz clic para seleccionar</p>
                                <p class="text-muted small">(Obligatorio)</p>
                                <input type="file" id="eventImage" class="d-none" accept="image/*" required>
                            </div>
                            <div id="imagePreview" class="mt-2 d-none">
                                <img src="" alt="Preview" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-secondary">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Publicar evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid d-flex main-content-wrapper">
        <aside class="sidebar p-4">
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
    <script src="../../public/js/menu.js"></script>
    <script src="../../public/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../../public/js/calendar.js"></script>
    <script src="../../public/js/events.js"></script>
</body>
</html>
