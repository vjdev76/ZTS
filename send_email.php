<?php

// Verifica si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['email'])) {
    // Recoge los datos del formulario
    $nombre = isset($_POST['name']) ? $_POST['name'] : '';
    $correo = isset($_POST['email']) ? $_POST['email'] : '';
    $archivo_cv = isset($_FILES['cv']['name']) ? $_FILES['cv']['name'] : '';
    $temp_cv = isset($_FILES['cv']['tmp_name']) ? $_FILES['cv']['tmp_name'] : '';

    // Dirección de correo a la que se enviará el formulario
    $destinatario = "flaviozanitti@zts.com.ar";

    // Asunto del correo
    $asunto = "CV enviado por la web";

    // Construye el cuerpo del correo
    $cuerpo = '
        <html> 
            <head> 
                <title>Curriculum Vitae</title> 
            </head>
            <body> 
                <h1>CV</h1>
                <p> 
                    <strong>Nombre:</strong> ' . $nombre . '<br>
                    <strong>Correo Electrónico:</strong> ' . $correo . '<br>
                </p>
            </body>
        </html>';

    // Headers para el correo en formato HTML
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    // Adjunta el archivo CV al correo
    if (is_uploaded_file($temp_cv)) {
        $adjunto = chunk_split(base64_encode(file_get_contents($temp_cv)));
        $headers .= "Content-Type: application/octet-stream; name=\"" . $archivo_cv . "\"\r\n";
        $headers .= "Content-Transfer-Encoding: base64\r\n";
        $headers .= "Content-Disposition: attachment; filename=\"" . $archivo_cv . "\"\r\n";
    } else {
        echo '<div class="alert alert-danger">Error al adjuntar el archivo.</div>';
        exit;
    }

    // Dirección del remitente
    $headers .= "From: $nombre <$correo>\r\n";

    // Envía el correo con el archivo adjunto
    if (mail($destinatario, $asunto, $cuerpo, $headers)) {
        // Muestra el mensaje de confirmación
        echo '<div class="alert alert-success">Hemos recibido tu CV correctamente.</div>';
    } else {
        echo '<div class="alert alert-danger">Hubo un error al enviar el mensaje. Por favor, inténtalo de nuevo.</div>';
    }
} else {
    // Si no se han enviado los datos del formulario, muestra un mensaje de error
    echo '<div class="alert alert-danger">No se han enviado los datos del formulario correctamente.</div>';
}

?>







