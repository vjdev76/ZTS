document.addEventListener('DOMContentLoaded', function () {
  var backToTopButton = document.getElementById('back-to-top');

  window.addEventListener('scroll', function () {
    if (window.pageYOffset > 100) {
      backToTopButton.style.display = 'block';
    } else {
      backToTopButton.style.display = 'none';
    }
  });

  backToTopButton.addEventListener('click', function () {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  });
});

document.getElementById('cvForm').addEventListener('submit', function (event) {
  event.preventDefault();
  console.log('Formulario enviado');
  const form = event.target;
  const formData = new FormData(form);

  // Mostrar el spinner mientras se procesa la solicitud
  const spinner = form.querySelector('.spinner-border');
  spinner.style.display = 'inline-block';

  fetch('send_email.php', {
    method: 'POST',
    body: formData,
  })
    .then((response) => {
      // Verifica si la respuesta es OK (estado HTTP 200-299)
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
      }
      return response.text();
    })
    .then((data) => {
      const messageContainer = document.getElementById('message-container');
      messageContainer.style.display = 'block';
      messageContainer.innerHTML = data;
      form.reset();
    })
    .catch((error) => {
      console.error('Error:', error);
      // Mostrar un mensaje de error al usuario
      const messageContainer = document.getElementById('message-container');
      messageContainer.style.display = 'block';
      messageContainer.innerHTML =
        '<div class="alert alert-danger">Hubo un error al enviar el formulario. Por favor, inténtalo de nuevo.</div>';
    })
    .finally(() => {
      // Ocultar el spinner una vez que se haya completado la solicitud
      spinner.style.display = 'none';
    });
});
