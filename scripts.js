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
  var form = event.target;
  var formData = new FormData(form);

  // Mostrar el spinner mientras se procesa la solicitud
  var spinner = form.querySelector('.spinner-border');
  spinner.style.display = 'inline-block';

  fetch('send_email.php', {
    method: 'POST',
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      var messageContainer = document.getElementById('message-container');
      messageContainer.style.display = 'block';
      messageContainer.innerHTML = data;
      form.reset();
    })
    .catch((error) => {
      console.error('Error:', error);
    })
    .finally(() => {
      // Ocultar el spinner una vez que se haya completado la solicitud
      spinner.style.display = 'none';
    });
});
