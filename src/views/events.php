<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/web.css">
</head>

<body>
    <!-- Agregar botón de menú y overlay -->
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="menu-overlay"></div>

    <div class="container-fluid d-flex main-content-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar p-4">
            <div class="icon mb-4">
                <img src="/uploads/images/logo1.png" alt="Logo" class="logo1">
            </div>
            <nav class="nav flex-column w-100">
                <a href="/" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
                <a href="?r=events" class="nav-link text-white">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-link-text">Events</span>
                </a>
                <a href="?r=profile" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="?r=admin" class="nav-link text-white"><i class="fas fa-cog"></i> Panel Admin</a>
                <?php endif; ?>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">
                Cerrar Sesión
            </button>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content flex-grow-1 p-4">
            <h2 class="text-white mb-3">Mis Eventos Favoritos</h2>

            <!-- Contenedor para los eventos favoritos -->
            <div class="saved-events">
                <?php if (!empty($savedEvents)): ?>
                    <?php foreach ($savedEvents as $evento): ?>
                        <div class="event-card mb-4" data-event-id="<?php echo htmlspecialchars($evento['id']); ?>">
                            <div class="card bg-dark text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($evento['titol'] ?? 'Sin título'); ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($evento['descripcio'] ?? 'Sin descripción'); ?>
                                    </p>
                                    <div class="event-details">
                                        <div class="date-time mb-2">
                                            <i class="far fa-calendar-alt me-2"></i>
                                            <?php 
                                                $fecha = new DateTime($evento['data_esdeveniment']);
                                                echo $fecha->format('d/m/Y');
                                            ?>
                                            <i class="far fa-clock ms-3 me-2"></i>
                                            <?php echo $evento['hora_esdeveniment']; ?>
                                        </div>
                                        <?php if (!empty($evento['longitud']) && !empty($evento['latitud'])): ?>
                                            <div class="location mb-2">
                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                <span>
                                                    <?php 
                                                        echo "Lat: " . number_format($evento['latitud'], 4) . 
                                                             ", Long: " . number_format($evento['longitud'], 4);
                                                    ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="event-interactions mt-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <button 
                                                    class="like-button me-3 liked" 
                                                    data-event-id="<?php echo $evento['id']; ?>"
                                                    onclick="toggleLike(<?php echo $evento['id']; ?>)"
                                                >
                                                    <i class="fas fa-heart" style="color: #53d5cd;"></i>
                                                </button>
                                                
                                                <div class="rating-container d-inline-flex align-items-center">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star rating-star" 
                                                           data-event-id="<?php echo $evento['id']; ?>"
                                                           data-rating="<?php echo $i; ?>"
                                                           style="color: <?php echo ($evento['user_rating'] >= $i) ? '#ffc107' : '#6c757d'; ?>"
                                                           onclick="rateEvent(<?php echo $evento['id']; ?>, <?php echo $i; ?>)"
                                                        ></i>
                                                    <?php endfor; ?>
                                                    <span class="average-rating ms-2">
                                                        (<?php echo number_format($evento['average_rating'] ?? 0, 1); ?>)
                                                    </span>
                                                    
                                                    
                                                </div>
                                                <a href="?r=eventDetails&id=<?php echo $evento['id']; ?>" 
                                                       class="btn btn-outline-primary btn-sm ms-3">
                                                        <i class="fas fa-info-circle"></i> Más detalles
                                                </a>
                                            </div>

                                            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                                <div class="event-actions ms-3">
                                                    <button class="btn btn-sm btn-link text-primary" 
                                                            onclick="loadEventData(<?php echo $evento['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-link text-danger" 
                                                            onclick="deleteEvent(<?php echo $evento['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-info">
                        No tienes eventos guardados como favoritos.
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Right Sidebar (común en todas las páginas) -->
        <aside class="sidebar-right p-4">
            <h4 class="text-white">Right Sidebar Content</h4>
            <!-- Contenido adicional aquí, si es necesario -->
        </aside>
    </div>
    
    <script src="/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="/js/events.js"></script>
</body>
</html>
