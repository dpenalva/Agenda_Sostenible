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
                <a href="?r=events" class="nav-link text-white">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="nav-link-text">Events</span>
                </a>
                <a href="?r=profile" class="nav-link text-white active"><i class="fas fa-user"></i> Profile</a>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">Cerrar Sesión</button>
        </aside>

        <!-- Contenido Principal -->
        <main class="main-content flex-grow-1 p-4">
            <div class="profile-container">
                <!-- Banner -->
                <div class="banner-container">
                    <img src="<?php echo htmlspecialchars($userData['banner'] ?? '/uploads/images/default-banner.jpg'); ?>" 
                         alt="Banner" 
                         class="banner-image">
                    <div class="edit-overlay">
                        <button type="button" class="btn edit-banner-btn">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                </div>

                <div class="profile-info-container">
                    <!-- Contenedor superior para foto y botón -->
                    <div class="profile-header">
                        <!-- Foto de perfil -->
                        <div class="profile-image-container">
                            <img src="<?php echo htmlspecialchars($userData['imatge_perfil'] ?? '/uploads/images/default-avatar.png'); ?>" 
                                 alt="Foto de perfil" 
                                 class="profile-image">
                            <div class="edit-overlay">
                                <button type="button" class="btn edit-photo-btn">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Botón editar perfil -->
                        <button class="btn edit-profile-btn" id="editProfileBtn" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i>
                            Editar perfil
                        </button>
                    </div>

                    <!-- Información del usuario -->
                    <div class="user-info">
                        <h2 id="displayName"><?php echo htmlspecialchars($userData['nom']); ?></h2>
                        <p class="username">@<?php echo htmlspecialchars($userData['nom_usuari']); ?></p>
                        <p class="bio" id="displayBio">
                            <?php echo htmlspecialchars($userData['biografia'] ?? 'No hay biografía aún...'); ?>
                        </p>
                    </div>

                    <!-- Pestañas -->
                    <div class="profile-tabs">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#posts">Posts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#respuestas">Respuestas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#destacados">Destacados</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Modal de edición -->
                <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Editar perfil</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="profileEditForm">
                                    <!-- Nombre completo editable -->
                                    <div class="mb-3">
                                        <label for="editName" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="editName" 
                                               value="<?php echo htmlspecialchars($userData['nom']); ?>">
                                    </div>

                                    <!-- Nombre de usuario no editable -->
                                    <div class="mb-3">
                                        <label class="form-label">Nombre de usuario</label>
                                        <div class="input-group">
                                            <span class="input-group-text">@</span>
                                            <input type="text" class="form-control" 
                                                   value="<?php echo htmlspecialchars($userData['nom_usuari']); ?>" 
                                                   disabled>
                                        </div>
                                    </div>

                                    <!-- Biografía editable -->
                                    <div class="mb-3">
                                        <label for="editBio" class="form-label">Biografía</label>
                                        <textarea class="form-control" id="editBio" rows="3"
                                                ><?php echo htmlspecialchars($userData['biografia'] ?? ''); ?></textarea>
                                        <div class="form-text">Máximo 160 caracteres</div>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Guardar cambios</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inputs ocultos para las imágenes -->
                <input type="file" id="bannerInput" class="d-none" accept="image/*">
                <input type="file" id="profileImageInput" class="d-none" accept="image/*">
            </div>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/profile.js"></script>
</body>
</html>
