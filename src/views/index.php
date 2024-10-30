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
                <a href="index.php" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
                <a href="events.php" class="nav-link text-white"><i class="fas fa-star"></i> Events</a>
                <a href="profile.php" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100">Cerrar Sesión</button>
        </aside>

        <main class="main-content flex-grow-1 p-4">
            <h2 class="text-white mb-3">Home</h2>

            <!-- Sección de Crear Post -->
            <div class="create-post bg-dark p-3 rounded mb-4">
                <div class="d-flex">
                    <img src="https://via.placeholder.com/50" alt="User Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                    <div class="flex-grow-1">
                        <input type="text" id="post-input" class="form-control bg-dark text-white border-secondary mb-3" placeholder="¿Qué está pasando?!" style="border-radius: 10px;">
                        <input type="date" id="date-input" class="form-control bg-dark text-white border-secondary" style="border-radius: 10px;">
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="action-icons d-flex gap-3 text-light">
                        <i class="fas fa-image"></i>
                        <i class="fas fa-chart-bar"></i>
                        <i class="fas fa-smile"></i>
                        <i class="fas fa-calendar-alt"></i>
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <button id="post-button" class="btn btn-primary rounded-pill px-4">Postear</button>
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

    <script src="../../public/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="../../public/js/calendar.js"></script>
</body>
</html>
