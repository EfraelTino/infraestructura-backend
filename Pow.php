<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cargar automáticamente las clases de PHPMailer si usas Composer
require 'vendor/autoload.php';

class Pow
{
    private $host = "localhost";

    private $user = "u694359124_tincuy";
    private $pass = "@Developer2024";


    // private $pass = "@Tupodcast2024";
    private $db = "u694359124_tincuy";
    // private $db = "u852125837_backendtupodca";
    public $dbConnect;

    public $respuesta = array();
    public function __construct()
    {
        $this->dbConnect = new mysqli($this->host, $this->user, $this->pass, $this->db);
        if ($this->dbConnect->connect_error) {
            die("Error en la conexión a la base de datos: " . $this->dbConnect->connect_error);
        }
    }
    public function getDbConnect()
    {
        return $this->dbConnect;
    }

    public function postInsert($table, $camps, $vals, $bind_param, $data_camps)
    {
        $respuesta = []; // Inicializar la respuesta

        $sql = "INSERT INTO $table ($camps) VALUES ($vals)";

        $stmt = mysqli_prepare($this->dbConnect, $sql);
        if (!$stmt) {
            // Si hay un error en la preparación de la consulta
            $respuesta["success"] = false;
            $respuesta["message"] = "Error en la preparación de la consulta: " . mysqli_error($this->dbConnect);
        } else {
            // Enlaza los parámetros y ejecuta la consulta
            if (!mysqli_stmt_bind_param($stmt, $bind_param, ...$data_camps)) {
                // Si hay un error al enlazar los parámetros
                $respuesta["success"] = false;
                $respuesta["message"] = "Error al enlazar los parámetros: " . mysqli_stmt_error($stmt);
            } else {
                // Ejecuta la consulta
                if (!mysqli_stmt_execute($stmt)) {
                    // Si hay un error al ejecutar la consulta
                    $respuesta["success"] = false;
                    $respuesta["message"] = "Error en la consulta: " . mysqli_error($this->dbConnect);
                } else {
                    // Si la consulta se ejecuta correctamente, obtener el ID del nuevo registro
                    $newId = mysqli_insert_id($this->dbConnect);
                    $respuesta["success"] = true;
                    $respuesta["message"] = "Consulta satisfactoria";
                    $respuesta["newId"] = $newId; // Agregar el ID del nuevo registro a la respuesta
                }
            }
            // Cierra el statement
            mysqli_stmt_close($stmt);
        }
        return json_encode($respuesta);
    }
    public function sendEmail($email, $name, $id)
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
            $mail->setFrom('suscripciones@tincuy.com', 'Equipo de suscripciones Tincuy');

            // Agrega la dirección y el nombre del destinatario (usuario)
            $mail->addAddress($email, $name);

            // Agrega la dirección de respuesta
            $mail->addReplyTo('suscripciones@tincuy.com', 'Equipo de suscripciones Tincuy');

            // Personaliza el asunto del correo
            $mail->Subject = 'Estás a un paso de suscribirte y acceder de por vida a nuestra plataforma';

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
                <title>{$name}, tus datos han sido registrados. Completa tu suscripción</title>
            </head>
            <body style="display: flex; justify-content: center; align-items: center; justify-content: center; width: 100%;      background-color: #f3f4f6;font-family: sans-serif; border-radius:12px;    max-width: 600px;    margin: auto; font-family: sans-serif;"> 
                     <div style="background-color: #f3f4f6; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px; max-width: 600px; width: 100%; text-align: center;">
                    <img src="https://res.cloudinary.com/dpwklm7yu/image/upload/v1722746691/tincuyprincipal_atrxcl.png" style="width: 200px;" alt="Logo Tincuy">
                    <h1 style="color: #22c55e; font-size: 24px; margin-bottom: 16px;">¡Felicidades! Estás a un paso de completar tu pre-registro en nuestra plataforma</h1>
                    <p style="color: #374151; font-size: 16px; margin-bottom: 16px;">Gracias por tu interés en nuestra plataforma. Te invitamos a completar tu suscripción para acceder de por vida a los mejores productos agrícolas directamente de los productores.</p>
                    <p style="color: #374151; font-size: 16px; margin-bottom: 16px;">Si no completaste tu suscripción, puedes hacerlo haciendo clic en el siguiente enlace:</p>
                    <a href="https://tincuy.com/suscribe?data={$id}&name={$name}&email={$email}" style="display: inline-block; background-color: #22c55e; color: #ffffff; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-size: 16px; transition: background-color 0.3s;">Completar suscripción</a>
                </div>
            </body>
            </html>
            EOT;

