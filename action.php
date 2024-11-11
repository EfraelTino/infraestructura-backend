<?php
// Si descargaste PHPMailer manualmente, incluye las clases necesarias
// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

$allowedOrigins = [
    'https://tincuy.com',
    'http://localhost:5174',
    'https://beta.tincuy.com/'
];

// Obtener el origen de la solicitud
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

// Verificar si el origen está permitido
if (in_array($origin, $allowedOrigins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// Configurar los métodos y encabezados permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Permitir el envío de credenciales
header("Access-Control-Allow-Credentials: true");

// Manejar la solicitud OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Enviar una respuesta 200 OK para solicitudes OPTIONS
    http_response_code(200);
    exit();
}

// Tu lógica PHP aquí


include("./Pow.php");
$actions = new Pow();
if (isset($_POST['action']) && $_POST['action'] == "insertada") {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telf = intval($_POST['telf']);
    $servicio = $_POST['plan'];
    $campodb = "name, correo, tipo,  telf";
    $valores = "?, ?, ?, ?";
    $bind = "sssi";
    $data = array($nombre, $correo, $servicio, $telf);
    $insertdata = $actions->postInsert('suscriptores', $campodb, $valores, $bind, $data);
    try {
        if ($insertdata) {
            // Decodificar la respuesta JSON para obtener el ID del nuevo registro
            $insert_response = json_decode($insertdata, true);
            $newId = $insert_response['newId'];

            // Enviar solo el ID como respuesta
            $send_email = $actions->sendEmail($correo, $nombre, $newId);

            if ($send_email['success']) {
                // echo "Mensaje: " . $send_email['message'];
                $response = array('success' => true, 'code' => 200, 'message' => 'Genial, estás a un paso de tener acceso a nuestra plataforma de por vida', 'newId' => $newId);
            } else {
                $response = array('success' => false, 'code' => 404, 'message' => $send_email);
            }
            // $response = array('success' => true, 'code' => 200, 'message' => 'Genial, estás a un paso de tener acceso a nuestra plataforma de por vida', 'newId' => $newId);
        } else {
            $response = array('success' => false, 'code' => 404, 'message' => 'Ocurrió un error, intenta nuevamente.');
        }
    } catch (\Throwable $th) {
        $response = array('success' => false, 'code' => 404, 'message' => 'Ocurrió un error, intenta nuevamente.');
    }
    echo json_encode($response);
}
if (isset($_POST['action']) && $_POST['action'] == "updatedata") {
    $id = intval($_POST['id']);
    $metodo = $_POST['metodo'];
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $name = htmlspecialchars($_POST['name']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = array('success' => false, 'message' => 'Email no válido.');
        echo json_encode($response);
        exit();
    }

    try {
        // Verificar si el registro existe
        $recordExists = $actions->findRecordById($id);
        if ($recordExists) {
            // Actualizar el método del registro
            $update = $actions->updateMetodo($id, $metodo);
            $send_email = $actions->completeSuscripcion($email, $name);
            if ($send_email['success']) {

                if ($update) {
                    $response = array('success' => true, 'message' => 'Método actualizado correctamente.');
                } else {
                    $response = array('success' => true, 'message' => 'No se pudo actualizar el método.', 'data' => $recordExists);
                }
            } else {
                $response = array('success' => false, 'message' => 'No se procesó la solicitud.', 'data' => $recordExists);
            }
        } else {
            $response = array('success' => false, 'message' => 'No se encontró el usuario.', 'user' => $recordExists);
        }
    } catch (Exception $e) {
        $response = array('success' => false, 'message' => 'Error, intente más tarde.');
    }

    // Envía la respuesta en formato JSON
    echo json_encode($response);
}
