<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents("php://input");
    $data = json_decode($input);

    if (!isset($data->titulo) || !isset($data->descripcion) || !isset($data->recompensa_gold) || !isset($data->latitud) || !isset($data->longitud) || !isset($data->creador_id)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Faltan campos obligatorios."]);
        exit();
    }

    try {
        // Iniciamos la Transacción
        $pdo->beginTransaction();

        // 1. Consultar el oro actual del creador usando bloqueo para actualización (FOR UPDATE)
        $stmt_check = $pdo->prepare("SELECT gold FROM users WHERE id = ? FOR UPDATE");
        $stmt_check->execute([$data->creador_id]);
        $user = $stmt_check->fetch();

        // 2. Verificar si tiene suficiente oro
        if (!$user || $user['gold'] < $data->recompensa_gold) {
            $pdo->rollBack(); // Deshacemos transacción si no hay saldo
            echo json_encode(["status" => "error", "message" => "No tienes suficiente oro para publicar esta misión."]);
            exit();
        }

        // 3. Restar el oro del usuario
        $stmt_update = $pdo->prepare("UPDATE users SET gold = gold - ? WHERE id = ?");
        $stmt_update->execute([$data->recompensa_gold, $data->creador_id]);

        // 4. Insertar la nueva misión
        $stmt_insert = $pdo->prepare("INSERT INTO quests (creador_id, titulo, descripcion, recompensa_gold, latitud, longitud, estado) VALUES (?, ?, ?, ?, ?, ?, 'disponible')");
        $stmt_insert->execute([
            $data->creador_id,
            $data->titulo,
            $data->descripcion,
            $data->recompensa_gold,
            $data->latitud,
            $data->longitud
        ]);

        // 5. Confirmar transacción si todo fue exitoso
        $pdo->commit();
        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Misión creada exitosamente y oro descontado."]);

    } catch (\PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error de base de datos: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>