<?php

session_start();
require 'bd.php';

$error = '';
$nombre_preservado = '';
$email_preservado = '';
$rol_preservado = 'worker';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_preservado = $_POST['nombre'] ?? '';
    $email_preservado = $_POST['email'] ?? '';
    $rol_preservado = $_POST['rol'] ?? 'worker';
    $password = $_POST['password'] ?? '';

    $pass_cifrada = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users (nombre, email, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nombre_preservado, $email_preservado, $pass_cifrada, $rol_preservado]);

        header('Location: login.php?registrado=1');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = "Ese correo ya está en uso.";
        } else {
            $error = "Error de base de datos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro - SideQuest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Nunito:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <section class="container-fluid">
                <a class="navbar-brand" href="index.html">SideQuest</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="index.html">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link active" href="registro.php">Únete</a></li>
                    </ul>
                </div>
            </section>
        </nav>
    </header>

    <main class="container mt-5">
        <section class="container mt-5 mb-5 text-center">
            <h1>Únete al Gremio</h1>
        </section>

        <div class="row justify-content-center">
            <div class="col-md-7 mb-5">
                <div class="card-1 shadow p-4 bg-white">

                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required
                                value="<?= htmlspecialchars($nombre_preservado) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo</label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($email_preservado) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tu Camino</label>
                            <select name="rol" class="form-select">
                                <option value="worker" <?= $rol_preservado === 'worker' ? 'selected' : '' ?>>Mercenario
                                    (Hago misiones)</option>
                                <option value="client" <?= $rol_preservado === 'client' ? 'selected' : '' ?>>Aldeano
                                    (Pido ayuda)</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info text-white fw-bold px-5 py-2">Firmar
                                Contrato</button>
                        </div>
                        <p class="mt-3 text-center">¿Ya tienes cuenta? <a href="login.php"
                                style="color: var(--btn-brown);">Entra aquí</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>