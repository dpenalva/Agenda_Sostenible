<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter-like Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/web.css">
</head>

<body>
    <div class="container-fluid d-flex main-content-wrapper">
        <!-- Sidebar (común en todas las páginas) -->
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

        <!-- Main Content Area (contenido variable) -->
        <main class="main-content flex-grow-1 p-4">
            <!-- Imagen de Portada y Foto de Perfil -->
            <div class="profile-banner position-relative mb-5">
                <img id="banner-img" src="https://via.placeholder.com/600x200" alt="Cover Photo" class="cover-photo w-100">
                <button id="change-banner" class="btn btn-light position-absolute" style="top: 10px; right: 10px;">Cambiar Banner</button>
            
                <!-- Avatar de perfil superpuesto -->
                <div class="profile-avatar-large">
                    <img id="profile-img" src="https://via.placeholder.com/100" alt="Profile Picture" class="rounded-circle">
                </div>
            </div>

            <!-- Información de Perfil -->
            <div class="profile-info mt-5 pt-3">
                <h3 id="profile-name" class="fw-bold">Nombre de Usuario</h3>
                <p id="profile-username" class="text-light">@Usuario</p>
                <p class="profile-bio">
                    <i class="fas fa-video"></i> TikTok: <a href="#">@username_tiktok</a> <br>
                    <i class="fas fa-twitch"></i> Twitch: <a href="#">twitch.tv/username</a>
                </p>
                <p class="text-light"><i class="fas fa-calendar-alt"></i> Se unió en abril de 2016</p>
                <div class="d-flex followers-count">
                    <p class="me-3"><strong>214</strong> Siguiendo</p>
                    <p><strong>161</strong> Seguidores</p>
                </div>
                <button class="btn profile-edit-btn mt-3">Editar perfil</button>
            </div>

            <!-- Barra de Navegación del Perfil -->
            <ul class="nav nav-tabs mt-4" id="profile-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Posts</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Respuestas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Destacados</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Artículos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Fotos y videos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Me gusta</a>
                </li>
            </ul>

            <!-- Tweets Section -->
            <div class="tweets mt-4">
               
            </div>
        </main>

        <!-- Right Sidebar (común en todas las páginas) -->
        <aside class="sidebar-right p-4">
            <h4 class="text-white">Right Sidebar Content</h4>
            <!-- Contenido adicional aquí, si es necesario -->
        </aside>
    </div>

    <script src="../../public/js/profile.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
