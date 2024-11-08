<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($userData['nom_usuari'] ?? ''); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="/css/web.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex main-content-wrapper">
        <!-- Sidebar Izquierdo -->
        <aside class="sidebar p-4">
            <div class="icon mb-4">
                <img src="/uploads/images/logo1.png" alt="Logo" class="logo1">
            </div>
            <nav class="nav flex-column w-100">
                <a href="/" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
                <a href="?r=events" class="nav-link text-white"><i class="fas fa-star"></i> Events</a>
                <a href="?r=profile" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="?r=admin" class="nav-link text-white"><i class="fas fa-cog"></i> Panel Admin</a>
                <?php endif; ?>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">Cerrar Sesión</button>
        </aside>

        <!-- Contenido Principal -->
        <main class="main-content flex-grow-1 p-4">
            <div class="profile-banner position-relative mb-5">
                <img id="banner-img" src="<?php echo $userData['banner'] ?? 'https://via.placeholder.com/600x200'; ?>" alt="Cover Photo" class="cover-photo w-100">
                <div class="profile-avatar-large">
                    <img id="profile-img" src="<?php echo $userData['imatge_perfil'] ?? 'https://via.placeholder.com/100'; ?>" alt="Profile Picture" class="rounded-circle">
                </div>
            </div>

            <div class="profile-info mt-5 pt-3 text-center">
                <h3 id="profile-name" class="fw-bold"><?php echo htmlspecialchars($userData['nom'] ?? '') . ' ' . htmlspecialchars($userData['cognoms'] ?? ''); ?></h3>
                <p id="profile-username" class="text-light">@<?php echo htmlspecialchars($userData['nom_usuari'] ?? ''); ?></p>
                <button class="btn btn-outline-light edit-profile-btn">Editar perfil</button>
                <div class="profile-bio-container mt-3">
                    <div class="profile-bio" id="bio-display">
                        <?php echo htmlspecialchars($userData['biografia'] ?? 'Escribe algo sobre ti...'); ?>
                    </div>
                </div>
            </div>

            <ul class="nav nav-tabs mt-4 justify-content-center" id="profile-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Respuestas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Destacados</a>
                </li>
            </ul>

            <div class="events mt-4">
                <?php if (!empty($userEvents)): ?>
                    <ul class="list-group">
                        <?php foreach ($userEvents as $event): ?>
                            <li class="list-group-item bg-dark text-white">
                                <h5><?php echo htmlspecialchars($event['titol']); ?></h5>
                                <p><?php echo htmlspecialchars($event['descripcio']); ?></p>
                                <small><?php echo htmlspecialchars($event['data_inici'] . ' ' . $event['hora_inici']); ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No tienes eventos.</p>
                <?php endif; ?>
            </div>
        </main>

        <!-- Sidebar Derecho -->
        <aside class="sidebar-right p-4">
            <h4 class="text-white">Right Sidebar Content</h4>
            <!-- Contenido adicional aquí, si es necesario -->
        </aside>
    </div>

    <script src="/js/menu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
