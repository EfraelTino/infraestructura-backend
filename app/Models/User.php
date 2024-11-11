<?php
require_once './config/Utils.php';

class User
{
    private $db;
    private $utils;

    public function __construct($db)
    {
        $this->db = $db;
        $this->utils = new Utils(); 
    }

    public function findUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Solo se llama a fetch una vez
    }
    public function findUserId($id){
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id ]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Solo se llama a fetch una vez
    }
    public function updateLogin($email)
    {
        $fecha = $this->utils->dataNow();
        $stmt = $this->db->prepare("UPDATE user SET fecha_inicio = ? WHERE email = ?");
        $stmt->execute([$fecha, $email]); // Corregido: los parámetros deben estar en un array

        // Como es un UPDATE, no se usa fetch() aquí
        return $stmt->rowCount(); // Opcional: devuelve el número de filas afectadas

    }
    // Validar si un usuario existe 
    public function checkUserExists($email)
    {
        return self::findUserByEmail($email) !== false;
    }
  public function verifyData($email)
{
    $stm = $this->db->prepare("SELECT is_verify FROM user WHERE email = ?");
    $stm->execute([$email]);
    $result = $stm->fetch(PDO::FETCH_ASSOC);
    $isverify = intval($result['is_verify']);

    if ($isverify === 0) {  // Si el usuario no está verificado
        $this->utils->sendEmail($email);
        return false;  // Retorna false para indicar que el usuario no está verificado
    } else {
        return true;  // Retorna true para indicar que el usuario está verificado
    }
}
    public function updateData($actualizar, $condicion, $valorCondicion)
    {
        $sql = "UPDATE user SET $actualizar WHERE $condicion = :valorCondicion";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':valorCondicion', $valorCondicion, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function createUser($data)
    {
        $fecha = $this->utils->dataNow();
        $stmt = $this->db->prepare(
            "INSERT INTO user (email, id_tipo_usuario, password, repeat_password, fecha_creacion, nombre, apellido) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $success = $stmt->execute([
            $data['email'],
            $data['userType'],
            $data['password'],
            $data['repeat_password'],
            $fecha,
            $data['nombre'],
            $data['apellido']
        ]);

        // Verifica si la inserción fue exitosa
        return $success;
    }

    public function updateDatasItem($data)
    {

        $stmt = $this->db->prepare("UPDATE user SET nombre=?, apellido=?, tel=?, about=? WHERE id =?");
        $stmt->execute([$data['nombres'], $data['apellidos'], $data['telefono'], $data['sobre'], $data['id_user']]);
        return $stmt->rowCount();
    }
    public function updateDataFoto($data)
    {

        $stmt = $this->db->prepare("UPDATE user SET perfil=? WHERE id =?");
        $stmt->execute([$data['foto'], $data['id_user']]);
        return $stmt->rowCount();
    }
    public function updatePassword($data)
    {

        $stmt = $this->db->prepare("UPDATE user SET password=?, repeat_password=? WHERE id =?");
        $stmt->execute([$data['password'], $data['repeat_password'], $data['id_user']]);
        return $stmt->rowCount();
    }
    public function updatePasswordEmail($data)
    {

        $stmt = $this->db->prepare("UPDATE user SET password=?, repeat_password=? WHERE email =?");
        $stmt->execute([$data['password'], $data['repeat_password'], $data['email']]);
        return $stmt->rowCount();
    }
}
