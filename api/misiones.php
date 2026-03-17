<?php
require_once 'config.php';

// Endpoint GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare("SELECT id, creador_id, titulo, descripcion, recompensa_gold, latitud, longitud, estado FROM quests WHERE estado = 'disponible'");
        $stmt->execute();
        $misiones = $stmt->fetchAll();
        
        echo json_encode(["status" => "success", "data" => $misiones]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Error de base de datos: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
