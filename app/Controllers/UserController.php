<?php
// require_once '/app/Models/User.php';
require_once './app/Models/User.php';
require_once './config/Utils.php';

use Firebase\JWT\JWT;

class UserController
{
    private $userModel;
    private $utils;

    public function __construct($db)
    {
        $this->userModel = new User($db);
        $this->utils = new Utils();
    }
    private function validarCampos($data)
    {
        // Verifica si la clave 'body' existe en $data y contiene los campos necesarios
        return isset($data['nombre'], $data['apellido'], $data['email'], $data['password'], $data['password_repeat'], $data['userType']);
    }
    public function crearUsuario($request)
    {
        $data = $request['body'];

        // Validar campos
        if (!$this->validarCampos($data)) {
            return $this->jsonResponse(['status' => 500], ['error' => 'Por favor, complete todos los campos.']);
        }

        // Validar email
        if (!$this->validarEmail($data['email'])) {
            return $this->jsonResponse(['status' => 400], ['error' => 'El email no es válido, intente de nuevo.']);
        }

        // Comparar contraseñas
        if ($data['password'] !== $data['password_repeat']) {
            return $this->jsonResponse(['status' => 400], ['error' => 'Las contraseñas no coinciden, intente de nuevo.']);
        }

        // Hash de contraseñas
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['repeat_password'] = $data['password'];

        // Verificar si el usuario existe
        if ($this->userModel->checkUserExists($data['email'])) {
            return $this->jsonResponse(['status' => 400], ['error' => 'Usuario registrado, inicia sesión']);
        }

        // Crear usuario
        if ($this->userModel->createUser($data)) {
            $this->utils->sendEmail($data['email']);
            return $this->jsonResponse(['status' => 200], ['message' => 'Bienvenido a la plataforma, verifica tu cuenta']);
        } else {
            return $this->jsonResponse(['status' => 500], ['error' => 'Error al crear el usuario']);
        }
    }

    public function loginUser($request)
    {
        $data = $request['body'];

        // Validar email
        if (!$this->validarEmail($data['email'])) {
            return $this->jsonResponse(['status' => 400], ['error' => 'El email no es válido, intente de nuevo.']);
        }

        // Validar contraseña
        if (empty($data['password'])) {
            return $this->jsonResponse(['status' => 400], ['error' => 'Ingrese una contraseña para continuar']);
        }

        // Buscar usuario por email
        $user = $this->userModel->findUserByEmail($data['email']);
        if (!$user) {
            return $this->jsonResponse(['status' => 400], ['error' => 'Usuario no encontrado']);
        }

        // Verificar contraseña
        if (!password_verify($data['password'], $user['password'])) {
            return $this->jsonResponse(['status' => 401], ['error' => 'Contraseña incorrecta, intente de nuevo']);
        }
        // $verificar = $this->userModel->verifyData($data['email']);
        // if (!$verificar) {
        //     return $this->jsonResponse(['status' => 400], ['error' => 'Usuario no verificado']);
        // }
        // Generar token
        $this->userModel->updateLogin($data['email']);
        $token = $this->generateToken($user);

        return $this->jsonResponse(['status' => 200], [
            'token' => $token,
            'email' => $user['email'],
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'tipo' => $user['id_tipo_usuario'],
            'idusuario' => $user['id'],
            'telefono' => $user['tel'],
            'perfil' => $user['perfil'],
            'about' => $user['about'],
            'iduser' => $user['id']
        ]);
    }
    public function recoverPassword($request)
    {
        $data = $request['body'];

        // Validar email


        // Buscar usuario por email
        $user = $this->userModel->findUserByEmail($data['email']);
        if (!$user) {

            $this->utils->jsonResponse(200, ["message" => "Ususario no encontrado, introduce una contraseña válida ", "success" => false]);
        }
        $enviarEmail = $this->utils->recoverEmail($data['email']);
        if (!$enviarEmail) {
            $this->utils->jsonResponse(200, ["message" => "Ocurrión un error, intenta de nuevo", "success" => false]);
        }
        $this->utils->jsonResponse(200, ["message" => "Hemos enviado un mensaje a tu bandeja de entrada. Revisa tu correo, sigue los pasos para cambiar tu contraseña y accede para explorar todos nuestros productos.", "success" => true]);
    }
    public function searchEmail($request)
    {
        $data = $request['body'];
        $email = $this->userModel->findUserByEmail($data['email']);
        if (!$email) {
            $this->utils->jsonResponse(200, ["message" => "Usuario no encontrado " . $data['email'], "success" => false]);
        }
        $this->utils->jsonResponse(200, ["message" => "Usuario encontrado " . $data['email'], "success" => true]);
    }
    public function changePassword($request)
    {
        $data = $request['body'];

        // Validar email


        // Buscar usuario por email
        $user = $this->userModel->findUserByEmail($data['email']);
        if (!$user) {

            $this->utils->jsonResponse(200, ["message" => "Correo incorrecto, intente de nuevo ", "success" => false]);
        }
        $enviarEmail = $this->utils->recoverEmail($data['email']);
        if (!$enviarEmail) {
            $this->utils->jsonResponse(200, ["message" => "Ocurrión un error, intenta de nuevo", "success" => false]);
        }
        $this->utils->jsonResponse(200, ["message" => "Hemos enviado un mensaje a tu bandeja de entrada. Revisa tu correo, sigue los pasos para cambiar tu contraseña y accede para explorar todos nuestros productos.", "success" => true]);
    }
    public function activarCuenta($request)
    {
        $data = $request['body'];

        // Validar el email
        if (!$this->validarEmail($data['email'])) {
            return $this->jsonResponse(['status' => 400], ['error' => 'El email no es válido, intente de nuevo.']);
        }

        // Buscar usuario por email
        $user = $this->userModel->findUserByEmail($data['email']);
        if (!$user) {
            return $this->jsonResponse(['status' => 400], ['error' => 'Usuario no encontrado, intenta registrarte']);
        }

        // Actualizar el estado de verificación
        $updated = $this->userModel->updateData("is_verify = 1", "email", $data['email']);

        if ($updated) {
            return $this->jsonResponse(['status' => 200], ['message' => 'Verificación de cuenta exitosa']);
        } else {
            return $this->jsonResponse(['status' => 500], ['error' => 'No se pudo verificar cuenta, intenta de nuevo']);
        }
    }
    private function validarEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    private function jsonResponse($status, $data = [])
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


