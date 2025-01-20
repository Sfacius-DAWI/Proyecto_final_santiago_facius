<?php
header("Content-Type: application/json; charset=UTF-8");

include_once 'database.php';

$database = new Database();
$db = $database->getConnection();

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM users";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO users (name, email) VALUES (:name, :email)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        if ($stmt->execute()) {
            echo json_encode(['mensaje' => 'Usuario creado']);
        } else {
            echo json_encode(['mensaje' => 'Error al crear usuario']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        if ($stmt->execute()) {
            echo json_encode(['mensaje' => 'Usuario actualizado']);
        } else {
            echo json_encode(['mensaje' => 'Error al actualizar usuario']);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'];
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            echo json_encode(['mensaje' => 'Usuario eliminado']);
        } else {
            echo json_encode(['mensaje' => 'Error al eliminar usuario']);
        }
        break;
    default:
        echo json_encode(['mensaje' => 'Método no soportado']);
        break;
}
