<?php
session_start();
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
  <link rel="stylesheet" href="estilos.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  <style>
    .tabla-contenedor {
      margin: 50px auto;
      width: 95%;
      max-width: 1200px;
      background-color: white;
      padding: 20px;
      border-radius: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      overflow-x: auto;
    }

    .filtro {
      margin-bottom: 20px;
      text-align: right;
    }

    .filtro input {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 16px;
      width: 250px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 14px;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #2e7d32;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    @media (max-width: 768px) {
      .filtro {
        text-align: center;
      }

      .filtro input {
        width: 100%;
        margin-bottom: 15px;
      }

      table {
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
   <?php include '../public/layout/barraNavAsesor.php'; ?>
  <div class="tabla-contenedor">
    <div class="filtro">
      <label for="filtroDocumento">Filtrar por documento personal:</label>
      <input type="text" id="filtroDocumento" placeholder="Ingrese número de documento...">
    </div>

    <table id="tablaTurnos">
      <thead>
        <tr>
          <th>ID Turno</th>
          <th>Nombre Cliente</th>
          <th>Documento Cliente</th>
          <th>ID Categoría</th>
          <th>Nombre Producto</th>
          <th>Descripción</th>
          <th>Nombre Personal</th>
          <th>Apellido Personal</th>
          <th>ID Rol Personal</th>
          <th>Documento Personal</th>
        </tr>
      </thead>
      <tbody>
        <!-- Aquí puedes llenar dinámicamente con PHP o JavaScript -->
        <tr>
          <td>001</td>
          <td>Camila López</td>
          <td>1025678901</td>
          <td>3</td>
          <td>Cuenta Ahorros</td>
          <td>Cuenta básica sin cobros adicionales</td>
          <td>Andrés</td>
          <td>Ramírez</td>
          <td>2</td>
          <td>1092837465</td>
        </tr>
        <tr>
          <td>002</td>
          <td>Esteban Gil</td>
          <td>1011223344</td>
          <td>2</td>
          <td>Crédito Vehículo</td>
          <td>Financiación para vehículo nuevo</td>
          <td>Lucía</td>
          <td>Salazar</td>
          <td>4</td>
          <td>1234567890</td>
        </tr>
        <!-- Más filas... -->
      </tbody>
    </table>
  </div>

  <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>


  <script>
    const filtro = document.getElementById("filtroDocumento");
    const tabla = document.getElementById("tablaTurnos").getElementsByTagName("tbody")[0];

    filtro.addEventListener("input", () => {
      const texto = filtro.value.toLowerCase();
      const filas = tabla.getElementsByTagName("tr");

      Array.from(filas).forEach(fila => {
        const doc = fila.cells[9].textContent.toLowerCase();
        fila.style.display = doc.includes(texto) ? "" : "none";
      });
    });
  </script>

</body>
</html>
