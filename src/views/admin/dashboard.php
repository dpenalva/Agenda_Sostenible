<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="/css/web.css" rel="stylesheet">
    <style>
        @media screen and (min-width: 993px) {
            .sidebar {
                position: relative !important;
                left: 0 !important;
                width: 350px !important;
            }
            
            .content {
                padding: 30px 50px;
                width: calc(100% - 250px);
                max-width: none;
            }

            .stats-row {
                margin: 0 -15px;
            }

            .stats-col {
                padding: 0 15px;
            }
        }

        /* Estilos para las tarjetas de estadísticas */
        .stats-card {
            background: #1a1d21;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card h5 {
            font-size: 1.1rem;
            margin-bottom: 15px;
            color: #5cd9d8;
        }

        .stats-number {
            font-size: 2.5rem;
            color: white;
            font-weight: bold;
        }

        /* Estilos para las tablas de datos */
        .data-table {
            background: #1a1d21;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .data-table h5 {
            color: #5cd9d8;
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2d3035;
        }

        .table {
            color: #fff;
            margin-bottom: 0;
        }

        .table thead th {
            color: #5cd9d8;
            border-bottom: 1px solid #2d3035;
            font-weight: 500;
            padding: 15px 10px;
        }

        .table td {
            padding: 15px 10px;
            border-color: #2d3035;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: background-color 0.2s;
        }

        .table tbody tr:hover {
            background-color: #2d3035;
        }

        /* Estilos para los botones de acción */
        .btn-action {
            background: #2d3035;
            border: none;
            color: #5cd9d8;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action:hover {
            background: #5cd9d8;
            color: #1a1d21;
            transform: scale(1.1);
        }

        /* Estilos para el contenedor principal */
        .content {
            padding: 30px;
        }

        .content h2 {
            font-size: 2rem;
            margin-bottom: 30px;
            color: #5cd9d8;
        }

        /* Estilos para mensajes vacíos */
        .text-muted {
            color: #6c757d !important;
            font-style: italic;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }
            
            .content {
                padding: 15px;
            }
            
            .data-table {
                padding: 15px;
            }
        }
    </style>
