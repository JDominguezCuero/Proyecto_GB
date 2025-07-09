<?php
require_once(__DIR__ . '../../../Config/config.php');

if (isset($_GET['login']) && $_GET['login'] == 'success') {
  $user = htmlspecialchars($_SESSION['nombre'] ?? 'Asesor');
  echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        showModal('✅ Operación Exitosa', 'Bienvenido @$user.', 'success');
    });
  </script>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Vista de Turnos</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- <script src="https://cdn.tailwindcss.com"></script> -->
</head>

<body>
  <?php include __DIR__ . '/../public/layout/barraNavAsesor.php'; ?>

  <div class="bg-white contenido-turnos max-w-7xl mx-auto p-6 mt-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">Módulo de Gestión de Turnos</h1>

    <!-- Filtro por producto -->
    <div class="mb-6 flex justify-end items-center">
      <label class="mr-2 font-semibold text-gray-700">Filtrar por Producto:</label>
      <select id="filtroProducto" class="border rounded px-3 py-1">
        <option value="todos">Todos</option>
        <?php foreach ($productos as $producto): ?>
          <option value="<?= $producto['ID_Producto'] ?>"><?= htmlspecialchars($producto['Nombre_Producto']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Turnos pendientes -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-10">
      <h2 class="text-xl font-semibold text-blue-700 mb-4">🕒 Turnos por Atender</h2>
      <div id="turnosPendientes" class="space-y-4">
        <!-- JS insertará aquí -->
      </div>
    </div>

    <!-- Turnos atendidos -->
    <div class="bg-white shadow-md rounded-lg p-4">
      <h2 class="text-xl font-semibold text-green-700 mb-4">✅ Turnos Atendidos</h2>
      <div id="turnosAtendidos" class="space-y-4">
        <!-- JS insertará aquí -->
      </div>
    </div>
  </div>

    <?php include __DIR__ . '/../public/layout/frontendBackend.php'; ?>
    <?php include __DIR__ . '/../public/layout/layoutfooter.php'; ?>    
    <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

  <script>
    // Datos PHP convertidos a JavaScript
    const turnos = <?= json_encode($turnos) ?>;
    const productos = {};
    <?php foreach ($productos as $producto): ?>
      productos[<?= $producto['ID_Producto'] ?>] = "<?= htmlspecialchars($producto['Nombre_Producto']) ?>";
    <?php endforeach; ?>

    function renderTurnos() {
      const filtro = document.getElementById('filtroProducto').value;
      const pendientesDiv = document.getElementById('turnosPendientes');
      const atendidosDiv = document.getElementById('turnosAtendidos');

      pendientesDiv.innerHTML = '';
      atendidosDiv.innerHTML = '';

      turnos.forEach(turno => {
        if (filtro !== 'todos' && turno.ID_Producto_Interes != filtro) return;

        const card = document.createElement('div');
        card.className = 'border p-4 rounded-lg shadow-sm bg-gray-50';

        card.innerHTML = `
          <div class="flex justify-between items-start">
              <div>
                  <p><strong>Turno:</strong> ${turno.Numero_Turno}</p>
                  <p><strong>Solicitante:</strong> ${turno.Nombre_Completo_Solicitante}</p>
                  <p><strong>Documento:</strong> ${turno.N_Documento_Solicitante}</p>
                  <p><strong>Tipo de Usuario:</strong> ${turno.EsCliente}</p>
                  <p><strong>Producto:</strong> ${turno.Nombre_Producto ?? 'No Definido.'}</p>
                  <p><strong>Detalle Producto:</strong> ${turno.Descripcion_Producto ?? 'No Definido.'}</p>
                  <p><strong>Motivo:</strong> ${turno.Motivo_Turno ?? 'No Definido.'}</p>
                  <p><strong>Tiempo de Espera:</strong> ${turno.Tiempo_Espera_Minutos ?? 'No Definido.'} min</p>
              </div>
              ${
                  turno.ID_Estado_Turno == 1
                  ? `<button type="button" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                            onclick="atenderTurno('${turno.ID_Turno}', '${turno.Nombre_Completo_Solicitante}', '${turno.N_Documento_Solicitante}')">Atender</button>`
                  : `<span class="text-green-600 font-semibold mt-2">✔ Atendido</span>`
              }
          </div>
        `;

        if (turno.ID_Estado_Turno == 1) {
          pendientesDiv.appendChild(card);
        } else {
          atendidosDiv.appendChild(card);
        }

      });
    }

    console.log(turnos);

    function atenderTurno(idTurno, nombreCompleto, documento) {
      // Almacena los datos en sessionStorage
      sessionStorage.setItem('atenderTurno_id', idTurno);
      sessionStorage.setItem('atenderTurno_nombre', nombreCompleto); // Ya lo estás haciendo
      sessionStorage.setItem('atenderTurno_documento', documento); // Ya lo estás haciendo

      // --- AGREGAR ESTOS CONSOLE.LOG PARA DEPURAR ---
      console.log('Datos guardados en sessionStorage:');
      console.log('ID Turno:', sessionStorage.getItem('atenderTurno_id'));
      console.log('Nombre Completo:', sessionStorage.getItem('atenderTurno_nombre'));
      console.log('Documento:', sessionStorage.getItem('atenderTurno_documento'));
      // ---------------------------------------------

      window.location.href = `<?= BASE_URL ?>/Controlador/asesorController.php?accion=Credito_Cliente&idTurno=${idTurno}`;
    }

    document.getElementById('filtroProducto').addEventListener('change', renderTurnos);
    renderTurnos();
  </script>
</body>
</html>
