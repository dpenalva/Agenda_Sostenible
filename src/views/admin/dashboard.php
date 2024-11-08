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
                width: 250px !important;
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
<body>
    <div class="container-fluid d-flex main-content-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar p-4">
            <div class="icon mb-4">
                <img src="/uploads/images/logo1.png" alt="Logo" class="logo1">
            </div>
            <nav class="nav flex-column w-100">
                <a href="/" class="nav-link text-white"><i class="fas fa-home"></i> Home</a>
                <a href="?r=events" class="nav-link text-white"><i class="fas fa-star"></i> Events</a>
                <a href="?r=profile" class="nav-link text-white"><i class="fas fa-user"></i> Profile</a>
                <a href="?r=admin" class="nav-link text-white active"><i class="fas fa-cog"></i> Panel Admin</a>
            </nav>
            <button class="btn logout-btn mt-4 w-100" onclick="window.location.href='/?r=logout'">
                Cerrar Sesión
            </button>
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
            <div class="data-table mb-4">
                <h5 class="text-white mb-3">Usuarios Recientes</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['recent_users'] as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nom'] . ' ' . $user['cognoms']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <button class="btn btn-action"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-action"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Eventos Recientes -->
            <div class="data-table">
                <h5 class="text-white mb-3">Eventos Recientes</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
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
                                    <td><?php echo htmlspecialchars($event['titol']); ?></td>
                                    <td><?php echo htmlspecialchars($event['data']); ?></td>
                                    <td>
                                        <button class="btn btn-action"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-action"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 