            // Envía el correo y verifica el resultado
            if ($mail->send()) {
                return array(
                    "success" => true,
                    "message" => "Correo enviado con éxito"
                );
            } else {
                return array(
                    "success" => false,
                    "message" => "No se pudo enviar el correo"
                );
            }
        } catch (Exception $e) {
            return array(
                "success" => false,
                "message" => "Error al enviar el correo: {$mail->ErrorInfo}"
            );
        }
    }
    public function findRecordById($id)
    {
        // Consulta para buscar el registro
        $sql = "SELECT id FROM suscriptores WHERE id = ?";
        $stmt = mysqli_prepare($this->dbConnect, $sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de búsqueda: " . mysqli_error($this->dbConnect));
        }

        // Asignar parámetros
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        // Verificar si el registro existe
        $exists = mysqli_stmt_num_rows($stmt) > 0;

        // Cerrar el statement
        mysqli_stmt_close($stmt);

        // Retornar verdadero si el registro existe, falso de lo contrario
        return $exists;
    }
    public function updateMetodo($id, $metodo)
    {
        // Consulta para actualizar el registro
        $sql = "UPDATE suscriptores SET metodo = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->dbConnect, $sql);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de actualización: " . mysqli_error($this->dbConnect));
        }
    
        // Asignar parámetros
        mysqli_stmt_bind_param($stmt, "si", $metodo, $id);
        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            throw new Exception("Error al ejecutar la consulta de actualización: " . mysqli_stmt_error($stmt));
        }
    
        // Obtener el número de filas afectadas
        $rows_affected = mysqli_stmt_affected_rows($stmt);
        mysqli_stmt_close($stmt);
    
        // Retornar verdadero si se han afectado filas, falso de lo contrario
        return $rows_affected > 0;
    }
    



    public function completeSuscripcion($email, $name)
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
            $mail->setFrom('suscripciones@tincuy.com', 'Equipo de suscripciones Tincuy');

            // Agrega la dirección y el nombre del destinatario (usuario)
            $mail->addAddress($email, $name);

            // Agrega la dirección de respuesta
            $mail->addReplyTo('suscripciones@tincuy.com', 'Equipo de suscripciones Tincuy');

            // Personaliza el asunto del correo
            $mail->Subject = 'Tu suscripción está en proceso de revisión: ¡Gracias por unirte!';

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
                <title>{$name}, tus datos han sido registrados. Completa tu suscripción</title>
            </head>
            <body style="display: flex; justify-content: center; align-items: center; justify-content: center; width: 100%;      background-color: #f3f4f6;font-family: sans-serif; border-radius:12px;    max-width: 600px;    margin: auto; font-family: sans-serif;">
                <div style="background-color: #f3f4f6; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); padding: 20px; max-width: 600px; width: 100%; text-align: center;">
                <img src="https://res.cloudinary.com/dpwklm7yu/image/upload/v1722746691/tincuyprincipal_atrxcl.png" style="width: 200px;" alt="Logo Tincuy">
                <h1 style="color: #22c55e; font-size: 24px; margin-bottom: 16px;">¡Felicidades! Tu suscripción está en proceso de evaluación</h1>
                <p style="color: #374151; font-size: 16px; margin-bottom: 16px;">Gracias por unirte a nuestra plataforma. Hemos recibido tu solicitud y un asesor está revisando tu registro. Pronto te contactaremos por este medio.</p>     </div>
            </body>
            </html>
            EOT;

            // Envía el correo y verifica el resultado
            if ($mail->send()) {
                return array(
                    "success" => true,
                    "message" => "Correo enviado con éxito"
                );
            } else {
                return array(
                    "success" => false,
                    "message" => "No se pudo enviar el correo"
                );
            }
        } catch (Exception $e) {
            return array(
                "success" => false,
                "message" => "Error al enviar el correo: {$mail->ErrorInfo}"
            );
        }
    }
}