    private function generateToken($user)
    {
        $payload = [
            'exp' => time() + 3600,
            'data' => $user
        ];
        return JWT::encode($payload, 'secret', 'HS256');
    }
    public function searchUserId($request)
    {
        $data = $request['body'];
        $searchUser = $this->userModel->findUserId($data['id_usuario']);
        if (!$searchUser) {
            return $this->utils->jsonResponse(400, ['error' => 'Error al actualizar producto']);
        }

        return $this->utils->jsonResponse(200, ['message' => [$searchUser]]);
    }
    public function updateDatas($request)
    {
        $data = $request['body'];
        $updateData = $this->userModel->updateDatasItem($data);
        if (!$updateData) {
            return $this->utils->jsonResponse(400, ['error' => $updateData]);
        }
        return $this->utils->jsonResponse(200, ['message' => 'Datos actualizados']);
    }
    public function updateFotoPerfil($request)
    {
        $data = $request['body'];
        $updateData = $this->userModel->updateDataFoto($data);
        if (!$updateData) {
            return $this->utils->jsonResponse(400, ['error' => $updateData]);
        }
        return $this->utils->jsonResponse(200, ['message' => 'Contraseña actualizada']);
    }
    public function updatePass($request)
    {
        $data = $request['body'];

        // Hash de contraseñas
        if ($data['password'] != $data['repeat_password']) {
            return $this->utils->jsonResponse(400, ['error' => 'Las contraseñas no coinciden']);
        }
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['repeat_password'] = $data['password'];
        $updateData = $this->userModel->updatePassword($data);
        if (!$updateData) {
            return $this->utils->jsonResponse(400, ['error' => 'Error al actualizar contraseña, itenta nuevamente']);
        }
        return $this->utils->jsonResponse(200, ['message' => 'Tu contraseña se a actualizado']);
    }
    public function updatePassword($request)
    {
        $data = $request['body'];

        // Hash de contraseñas
        if ($data['password'] != $data['repeat_password']) {
            return $this->utils->jsonResponse(200, ['message' => 'Las contraseñas no coinciden', 'success' => false]);
        }
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['repeat_password'] = $data['password'];
        $updateData = $this->userModel->updatePasswordEmail($data);
        if (!$updateData) {
            return $this->utils->jsonResponse(200, ['message' => 'Error al actualizar contraseña, itenta nuevamente', 'success' => false]);
        }
        return $this->utils->jsonResponse(200, ['message' => 'Tu contraseña se a actualizado', 'success' => true]);
    }
}
