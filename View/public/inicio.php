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

  } else if (isset($_GET['login']) && $_GET['login'] == 'error') {
    $mensaje = htmlspecialchars($_GET['error']) ?? 'Error inesperado, contactese con el administrador.';
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('❌ Error al registrar', '$mensaje', 'error');
      });
      </script>";
  }else if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
    echo "<script>
      document.addEventListener('DOMContentLoaded', function() {
          showModal('✅ Operación Exitosa', 'Sesión cerrada', 'success');
      });
      </script>";
    header("Location: inicio.php");

    exit;
  }  

?>

  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco finan_cias</title>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="<?= BASE_URL ?>/View/public/assets/inicio.css">
  </head>
  <body>
    <!-- barra de navegacion en todas las paginas :) -->
   <?php include '../public/layout/barraNavAsesor.php'; ?>

 <!-- Carrusel -->
  <section class="carrusel">
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A1.png" alt="Imagen 1"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A2.png" alt="Imagen 2"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A3.png" alt="Imagen 3"></div>
   <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A4.png" alt="Imagen 4"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A5.png" alt="Imagen 5"></div>
   <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A6.png" alt="Imagen 6"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A7.png" alt="Imagen 7"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A8.png" alt="Imagen 8"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A9.png" alt="Imagen 9"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A10.png" alt="Imagen 10"></div>
   <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A11.png" alt="Imagen 11"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A12.png" alt="Imagen 12"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A13.png" alt="Imagen 13"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A14.png" alt="Imagen 14"></div>
    <div class="slide"><img src="<?= BASE_URL ?>/View/public/assets/Img/carrusel/A15.png" alt="Imagen 15"></div>
    

    <!-- Flechas -->
  <button class="flecha izq"><i class="fas fa-chevron-left"></i></button>
  <button class="flecha der"><i class="fas fa-chevron-right"></i></button>


  </section>

  <!-- Texto debajo del carrusel -->
  <section class="texto-dinamico">
    <p>
      BANCO Finan-CIAS, una institución financiera autónoma, moderna y confiable, creada con visión y compromiso desde la academia, pero pensada para el mundo real.
      Nacimos como un proyecto de los estudiantes del programa Tecnólogo en Gestión Bancaria del SENA, con el propósito de construir una banca accesible, humana y eficiente, que responda a los desafíos actuales de las personas, las familias, los emprendedores y las comunidades.
    </p>
  </section>

    <!-- Footer -->

  <?php include '../public/layout/frontendBackend.php'; ?>
  <?php include '../public/layout/layoutfooter.php'; ?>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <?php include __DIR__ . '../../../View/public/layout/mensajesModal.php'; ?>
   
  <script>
  let indiceActual = 0;
  let intervalo;

  const slides = document.querySelectorAll('.slide');

  function mostrarSlide(n) {
    slides.forEach(slide => slide.classList.remove('active'));
    slides[n].classList.add('active');
  }

  function cambiarSlide(direccion) {
    indiceActual += direccion;
    if (indiceActual < 0) indiceActual = slides.length - 1;
    if (indiceActual >= slides.length) indiceActual = 0;
    mostrarSlide(indiceActual);
  }

  function iniciarCarruselAutomatico() {
    intervalo = setInterval(() => cambiarSlide(1), 4000);
  }

  function reiniciarCarrusel() {
    clearInterval(intervalo);
    iniciarCarruselAutomatico();
  }

  // Eventos de botones (mejor para evitar conflictos en HTML inline)
  document.addEventListener("DOMContentLoaded", () => {
    mostrarSlide(indiceActual);
    iniciarCarruselAutomatico();

    document.querySelector('.flecha.izq').addEventListener('click', () => {
      cambiarSlide(-1);
      reiniciarCarrusel();
    });

    document.querySelector('.flecha.der').addEventListener('click', () => {
      cambiarSlide(1);
      reiniciarCarrusel();
    });
  });
</script>
</body>
</html>