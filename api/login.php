<?php
require_once 'config.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    // 1. Extraemos también la columna 'gold'
    $stmt = $pdo->prepare("SELECT id, nombre, rol, password, gold FROM users WHERE email = ?");
    $stmt->execute([$data->email]);
    $user = $stmt->fetch();

    if ($user && password_verify($data->password, $user['password'])) {
        echo json_encode([
            "status" => "success",
            "message" => "Login correcto",
            "data" => [
                "id" => $user['id'],
                "nombre" => $user['nombre'],
                "rol" => $user['rol'],
                "gold" => $user['gold'] // 2. Devolvemos el oro en el JSON
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Credenciales incorrectas"]);
    }
}
?>
