<!-- equipos -->
 <section class="equipos">
  <div class="equipo">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Redes/backend_icon.png" alt="Backend" class="equipo-img">
    <div class="equipo-info">
      <h3>Equipo Backend</h3>
      <ul>
        <li>José Domingez </li>
        <li>Dana Cifuentes</li>
        <li>Brian Rodriguez</li>
        
      </ul>
    </div>
  </div>

  <div class="equipo">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Redes/frontend_icon.png" alt="Frontend" class="equipo-img">
    <div class="equipo-info">
      <h3>Equipo Frontend</h3>
      <ul>
        <li>Junior Santamaria (lider de proyecto)</li>
        <li>Juan David Huertas</li>
        <li>Daniel Suarez</li>
        <li>Juan Pablo Martinez </li>
      </ul>
    </div>
  </div>

  <div class="equipo">
    <img src="<?= BASE_URL ?>/View/public/assets/Img/Redes/dev.png" alt="Developer" class="equipo-img">
    <div class="equipo-info">
      <h3>Equipo Developer</h3>
      <ul>
        <li>Luis Fernando Acosta</li>
        <li>Eder Galeano</li>
        <li>José Dominguez</li>
      </ul>
    </div>
  </div>
</section>


<style>
/* Sección de Equipos */
.equipos {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  gap: 50px;
  padding: 50px 30px;
  flex-wrap: wrap;
  background: transparent; /* fondo transparente */
}

.equipo {
  display: flex;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.07); /* semi transparente */
  padding: 20px 25px;
  border-radius: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  max-width: 400px;
  width: 100%;
  color: white;
  transition: transform 0.3s ease;
   
  border: 3px solid;
  border-image: linear-gradient(135deg, #ff9800, #2e7d32) 1;
}

.equipo:hover {
  transform: translateY(-6px);
}

.equipo-img {
  width: 110px;
  height: 110px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 20px;
  border: 3px solid white;
}

.equipo-info h3 {
  font-size: 1.2rem;
  margin-bottom: 10px;
  border-bottom: 2px solid #ef6c00;
  display: inline-block;
  padding-bottom: 5px;
}

.equipo-info ul {
  list-style: none;
  padding: 0;
  margin: 0;
  text-align: left;
}

.equipo-info ul li {
  margin: 4px 0;
  font-size: 15px;
}

/* Responsive para móvil */
@media (max-width: 768px) {
  .equipos {
    flex-direction: column;
    align-items: center;
  }

  .equipo {
    flex-direction: column;
    text-align: center;
  }

  .equipo-img {
    margin: 0 0 15px 0;
  }

  .equipo-info ul {
    text-align: center;
  }
}

</style>