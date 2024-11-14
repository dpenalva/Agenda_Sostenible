<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($event['titol']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/web.css">
</head>
<body>
    <div class="container-fluid d-flex main-content-wrapper">

        <!-- Contenido principal -->
        <main class="main-content flex-grow-1 p-4">
            <div class="event-details-container">
                <!-- Header del evento con efecto de cristal -->
                <div class="event-header-glass">
                        <h1 class="neo-title"><?php echo htmlspecialchars($event['titol']); ?></h1>
                        <div class="event-meta">
                            <span class="neo-badge <?php echo $event['visibilitat_esdeveniment'] ? 'public' : 'private'; ?>">
                                <i class="fas fa-eye"></i>
                                <?php echo $event['visibilitat_esdeveniment'] ? 'Público' : 'Privado'; ?>
                            </span>
                            <span class="neo-badge user">
                                <i class="fas fa-user"></i>
                                <?php echo htmlspecialchars($event['username'] ?? 'Anónimo'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Grid de información -->
                <div class="neo-grid">
                    <!-- Tarjeta de fecha y hora -->
                    <div class="neo-card time-card">
                        <div class="card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-content">
                            <div class="date-display">
                                <?php echo date('d/m/Y', strtotime($event['data_esdeveniment'])); ?>
                            </div>
                            <div class="time-display">
                                <?php echo $event['hora_esdeveniment']; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de ubicación -->
                    <div class="neo-card location-card">
                        <div class="card-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="card-content">
                            <div class="coordinates">
                                <span>LAT: <?php echo number_format($event['latitud'], 4); ?></span>
                                <span>LONG: <?php echo number_format($event['longitud'], 4); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de interacciones -->
                    <div class="neo-card interaction-card">
                        <div class="rating-section">
                            <div class="neo-stars">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star" 
                                       data-rating="<?php echo $i; ?>"
                                       onclick="rateEvent(<?php echo $event['id']; ?>, <?php echo $i; ?>)"
                                       style="color: <?php echo (isset($event['user_rating']) && $event['user_rating'] >= $i) ? '#53d5cd' : '#2a2d35'; ?>">
                                    </i>
                                <?php endfor; ?>
                                <span class="rating-value"><?php echo number_format($event['average_rating'] ?? 0, 1); ?></span>
                            </div>
                            <button class="neo-like-btn <?php echo isset($event['is_liked']) && $event['is_liked'] ? 'active' : ''; ?>"
                                    onclick="toggleLike(<?php echo $event['id']; ?>)">
                                <i class="fas fa-heart"></i>
                                <span class="likes-count"><?php echo $event['likes_count'] ?? 0; ?></span>
                            </button>
                        </div>
                    </div>

                    <!-- Tarjeta de descripción -->
                    <div class="neo-card description-card">
                        <h3 class="neo-subtitle">Descripción</h3>
                        <div class="description-content">
                            <?php echo nl2br(htmlspecialchars($event['descripcio'])); ?>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="neo-actions">
                    <a href="/" class="neo-btn back">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $event['id_usuari']): ?>
                        <button class="neo-btn edit" onclick="loadEventData(<?php echo $event['id']; ?>)">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button class="neo-btn delete" onclick="deleteEvent(<?php echo $event['id']; ?>)">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    <?php endif; ?>
                </div>
        </main>
    </div>

    <script src="/js/events.js"></script>
</body>
</html> 