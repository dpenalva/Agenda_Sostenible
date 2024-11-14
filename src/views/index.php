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
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.css' rel='stylesheet' />
    <link href='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.css' rel='stylesheet' />
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
                <a href="?r=events" class="nav-link text-white">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-link-text">Events</span>
                </a>
                <a href="?r=profile" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="?r=admin" class="nav-link text-white"><i class="fas fa-cog"></i> Panel Admin</a>
                <?php endif; ?>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">Cerrar Sesión</button>
        </aside>

        <main class="main-content flex-grow-1 p-4">
            <h2 class="text-white mb-3">Home</h2>

            <!-- Área de creación de evento -->
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="post-creation-area">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button type="button" class="create-event-button" data-bs-toggle="modal" data-bs-target="#createEventModal">
                            <i class="fas fa-plus me-2"></i>Crear Evento
                        </button>
                        
                        <div class="search-container">
                            <input type="text" id="searchEvents" class="search-bar" placeholder="Buscar eventos..." autocomplete="off">
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Feed de eventos -->
            <div class="events-feed mt-4">
                <?php if (isset($eventos) && !empty($eventos)): ?>
                    <?php foreach ($eventos as $evento): ?>
                        <div class="event-card mb-4" data-event-id="<?php echo $evento['id']; ?>">
                            <div class="card bg-dark text-white position-relative">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($evento['titol'] ?? 'Sin título'); ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($evento['descripcio'] ?? 'Sin descripción'); ?>
                                    </p>
                                    <div class="event-details">
                                        <div class="detail">
                                            <i class="fas fa-calendar"></i>
                                            <?php 
                                                $fecha = !empty($evento['data_esdeveniment']) 
                                                    ? date('d/m/Y', strtotime($evento['data_esdeveniment'])) 
                                                    : 'Fecha no especificada';
                                                echo $fecha;
                                            ?>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-clock"></i>
                                            <?php 
                                                $hora = !empty($evento['hora_esdeveniment']) 
                                                    ? date('H:i', strtotime($evento['hora_esdeveniment'])) 
                                                    : 'Hora no especificada';
                                                echo $hora;
                                            ?>
                                        </div>
                                        <div class="detail">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?php 
                                                $ubicacion = (!empty($evento['longitud']) && !empty($evento['latitud']))
                                                    ? htmlspecialchars($evento['longitud'] . ', ' . $evento['latitud'])
                                                    : 'Ubicación no especificada';
                                                echo $ubicacion;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="visibility-badge">
                                        <i class="fas fa-eye"></i>
                                        <?php echo isset($evento['visibilitat_esdeveniment']) && $evento['visibilitat_esdeveniment'] ? 'Público' : 'Privado'; ?>
                                    </div>
                                    <div class="event-interactions mt-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <button 
                                                    class="like-button me-3 <?php echo isset($evento['is_liked']) && $evento['is_liked'] ? 'liked' : ''; ?>"
                                                    data-event-id="<?php echo $evento['id']; ?>"
                                                    onclick="toggleLike(<?php echo $evento['id']; ?>)"
                                                >
                                                    <i class="fas fa-heart"></i>
                                                </button>
                                                
                                                <div class="rating-container d-inline-flex align-items-center">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star rating-star" 
                                                           data-rating="<?php echo $i; ?>"
                                                           onclick="rateEvent(<?php echo $evento['id']; ?>, <?php echo $i; ?>)"
                                                           style="color: <?php echo (isset($evento['user_rating']) && $evento['user_rating'] >= $i) ? '#ffc107' : '#6c757d'; ?>">
                                                        </i>
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
                    <div class="text-center text-muted mt-4">
                        <p>No hay eventos disponibles en este momento.</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>

        <!-- Right Sidebar with Calendar -->
        <aside class="sidebar-right p-4">
            <h4 class="text-white">Calendar</h4>
            <div class="calendar">
                <div class="calendar-header">
                    <button id="prevMonth">&lt;</button>
                    <h2 id="monthDisplay"></h2>
                    <button id="nextMonth">&gt;</button>
                </div>
                <div class="calendar-weekdays">
                    <div>Do</div>
                    <div>Lu</div>
                    <div>Ma</div>
                    <div>Mi</div>
                    <div>Ju</div>
                    <div>Vi</div>
                    <div>Sa</div>
                </div>
                <div id="daysContainer"></div>
            </div>
        </aside>
    </div>
    <script src="/js/menu.js"></script>
    <script src="/js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="/js/calendar.js"></script>
    <script src="/js/events.js"></script>
    <script src="/js/eventActions.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.js'></script>

    <!-- Modal para crear evento -->
    <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Crear Nuevo Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createEventForm">
                        <div class="mb-3">
                            <label for="titol" class="form-label">Título</label>
                            <input type="text" class="form-control bg-dark text-white" id="titol" name="titol" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcio" class="form-label">Descripción</label>
                            <textarea class="form-control bg-dark text-white" id="descripcio" name="descripcio" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="data_esdeveniment" class="form-label">Fecha</label>
                            <input type="date" class="form-control bg-dark text-white" id="data_esdeveniment" name="data_esdeveniment" required>
                        </div>
                        <div class="mb-3">
                            <label for="hora_esdeveniment" class="form-label">Hora</label>
                            <input type="time" class="form-control bg-dark text-white" id="hora_esdeveniment" name="hora_esdeveniment" required>
                        </div>
                        <div class="mb-3">
                            <label for="longitud" class="form-label">Longitud</label>
                            <input type="text" class="form-control bg-dark text-white" id="longitud" name="longitud" required>
                        </div>
                        <div class="mb-3">
                            <label for="latitud" class="form-label">Latitud</label>
                            <input type="text" class="form-control bg-dark text-white" id="latitud" name="latitud" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="visibilitat_esdeveniment" name="visibilitat_esdeveniment" checked>
                                <label class="form-check-label" for="visibilitat_esdeveniment">
                                    Evento público
                                </label>
                            </div>
                        </div>
                        <button type="button" id="saveEventButton" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveEventButton">Crear Evento</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar evento -->
    <div class="modal fade" id="editEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Editar Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm">
                        <input type="hidden" id="editEventId" name="id">
                        
                        <div class="mb-3">
                            <label for="editEventTitle" class="form-label">Título</label>
                            <input type="text" class="form-control bg-dark text-white" id="editEventTitle" name="titol" required>
                        </div>

                        <div class="mb-3">
                            <label for="editEventDescription" class="form-label">Descripción</label>
                            <textarea class="form-control bg-dark text-white" id="editEventDescription" name="descripcio"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="editEventDate" class="form-label">Fecha</label>
                            <input type="date" class="form-control bg-dark text-white" id="editEventDate" name="data_esdeveniment" required>
                        </div>

                        <div class="mb-3">
                            <label for="editEventTime" class="form-label">Hora</label>
                            <input type="time" class="form-control bg-dark text-white" id="editEventTime" name="hora_esdeveniment" required>
                        </div>

                        <div class="mb-3">
                            <label for="editEventLatitude" class="form-label">Latitud</label>
                            <input type="text" class="form-control bg-dark text-white" id="editEventLatitude" name="latitud">
                        </div>

                        <div class="mb-3">
                            <label for="editEventLongitude" class="form-label">Longitud</label>
                            <input type="text" class="form-control bg-dark text-white" id="editEventLongitude" name="longitud">
                        </div>

                        <div class="mb-3">
                            <label for="editEventVisibility" class="form-label">Visibilidad</label>
                            <select class="form-control bg-dark text-white" id="editEventVisibility" name="visibilitat_esdeveniment">
                                <option value="0">Privado</option>
                                <option value="1">Público</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveEventChanges()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
