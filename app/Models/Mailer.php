<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../libs/PHPMailer/src/SMTP.php';          

/* ---------------------------------------------------------
   CLASE MAILER
   Envío de correos mediante PHPMailer.
   Usa los parámetros definidos en Config.php.
   --------------------------------------------------------- */

class Mailer {

    /**
     * Envía un correo electrónico usando PHPMailer.
     *
     * @param string $to      Dirección del destinatario
     * @param string $subject Asunto del correo
     * @param string $body    Cuerpo del mensaje (HTML permitido)
     * @return bool           true si se envió correctamente, false si falló
     */
    public static function enviar($to, $subject, $body) {

        // Creamos una instancia de PHPMailer con manejo de excepciones
        $mail = new PHPMailer(true);

        try {
            /* -----------------------------------------
               CONFIGURACIÓN DEL SERVIDOR SMTP (GMAIL)
               ----------------------------------------- */
            $mail->isSMTP();                     // Usar protocolo SMTP
            $mail->Host = SMTP_HOST;             // Servidor SMTP
            $mail->SMTPAuth = true;              // Requiere autenticación
            $mail->Username = SMTP_USER;         // Usuario SMTP (tu Gmail)
            $mail->Password = SMTP_PASS;         // Contraseña de aplicación
            $mail->SMTPSecure = SMTP_SECURE;     // Tipo de cifrado (ssl)
            $mail->Port = SMTP_PORT;             // Puerto (465)

            /* -----------------------------------------
               CONFIGURACIÓN DEL REMITENTE Y DESTINATARIO
               ----------------------------------------- */
            $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME); // Remitente
            $mail->addAddress($to);                    // Destinatario

            /* -----------------------------------------
               CONTENIDO DEL MENSAJE
               ----------------------------------------- */
            $mail->isHTML(true);               // Permitir HTML
            $mail->CharSet = "UTF-8";          // Codificación
            $mail->Subject = $subject;         // Asunto
            $mail->Body    = $body;            // Cuerpo HTML
            $mail->AltBody = strip_tags($body); // Alternativa en texto plano

            // Intentar enviar el correo
            return $mail->send();

        } catch (Exception $e) {
            // Registrar el error en el log del servidor
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
