<?php
  require_once(__DIR__ . '../../../Config/config.php');
  
  if (isset($_GET['error']) && $_GET['error'] == 'error' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('❌ Error al registrar', '$mensaje', 'error');
      });
      </script>";

  }else if (isset($_GET['success']) && $_GET['success'] == 'success' && isset($_GET['msg'])) {
    $mensaje = htmlspecialchars($_GET['msg']) ?? 'Error inesperado.';
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('✅ Operación Exitosa', '$mensaje', 'success');
      });
      </script>";
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/crearAsesor.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/CrearCliente.css">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
    <!-- barra de navegacion en todas las paginas :) -->
<?php include __DIR__ . '/../public/layout/barraNavAsesor.php'; ?>
<body>

  <form action="<?= BASE_URL ?>/Controlador/asesorController.php?accion=agregarCliente" method="POST" class="formulario-banco">
    <h2>Registro de Cliente</h2>

    <div class="fila">
      <div class="columna">
        <label>Nombre:</label>
        <input type="text" name="nombre" placeholder="Ej. Carlos" required>

        <label>Género:</label>
        <select name="genero" required>
          <option value="" disabled selected>Seleccione el género</option>
          <option value="1">Hombre</option>
          <option value="2">Mujer</option>
          <option value="3">Otro</option>
        </select>

        <label>Número de Documento:</label>
        <input type="text" name="documento" placeholder="Ej. 1234567890" required>

        <label>Correo:</label>
        <input type="email" name="correo" placeholder="Ej. usuario@correo.com">

        <label>Ciudad:</label>
        <input type="text" name="ciudad" placeholder="Ej. Medellín">

        <label>Contraseña:</label>
        <input type="password" name="contrasena" placeholder="Crea una contraseña segura" required>
      </div>

      <div class="columna">
        <label>Apellido:</label>
        <input type="text" name="apellido" placeholder="Ej. Ramírez" required>

        <label>Tipo Documento:</label>
        <select name="tipo_documento" required>
          <option value="" disabled selected>Seleccionar el tipo de documento</option>
          <option value="1">Cédula de ciudadanía</option>
          <option value="2">Tarjeta de identidad</option>
          <option value="3">Cédula extranjera</option>
        </select>

        <label>Celular:</label>
        <input type="text" name="celular" placeholder="Ej. 3001234567">

        <label>Dirección:</label>
        <input type="text" name="direccion" placeholder="Ej. Cra 45 #10-15">

        <label>Fecha de Nacimiento:</label>
        <input type="date" name="fecha_nacimiento">
        
      </div>
    </div>

    <strong><h3 style="padding-top: 17px;">Datos del Producto</h3></strong>

    <div class="mb-4">
      <button type="button" onclick="abrirModalProductos()" 
        class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 border border-blue-600 hover:border-blue-800 px-3 py-1 rounded-md transition duration-150"
        style="width: auto; display: inline-flex;">
        <i data-lucide="expand" class="w-4 h-4"></i>
        Seleccionar Producto
      </button>
    </div>

    <div id="datosProductoSeleccionado" class="border p-4 rounded bg-gray-100 hidden">
      <p><strong>Nombre:</strong> <span id="nombreProducto"></span></p>
      <p><strong>Descripción:</strong> <span id="descripcionProducto"></span></p>
      <p><strong>Monto mínimo:</strong> <span id="montoMinimo"></span></p>
      <p><strong>Monto máximo:</strong> <span id="montoMaximo"></span></p>
      <p><strong>Plazo mínimo:</strong> <span id="plazoMinimo"></span></p>
      <p><strong>Plazo máximo:</strong> <span id="plazoMaximo"></span></p>
    </div>

    <strong><h3 style="padding-top: 17px;">Datos del Préstamo</h3></strong>
    
    <div class="fila">
      <div class="columna">
        <label>Valor del préstamo:</label>
        <input type="number" name="valor_prestamo" required>

        <label>Número de cuotas:</label>
        <input type="number" name="num_cuotas" required>

        <label>Tasa de interés anual (%):</label>
        <input type="number" step="0.01" name="tasa_interes_anual" required>

        <label>Período (meses):</label>
        <input type="number" name="periodo" required>
      </div>

      <div class="columna">
        <label>Tasa de interés periódica (%):</label>
        <input type="number" step="0.01" name="tasa_interes_periodica" required>

        <label>Valor de la cuota:</label>
        <input type="number" step="0.01" name="valor_cuota" required>
      </div>
    </div>

    <button type="submit">Guardar Cliente</button>
  </form>

  <!-- Modal Productos -->
  <div id="modalProductos" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 rounded shadow-lg p-6">
      <h2 class="text-xl font-semibold mb-4">Seleccionar Producto</h2>
      <input type="text" id="filtroProducto" placeholder="Buscar por nombre..." class="mb-4 p-2 border w-full rounded">
      <table class="min-w-full table-auto border border-gray-300">
        <thead>
          <tr class="bg-gray-200">
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Nombre</th>
            <th class="px-4 py-2">Descripción</th>
            <th class="px-4 py-2">Acción</th>
          </tr>
        </thead>
        <tbody id="tablaProductos">
          <?php foreach ($productos as $producto): ?>
            <tr class="hover:bg-gray-100">
              <td class="border px-4 py-2"><?= $producto['ID_Producto'] ?></td>
              <td class="border px-4 py-2"><?= $producto['Nombre_Producto'] ?></td>
              <td class="border px-4 py-2"><?= $producto['Descripcion_Producto'] ?></td>
              <td class="border px-4 py-2">
                <button
                  type="button"
                  onclick="seleccionarProducto(<?= htmlspecialchars(json_encode($producto), ENT_QUOTES, 'UTF-8') ?>)"
                  class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded"
                >Seleccionar</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="mt-4 text-right">
        <button onclick="cerrarModalProductos()" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
          Cerrar
        </button>
      </div>
    </div>
  </div>

  <script>
    lucide.createIcons();

    function abrirModalProductos() {
      document.getElementById('modalProductos').classList.remove('hidden');
    }

    function cerrarModalProductos() {
      document.getElementById('modalProductos').classList.add('hidden');
    }

    function seleccionarProducto(producto) {
      document.getElementById('nombreProducto').innerText = producto.Nombre_Producto;
      document.getElementById('descripcionProducto').innerText = producto.Descripcion_Producto;
      document.getElementById('montoMinimo').innerText = producto.Monto_Minimo;
      document.getElementById('montoMaximo').innerText = producto.Monto_Maximo;
      document.getElementById('plazoMinimo').innerText = producto.Plazo_Minimo;
      document.getElementById('plazoMaximo').innerText = producto.Plazo_Maximo;

      document.getElementById('datosProductoSeleccionado').classList.remove('hidden');
      cerrarModalProductos();
    }

    // Filtro de productos
    document.getElementById('filtroProducto').addEventListener('input', function() {
      const filtro = this.value.toLowerCase();
      const filas = document.querySelectorAll('#tablaProductos tr');
      filas.forEach(fila => {
        const nombre = fila.cells[1].textContent.toLowerCase();
        fila.style.display = nombre.includes(filtro) ? '' : 'none';
      });
    });
  </script>

  <!-- footer -->

  <?php include __DIR__ . '/../public/layout/frontendBackend.php'; ?>
  <?php include __DIR__ . '/../public/layout/layoutfooter.php'; ?>
   
  <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>

</body>
</html>



  