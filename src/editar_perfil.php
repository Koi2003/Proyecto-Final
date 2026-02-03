<?php
session_start();
require 'bd.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$id_usuario = $_SESSION['user_id'];
$mensaje = '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password_nueva = $_POST['password'];

    try {
        if (!empty($password_nueva)) {
            $pass_cifrada = password_hash($password_nueva, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET nombre = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $email, $pass_cifrada, $id_usuario]);
        } else {

            $sql = "UPDATE users SET nombre = ?, email = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nombre, $email, $id_usuario]);
        }


        $_SESSION['user_nombre'] = $nombre;


        $mensaje = "¡Perfil actualizado con éxito!";


        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id_usuario]);
        $usuario = $stmt->fetch();

    } catch (PDOException $e) {
        $mensaje = "Error: Ese email ya está en uso.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Nunito:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <article class="container-fluid">

            <a class="navbar-brand" href="index.html">SideQuest</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <section class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link active" href="index.html">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Entradas.html">Entradas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="AcercaDe.html">Acerca de</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Contacto.html">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="perfil.php">
                            <i class="bi bi-person-circle"></i> Perfil
                        </a>
                    </li>
                </ul>
            </section>
        </article>
    </nav>


    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-1 shadow p-4 bg-white">
                    <h3 class="text-center mb-4">Editar Mis Datos</h3>

                    <?php if ($mensaje): ?>
                        <div class="alert alert-info text-center">
                            <?= $mensaje ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre de Héroe</label>
                            <input type="text" name="nombre" class="form-control" required
                                value="<?= htmlspecialchars($usuario['nombre']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo (Lechuza)</label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($usuario['email']) ?>">
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Cambiar Contraseña</label>
                            <input type="password" name="password" class="form-control">
                            <div class="form-text">Solo escribe aquí si quieres una nueva clave.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success fw-bold">Guardar Cambios</button>
                            <a href="perfil.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
</body>

</html>