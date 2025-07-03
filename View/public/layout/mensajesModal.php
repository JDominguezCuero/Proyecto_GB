<!-- Modal personalizado (sin Bootstrap JS) -->
<div class="modal" id="modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Mensaje Error</h5>
        <!-- <button type="button" class="close-modal" aria-label="Cerrar">x</button> -->
      </div>

      <div class="modal-body">
        <p id="modal-message">Descripción</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary close-modal">Cerrar</button>
      </div>

    </div>
  </div>
</div>

<script>
function showModal(title, message, type = 'info') {
  const modalEl = document.getElementById("modal");
  const titleEl = document.getElementById("modal-title");
  const messageEl = document.getElementById("modal-message");

  titleEl.textContent = title;
  messageEl.textContent = message;

  // Color del título según tipo
  titleEl.style.color = {
    'error': 'red',
    'success': 'green',
    'info': 'black'
  }[type] || 'black';

  modalEl.classList.add("show");

  // Cierre automático después de 10 segundos
  setTimeout(() => {
    modalEl.classList.remove("show");
  }, 10000);
}

// Escuchar clicks en botones de cerrar
document.addEventListener("DOMContentLoaded", function () {
  const modalEl = document.getElementById("modal");
  const closeButtons = modalEl.querySelectorAll(".close-modal");

  closeButtons.forEach(btn => {
    btn.addEventListener("click", function () {
      modalEl.classList.remove("show");
    });
  });
});
</script>
