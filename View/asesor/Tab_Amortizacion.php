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
    <title>Tabla de Amortización - Tasa Efectiva</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/amortizacion.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/CrearCliente.css">
</head>
<body>
 <?php include '../public/layout/barraNavAsesor.php'; ?>

 
 <form id="formulario" action="../Model/Model_Cliente.php" method="POST" class="formulario-banco">
    <h1 id="title">Formulario - Tasa Efectiva</h1>

    
  <label for="monto">Valor del Préstamo (COP)</label>
  <input type="number" id="monto" name="monto" placeholder="Ej. 1.000.000" required>

  <label for="cuotas">Número de Cuotas</label>
  <input type="number" id="cuotas" name="cuotas" placeholder="Ej. 12" required>

  <label for="tasaAnual">Tasa de Interés Anual (%)</label>
  <input type="number" id="tasaAnual" name="tasaAnual" step="0.01" placeholder="Ej. 15.5" required>

  <label for="periodicidad">Periodicidad</label>
  <select id="periodicidad" name="periodicidad" required>
    <option value="" disabled selected>Seleccione una opción</option>
    <option value="Diaria">Diaria</option>
    <option value="Mensual">Mensual</option>
    <option value="Bimensual">Bimestral</option>
    <option value="Trimestral">Trimestral</option>
    <option value="Semestral">Semestral</option>
    <option value="Anual">Anual</option>
  </select>

  <button type="submit">Calcular</button>
</form>


<div id="resultado">
    <div class="output" id="tasaPeriodica"></div>
    <div class="output" id="valorCuota"></div>
</div>

<div id="tabla-amortizacion"></div>

<script >document.getElementById("formulario").addEventListener("submit", function (e) {
    e.preventDefault();

    const monto = parseFloat(document.getElementById("monto").value);
    const cuotas = parseInt(document.getElementById("cuotas").value);
    const tasaAnual = parseFloat(document.getElementById("tasaAnual").value.replace(",", ".")) / 100;
    const periodicidad = document.getElementById("periodicidad").value;

    let tasaPeriodica = 0;

    // CALCULO CON TASA NOMINAL (simple división)
    switch (periodicidad) {
        case "Diaria":
            tasaPeriodica = tasaAnual / 365;
            break;
        case "Mensual":
            tasaPeriodica = tasaAnual / 12;
            break;
        case "Bimensual":
            tasaPeriodica = tasaAnual / 6;
            break;
        case "Trimestral":
            tasaPeriodica = tasaAnual / 4;
            break;
        case "Semestral":
            tasaPeriodica = tasaAnual / 2;
            break;
        case "Anual":
            tasaPeriodica = tasaAnual;
            break;
    }

    // Calcular cuota 
    const cuota = monto * ((tasaPeriodica * Math.pow(1 + tasaPeriodica, cuotas)) / (Math.pow(1 + tasaPeriodica, cuotas) - 1));

    document.getElementById("tasaPeriodica").innerHTML = `📈 Tasa de interés periódica (${periodicidad}): <strong>${(tasaPeriodica * 100).toFixed(4)}%</strong>`;
    document.getElementById("valorCuota").innerHTML = `💰 Valor de la cuota: <strong>$${cuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</strong>`;

    // Generar tabla al ingresar los valores
    let saldo = monto;
    let tablaHTML = `
        <h2>Tabla de Amortización</h2>
        <table>
            <thead>
                <tr>
                    <th>Nº Cuota</th>
                    <th>Valor Cuota</th>
                    <th>Abono a Capital</th>
                    <th>Abono a Intereses</th>
                    <th>Saldo después del Pago</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>0</td>
                    <td>$ 0</td>
                    <td>$ 0</td>
                    <td>$ 0</td>
                    <td>$ ${saldo.toLocaleString('es-CO')}</td>
                </tr>
    `;

    for (let i = 1; i <= cuotas; i++) {
        const interes = saldo * tasaPeriodica;
        const abonoCapital = cuota - interes;
        saldo -= abonoCapital;

        tablaHTML += `
            <tr>
                <td>${i}</td>
                <td>$ ${cuota.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                <td>$ ${abonoCapital.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                <td>$ ${interes.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>
                <td>$ ${saldo > 0 ? saldo.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0'}</td>
            </tr>
        `;
    }

    tablaHTML += `</tbody></table>`;
    document.getElementById("tabla-amortizacion").innerHTML = tablaHTML;
});
</script>

    <?php include '../public/layout/frontendBackend.php'; ?>
    <?php include '../public/layout/layoutfooter.php'; ?>

</body>
</html>
