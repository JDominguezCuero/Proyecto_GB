<header class="header">

  <div class="logo-nombre">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/logos/logo2.png" alt="Logo Banco" class="logo">
    <div class="titulo">
      <h1 style="font-size: 3rem;">Banco Finan-CIAS</h1>
      <h6>¡La Banca que te ayuda a crecer!</h6>
    </div>
  </div>

  <!-- Si NO hay una sesión activa -->
  <?php if (!isset($_SESSION['usuario'])): ?>
    <div class="dropdown">
        <button class="btn-admin" onclick="toggleDropdown()">Iniciar Sesión ▾</button>
        <div id="menuSesion" class="dropdown-content">
          <a href="../autenticacion/Login_Cliente.php">Cliente</a>
          <a href="../autenticacion/Login_Administracion.php">Administrativo</a>
        </div>
    </div>
  <?php else: ?>
    <!-- Si hay sesión activa -->
    <p class="text-sm text-gray-300"><?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></p>
    <a href="#" id="cerrarSesionBtn" class="btn-admin">Cerrar Sesión</a>
  <?php endif; ?>

</header>

<nav class="nav">
  <ul>
    <!-- Vista publica (PARA TODOS) -->
    <li><a href="<?= BASE_URL ?>/View/public/inicio.php">Inicio</a></li>

    <!-- Vista Publica No Logueado-->
    <?php if (!isset($_SESSION['usuario'])): ?>
      <li><a href="<?= BASE_URL ?>/View/public/visionMision.php">Visión y Misión</a></li>
      <li><a href="<?= BASE_URL ?>/View/public/estructura.php">Estructura Organizacional</a></li>
    <?php endif; ?>

    <li><a href="<?= BASE_URL ?>/View/asesor/productosyservicios.php">Productos y Servicios</a></li>

    <!-- Para asesores -->
    <?php 
      if(isset($_SESSION['rol']) && ($_SESSION['rol'] == 3 || $_SESSION['rol'] == 5)){
        echo '<li><a href="' . BASE_URL . '/Controlador/asesorController.php?accion=listarTurnos">Turnos</a></li>';
        echo '<li><a href="' . BASE_URL . '/Controlador/asesorController.php?accion=Credito_Cliente">Solicitud credito</a></li>';
      }
    ?>

<!-- Para Cajeros -->
<?php 
      if(isset($_SESSION['rol']) && ($_SESSION['rol'] == 4 || $_SESSION['rol'] == 5)){
        echo '<li><a href="' . BASE_URL . '/View/asesor/cajero.php">Gestionar Cajero</a></li>';
      }
      ?>

<?php 
      if(isset($_SESSION['rol']) && ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 5)){
        echo '<li><a href="' . BASE_URL . '/View/asesor/RegistroAsesor.php">Crear Asesor</a></li>';
        echo '<li><a href="' . BASE_URL . '/View/asesor/Bitacora.php">Bitacora</a></li>';
        echo '<li><a href="' . BASE_URL . '/Controlador/gerenteController.php?accion=listarGestionTotal">Gestión Total</a></li>';
      }
    ?>   


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

  function toggleDropdown() {
    document.getElementById("menuSesion").classList.toggle("show");
  }

  window.onclick = function (event) {
    if (!event.target.matches('.btn-admin')) {
      const dropdowns = document.getElementsByClassName("dropdown-content");
      for (let i = 0; i < dropdowns.length; i++) {
        dropdowns[i].classList.remove("show");
      }
    }
  };
</script>