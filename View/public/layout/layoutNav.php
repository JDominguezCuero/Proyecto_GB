
  <!-- Header -->
  <header class="header">
    <div class="logo-nombre">
      <img src="assets/Img/logos/logo2.png" alt="Logo Banco" class="logo">
      <div class="titulo"><h1>Banco Finan-CIAS</h1>
      <h6>¡La Banca que te ayuda a crecer!</h6></div>
    </div>
   <div class="dropdown">
  <button class="btn-admin" onclick="toggleDropdown()">Iniciar Sesión ▾</button>
  <div id="menuSesion" class="dropdown-content">
    <a href="../autenticacion/Login_Cliente.php">Cliente</a>
    <a href="../autenticacion/Login_Administracion.php">Administrativo</a>
     </div>
   </div>


   
  </header>

  <!-- Barra de navegación -->
  <nav class="nav">
    <ul>
      <li><a href="inicio.php">Inicio</a></li>
      <li><a href="visionMision.php">Visión y Misión</a></li>
      <li><a href="estructura.php">Estructura Organizacional</a></li>
      <li><a href="productos.php">Productos y Servicios</a></li>
    </ul>
  </nav>

 <script>
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

