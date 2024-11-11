<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Utils
{
    public function jsonResponse($status, $data = [])
   
    //  public function jsonResponse($status, $data = [], $success = false)
    {
        // Verificar si $status es un array y contiene la clave 'status'
        if (is_array($status) && isset($status['status'])) {
            $statusCode = $status['status'];
        } else {
            // Si $status no es un array o no contiene la clave 'status', usar $status directamente
            $statusCode = $status;
        }
    
        // Crear la respuesta combinando el estado y los datos
        $response = array_merge(['status' => $statusCode], $data);
    
        // Establecer el código de estado HTTP
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($response); // Enviar el JSON con el estado incluido
        exit; // Finaliza el script después de enviar la respuesta
    }
    

    public function dataNow()
    {
        date_default_timezone_set('America/Bogota');
        return date('Y-m-d H:i:s');
    }

    public function sendEmail($email)
    {
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'suscripciones@tincuy.com';
            $mail->Password = '@Developer2024';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación STARTTLS

            // Usar la misma dirección para el remitente y el nombre de usuario
            $mail->setFrom('suscripciones@tincuy.com', 'Equipo de verificación Tincuy');

            // Agrega la dirección y el nombre del destinatario (usuario)
            $mail->addAddress($email);

            // Agrega la dirección de respuesta
            $mail->addReplyTo('suscripciones@tincuy.com', 'Equipo de verificación Tincuy');

            // Personaliza el asunto del correo
            $mail->Subject = 'Verifica tu cuenta de tincuy';

            // Configuración de codificación
            $mail->CharSet = 'UTF-8';

            // Contenido del correo
            $mail->isHTML(true); // Configura el correo como HTML
            $mail->Body = <<<EOT
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Tus datos han sido registrados. Verifica tu cuenta </title>
            </head>
            <body style="display: flex; justify-content: center; align-items: center; justify-content: center; width: 100%;      background-color: #f3f4f6;font-family: sans-serif; border-radius:12px;    max-width: 600px;    margin: auto; font-family: sans-serif;"> 
                     <div style="background-color: #f3f4f6; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px; max-width: 600px; width: 100%; text-align: center;">
                    <img src="https://res.cloudinary.com/dpwklm7yu/image/upload/v1722746691/tincuyprincipal_atrxcl.png" style="width: 200px;" alt="Logo Tincuy">
                    <h1 style="color: #22c55e; font-size: 24px; margin-bottom: 16px;">Verifica tu cuenta</h1>
                    <p style="color: #374151; font-size: 16px; margin-bottom: 16px;">Gracias por unirte a Tincuy, la plataforma que te conecta directamente con los mejores productos agrícolas.</p>
                    <p style="color: #374151; font-size: 16px; margin-bottom: 16px;">Para completar tu registro y acceder a nuestra oferta exclusiva de productos frescos directamente desde los productores, por favor confirma tu cuenta haciendo clic en el botón a continuación.</p>
                    <a href="http://tincuy.com/verificado?&email={$email}" style="display: inline-block; background-color: #22c55e; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-size: 16px; transition: background-color 0.3s;">Confirmar verificación</a>
                </div>
            </body>
            </html>
            EOT;

            // Envía el correo y verifica el resultado
            if ($mail->send()) {
                return true;
            } else {
             return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    public function recoverEmail($email)
    {
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;
            $mail->Username = 'recovery@tincuy.com';
            $mail->Password = '@Developer2024';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encriptación STARTTLS

            // Usar la misma dirección para el remitente y el nombre de usuario
            $mail->setFrom('recovery@tincuy.com', 'Equipo de asistencia técnica Tincuy');

            // Agrega la dirección y el nombre del destinatario (usuario)
            $mail->addAddress($email);

            // Agrega la dirección de respuesta
            $mail->addReplyTo('recovery@tincuy.com', 'Equipo de asistencia técnica Tincuy');

            // Personaliza el asunto del correo
            $mail->Subject = 'Recupera tu cuenta de tincuy';

            // Configuración de codificación
            $mail->CharSet = 'UTF-8';

            // Contenido del correo
            $mail->isHTML(true); // Configura el correo como HTML
            $mail->Body = <<<EOT
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Tus datos han sido registrados. Verifica tu cuenta </title>
            </head>
            <body style="display: flex; justify-content: center; align-items: center; justify-content: center; width: 100%;      background-color: #f3f4f6;font-family: sans-serif; border-radius:12px;    max-width: 600px;    margin: auto; font-family: sans-serif;"> 
                     <div style="background-color: #f3f4f6; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px; max-width: 600px; width: 100%; text-align: center;">
                   
                    <h1 style="color: #22c55e; font-size: 24px; margin-bottom: 16px;">Recupera tu cuenta</h1>
                    
                    <p style="color: #374151; font-size: 16px; margin-bottom: 16px;">  ¡Recupera el acceso a tu cuenta en un solo clic! Haz clic en el botón de abajo para cambiar tu contraseña. ¡Te estamos esperando!</p>
                    <a href="https://beta.tincuy.com/change-password?email={$email}" style="display: inline-block; background-color: #22c55e; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-size: 16px; transition: background-color 0.3s;">Cambiar contraseña</a>
                </div>
            </body>
            </html>
            EOT;

            // Envía el correo y verifica el resultado
            if ($mail->send()) {
                return true;
            } else {
             return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
