<header class="header">
  <div class="logo-nombre">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/logos/logo2.png" alt="Logo Banco" class="logo">
    <div class="titulo">
      <h1>Banco Finan-CIAS</h1>
      <h6>¡La Banca que te ayuda a crecer!</h6>
    </div>
  </div>
  <p class="text-sm text-gray-300"><?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></p>
  <a href="#" id="cerrarSesionBtn" class="btn-admin">Cerrar Sesion</a> 
</header>

<nav class="nav">
  <ul>
    <li><a href="<?= BASE_URL ?>/View/asesor/asesorGerente.php">inicio</a></li>
    <li><a href="<?= BASE_URL ?>/View/asesor/productosyservicios.php">Productos y Servicios </a></li>
    <li><a href="<?= BASE_URL ?>/View/asesor/Tab_Amortizacion.php">Solicitud credito</a></li>
    <li><a href="<?= BASE_URL ?>/Controlador/asesorController.php?accion=listarCliente">Crear cliente</a></li>
    <?php 
      if($_SESSION['rol'] == 3){
        echo '<li><a href="../asesor/RegistroAsesor.php">Crear Asesor</a></li>';
      }
    ?>
    <li><a href="<?= BASE_URL ?>/View/asesor/RegistroAsesor.php">Crear Asesor</a></li>
    <li><a href="<?= BASE_URL ?>/View/asesor/Bitacora.php">Bitacora</a></li>
    <li><a href="<?= BASE_URL ?>/View/asesor/cajero.php">Cajero</a></li>
  </ul>
</nav>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const cerrarSesionBtn = document.getElementById('cerrarSesionBtn');

    if (cerrarSesionBtn) {
      cerrarSesionBtn.addEventListener('click', function(event) {
        event.preventDefault(); // Evita la navegación predeterminada del enlace

        // Muestra el cuadro de diálogo de confirmación
        const confirmacion = confirm('¿Estás seguro de que deseas cerrar sesión?');

        if (confirmacion) {
          // Si el usuario confirma, redirige a inicio.php con un parámetro 'logout'
          window.location.href = '<?= BASE_URL ?>/View/public/inicio.php?logout=true'; 
        }
      });
    }
  });
</script>