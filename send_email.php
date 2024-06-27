<?php

// Deshabilitar la visualización de errores en producción
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', '/ruta/a/tu/archivo_de_log_de_errores.log');

try {
    // Verifica si se han enviado los datos del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name']) && isset($_POST['email'])) {
        // Recoge los datos del formulario
        $nombre = isset($_POST['name']) ? $_POST['name'] : '';
        $correo = isset($_POST['email']) ? $_POST['email'] : '';
        $archivo_cv = isset($_FILES['cv']['name']) ? $_FILES['cv']['name'] : '';
        $temp_cv = isset($_FILES['cv']['tmp_name']) ? $_FILES['cv']['tmp_name'] : '';

        // Dirección de correo a la que se enviará el formulario
        $destinatario = "dardoleguizamon@gmail.com";

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
                        <strong>Nombre:</strong> ' . htmlspecialchars($nombre) . '<br>
                        <strong>Correo Electrónico:</strong> ' . htmlspecialchars($correo) . '<br>
                    </p>
                </body>
            </html>';

        // Headers para el correo en formato HTML
        $headers = [
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=UTF-8',
            'From' => "$nombre <$correo>"
        ];

        // Adjunta el archivo CV al correo
        if (is_uploaded_file($temp_cv)) {
            $adjunto = chunk_split(base64_encode(file_get_contents($temp_cv)));
            $filename = basename($archivo_cv);
            $headers['Content-Type'] = 'application/octet-stream; name="' . $filename . '"';
            $headers['Content-Transfer-Encoding'] = 'base64';
            $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';
        } else {
            throw new Exception('Error al adjuntar el archivo.');
        }

        // Construir la cadena de headers
        $headers_str = '';
        foreach ($headers as $key => $value) {
            $headers_str .= $key . ': ' . $value . "\r\n";
        }

        // Envía el correo con el archivo adjunto
        if (mail($destinatario, $asunto, $cuerpo, $headers_str)) {
            // Muestra el mensaje de confirmación
            echo '<div class="alert alert-success">Hemos recibido tu CV correctamente.</div>';
        } else {
            throw new Exception('Hubo un error al enviar el mensaje.');
        }
    } else {
        // Si no se han enviado los datos del formulario, muestra un mensaje de error
        echo '<div class="alert alert-danger">No se han enviado los datos del formulario correctamente.</div>';
    }
} catch (Exception $e) {
    // Registro de error en el archivo de log
    error_log($e->getMessage());
    // Muestra un mensaje de error al usuario
    echo '<div class="alert alert-danger">Hubo un error al procesar tu solicitud. Por favor, inténtalo de nuevo más tarde.</div>';
}

?>
