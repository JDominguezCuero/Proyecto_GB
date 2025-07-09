<header class="header">
  
  <div class="logo-nombre">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/logos/logo2.png" alt="Logo Banco" class="logo">
    <div class="titulo">
      <h1>Banco Finan-CIAS</h1>
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
            <div class="usuario-dropdown">
                <button onclick="toggleUsuarioMenu()" class="usuario-btn">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? '') . ' ' . htmlspecialchars($_SESSION['apellido'] ?? ''); ?></span>
                </button>
                <div id="menuUsuario" class="usuario-menu">
                    <a href="<?= BASE_URL ?>/Controlador/asesorController.php?accion=verPerfilPersonal">Mi Perfil</a>
                    <a href="#" id="cerrarSesionBtn">Cerrar Sesión</a>
                </div>
            </div>
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
  // Se ejecuta cuando el DOM está completamente cargado
  document.addEventListener('DOMContentLoaded', function() {
    // --- Confirmación para cerrar sesión ---
    const cerrarSesionBtn = document.getElementById('cerrarSesionBtn');
      if (cerrarSesionBtn) {
        cerrarSesionBtn.addEventListener('click', function(event) {
          event.preventDefault(); // Evita la navegación predeterminada del enlace
          const confirmacion = confirm('¿Estás seguro de que deseas cerrar sesión?');
          if (confirmacion) {
            window.location.href = '<?= BASE_URL ?>/View/public/inicio.php?logout=true';
          }
        });
      }
    });

    // --- FUNCIONALIDAD PARA EL MENÚ DE INICIO DE SESIÓN (Cliente/Admin) ---
    function toggleDropdown() {
      document.getElementById("menuSesion").classList.toggle("show");
    }

    // --- FUNCIONALIDAD PARA EL MENÚ DE USUARIO (Perfil/Cerrar Sesión) ---
    function toggleUsuarioMenu() {
      document.getElementById("menuUsuario").classList.toggle("show");
    }

    // --- CERRAR MENÚS AL HACER CLIC FUERA ---
    window.addEventListener("click", function(event) {
    // Cerrar menú de inicio de sesión si se hace clic fuera del "dropdown"
    if (!event.target.closest(".dropdown")) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
      for (let i = 0; i < dropdowns.length; i++) {
        const openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show')) {
          openDropdown.classList.remove('show');
        }
      }
    }

        // Cerrar menú de usuario si se hace clic fuera del "usuario-dropdown"
    if (!event.target.closest(".usuario-dropdown")) {
        const usuarioMenu = document.getElementById("menuUsuario");
        if (usuarioMenu && usuarioMenu.classList.contains("show")) {
            usuarioMenu.classList.remove("show");
        }
    }
  });
</script>