<?php

require_once(__DIR__ . '../../../Config/config.php');

// Mensajes de éxito/error (manteniendo tu lógica actual)
if (isset($_GET['login']) && $_GET['login'] == 'success') {
    $user = htmlspecialchars($_SESSION['nombre'] ?? 'Asesor');
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('✅ Operación Exitosa', 'Bienvenido @$user.', 'success');
        });
        </script>";
}
if (isset($_GET['error']) && $_GET['error'] == 'error' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('❌ Error', '$mensaje', 'error');
        });
        </script>";
} else if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Operación Exitosa.';
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('✅ Éxito', '$mensaje', 'success');
        });
        </script>";
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - <?= htmlspecialchars(($personalData['Nombre_Personal'] ?? 'No definido') . ' ' . ($personalData['Apellido_Personal'] ?? 'No definido')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .profile-container {
            margin: 30px auto;
            width: 95%;
            max-width: 800px;
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        .profile-header h2 {
            color: #E67E22;
            font-size: 2.5em;
            margin-bottom: 5px;
        }
        .profile-header p {
            color: #555;
            font-size: 1.1em;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #2ECC71;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 4em;
            margin: 0 auto 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .profile-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px 30px;
            margin-bottom: 30px;
        }
        .info-item {
            background-color: #f8f8f8;
            padding: 15px 20px;
            border-radius: 8px;
            border-left: 5px solid;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .info-item.primary { border-color: #E67E22; }
        .info-item.secondary { border-color: #2ECC71; }

        .info-item strong {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9em;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-item p {
            font-size: 1.1em;
            color: #333;
            margin: 0;
            word-wrap: break-word;
        }
        .profile-section-title {
            color: #34495E;
            font-size: 1.8em;
            margin-top: 40px;
            margin-bottom: 25px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #ecf0f1;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .stat-card .icon {
            font-size: 2.5em;
            color: #E67E22;
            margin-bottom: 10px;
        }
        .stat-card .value {
            font-size: 2em;
            font-weight: bold;
            color: #2ECC71;
            margin-bottom: 5px;
        }
        .stat-card .label {
            font-size: 0.9em;
            color: #555;
        }

        @media (max-width: 768px) {
            .profile-container {
                padding: 15px;
            }
            .profile-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../public/layout/barraNavAsesor.php'; ?>


    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h2><?= htmlspecialchars(($personalData['Nombre_Personal'] ?? 'No definido') . ' ' . ($personalData['Apellido_Personal'] ?? 'No definido')) ?></h2>
            <p><?= htmlspecialchars($personalData['Nombre_Rol'] ?? 'Rol no definido') ?></p>
        </div>

        <h3 class="profile-section-title">Información Personal</h3>
        <div class="profile-info-grid">
            <div class="info-item primary">
                <strong>Número de Documento</strong>
                <p><?= htmlspecialchars($personalData['N_Documento_Personal'] ?? 'No definido') ?></p>
            </div>
            <div class="info-item secondary">
                <strong>Email</strong>
                <p><?= htmlspecialchars($personalData['Correo_Personal'] ?? 'No definido') ?></p>
            </div>
            <div class="info-item primary">
                <strong>Teléfono</strong>
                <p><?= htmlspecialchars($personalData['Celular_Personal'] ?? 'No definido') ?></p>
            </div>
            <div class="info-item secondary">
                <strong>Fecha de Creación</strong>
                <p><?= htmlspecialchars($personalData['Fecha_Creacion_Personal'] ?? 'No definido') ?></p>
            </div>
            <div class="info-item primary">
                <strong>Estado</strong>
                <p>
                    <?php
                        // Convertir el valor booleano o numérico a un estado legible
                        $estadoPersonal = $personalData['Activo_Personal'] ?? null;
                        if ($estadoPersonal === 1 || $estadoPersonal === true) {
                            echo "Activo";
                        } elseif ($estadoPersonal === 0 || $estadoPersonal === false) {
                            echo "Inactivo";
                        } else {
                            echo "No definido";
                        }
                    ?>
                </p>
            </div>
            <div class="info-item secondary">
                <strong>Tipo de Documento</strong>
                <p><?= htmlspecialchars($personalData['Nombre_Tipo_Documento'] ?? 'No definido') ?></p>
            </div>
            <div class="info-item primary">
                <strong>Género</strong>
                <p><?= htmlspecialchars($personalData['Nombre_Genero'] ?? 'No definido') ?></p>
            </div>
             <div class="info-item secondary">
                <strong>ID de Personal</strong>
                <p><?= htmlspecialchars($personalData['ID_Personal'] ?? 'No definido') ?></p>
            </div>
            </div>

        <?php
        $idRol = $personalData['ID_Rol'] ?? null;
        if ($idRol == 3 || $idRol == 4 || $idRol == 5) { ?>
            <h3 class="profile-section-title">Estadísticas de Actividad</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-handshake"></i></div>
                    <div class="value"><?= htmlspecialchars($personalData['estadisticas']['total_asesoramientos'] ?? 0) ?></div>
                    <div class="label">Asesoramientos Realizados</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <div class="value"><?= htmlspecialchars($personalData['estadisticas']['clientes_asesorados_unicos'] ?? 0) ?></div>
                    <div class="label">Clientes Asesorados (Únicos)</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <div class="value"><?= htmlspecialchars($personalData['estadisticas']['turnos_atendidos'] ?? 0) ?></div>
                    <div class="label">Turnos Atendidos</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    <div class="value">$<?= htmlspecialchars($personalData['estadisticas']['monto_total_aprobado_clientes_asesorados'] ?? '0.00') ?></div>
                    <div class="label">Monto Aprobado Clientes Asesorados</div>
                </div>
            </div>
        <?php
        }
        ?>

    </div>

    <?php include __DIR__ . '/../public/layout/frontendBackend.php'; ?>
    <?php include __DIR__ . '/../public/layout/layoutfooter.php'; ?>    
    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>
</body>
</html>