</head>
<body class="bg-dark">
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="menu-overlay"></div>

    <div class="container-fluid d-flex main-content-wrapper">
        <!-- Sidebar Izquierdo -->
        <aside class="sidebar p-4">
            <div class="icon mb-4">
                <img src="/uploads/images/logo1.png" alt="Logo" class="logo1">
            </div>
            <nav class="nav flex-column w-100">
                <a href="/" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
                <a href="?r=events" class="nav-link text-white"><i class="fas fa-calendar-alt"></i> Events</a>
                <a href="?r=profile" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/?r=admin" class="nav-link text-white">
                        <i class="fas fa-cog"></i> Panel Admin
                    </a>
                <?php endif; ?>
            </nav>
            <button id="logout-button" class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">Cerrar Sesión</button>
        </aside>

        <!-- Contenido Principal -->
        <main class="content p-4">
            <h2 class="text-white mb-4">Panel de Administración</h2>

            <!-- Estadísticas -->
            <div class="row stats-row mb-4">
                <div class="col-md-6 stats-col">
                    <div class="stats-card">
                        <h5 class="text-white">Total Usuarios</h5>
                        <div class="stats-number"><?php echo $stats['total_users']; ?></div>
                    </div>
                </div>
                <div class="col-md-6 stats-col">
                    <div class="stats-card">
                        <h5 class="text-white">Total Eventos</h5>
                        <div class="stats-number"><?php echo $stats['total_events']; ?></div>
                    </div>
                </div>
            </div>

            <!-- Usuarios Recientes -->
            <div class="card bg-dark text-white">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Usuarios Recientes</h5>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus"></i> Añadir Usuario
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-scroll-container">
                        <table class="table table-dark table-hover table-fixed-header">
                            <thead>
                                <tr>
                                    <th class="text-info">Nombre</th>
                                    <th class="text-info">Email</th>
                                    <th class="text-info">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($stats['recent_users'] as $user): ?>
                                <tr>
                                    <td class="text-white"><?php echo htmlspecialchars($user['nom']); ?></td>
                                    <td class="text-white"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" 
                                            onclick="loadUserData('<?php echo $user['id']; ?>')">
                                            <i class="fas fa-edit"></i> Editar
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteUser(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Card de Eventos Recientes -->
            <div class="card bg-dark text-white mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Eventos Recientes</h5>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#createEventModal">
                        <i class="fas fa-plus"></i> Añadir Evento
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-scroll-container">
                        <table class="table table-dark table-hover table-fixed-header">
                            <thead>
                                <tr>
                                    <th class="text-info">Título</th>
                                    <th class="text-info">Fecha</th>
                                    <th class="text-info">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($stats['recent_events'])): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay eventos recientes</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($stats['recent_events'] as $event): ?>
                                    <tr>
                                        <td class="text-white"><?php echo htmlspecialchars($event['titol'] ?? ''); ?></td>
                                        <td class="text-white"><?php echo htmlspecialchars($event['data_esdeveniment'] ?? ''); ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm" onclick="loadEventData(<?php echo $event['id']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteEvent(<?php echo $event['id']; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para editar usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm">
                        <input type="hidden" id="userId" name="id">
                        <div class="mb-3">
                            <label for="userNom" class="form-label">Nombre</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="userNom" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="userCognoms" class="form-label">Apellidos</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="userCognoms" name="cognoms" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control bg-dark text-white border-secondary" 
                                   id="userEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="userNomUsuari" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="userNomUsuari" name="nom_usuari" required>
                        </div>
                        <div class="mb-3">
                            <label for="userBiografia" class="form-label">Biografía</label>
                            <textarea class="form-control bg-dark text-white border-secondary" 
                                      id="userBiografia" name="biografia" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="userRol" class="form-label">Rol</label>
                            <select class="form-select bg-dark text-white border-secondary" 
                                    id="userRol" name="rol">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveUserChanges">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para añadir usuario -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Añadir Nuevo Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" onsubmit="return false;">
                        <div class="mb-3">
                            <label for="newUserName" class="form-label">Nombre</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="newUserName" name="nom" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserLastName" class="form-label">Apellidos</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="newUserLastName" name="cognoms" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserEmail" class="form-label">Email</label>
                            <input type="email" class="form-control bg-dark text-white border-secondary" id="newUserEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserUsername" class="form-label">Nombre de Usuario</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" id="newUserUsername" name="nom_usuari" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserPassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control bg-dark text-white border-secondary" id="newUserPassword" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUserRole" class="form-label">Rol</label>
                            <select class="form-control bg-dark text-white border-secondary" id="newUserRole" name="rol">
                                <option value="user">Usuario</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-info" id="saveNewUser">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear evento -->
    <div class="modal fade" id="createEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Crear Nuevo Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="createEventForm">
                        <div class="mb-3">
                            <label for="newEventTitle" class="form-label">Título</label>
                            <input type="text" class="form-control bg-dark text-white" id="newEventTitle" name="titol" required>
                        </div>

                        <div class="mb-3">
                            <label for="newEventDescription" class="form-label">Descripción</label>
                            <textarea class="form-control bg-dark text-white" id="newEventDescription" name="descripcio"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="newEventDate" class="form-label">Fecha</label>
                            <input type="date" class="form-control bg-dark text-white" id="newEventDate" name="data_esdeveniment" required>
                        </div>

                        <div class="mb-3">
                            <label for="newEventTime" class="form-label">Hora</label>
                            <input type="time" class="form-control bg-dark text-white" id="newEventTime" name="hora_esdeveniment" required>
                        </div>

                        <div class="mb-3">
                            <label for="newEventLatitude" class="form-label">Latitud</label>
                            <input type="text" class="form-control bg-dark text-white" id="newEventLatitude" name="latitud">
                        </div>

                        <div class="mb-3">
                            <label for="newEventLongitude" class="form-label">Longitud</label>
                            <input type="text" class="form-control bg-dark text-white" id="newEventLongitude" name="longitud">
                        </div>

                        <div class="mb-3">
                            <label for="newEventVisibility" class="form-label">Visibilidad</label>
                            <select class="form-control bg-dark text-white" id="newEventVisibility" name="visibilitat_esdeveniment">
                                <option value="0">Privado</option>
                                <option value="1">Público</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="saveNewEvent">Crear Evento</button>
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
                    <button type="button" class="btn btn-primary" id="saveEventChanges">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/menu.js"></script>
    <script src="/js/admin.js"></script>
</body>
</html> 