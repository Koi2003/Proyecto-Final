<?php
session_start();
require 'bd.php';

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['user_id'];


if (isset($_POST['borrar_cuenta'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id_usuario]);

    session_destroy();
    header('Location: index.html');
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600&family=Nunito:wght@400;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
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
                        <a class="nav-link" href="index.html">Inicio</a>
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
                        <a class="nav-link active" href="perfil.php">
                            <i class="bi bi-person-circle"></i> Perfil
                        </a>
                    </li>
                </ul>
            </section>
        </article>
    </nav>


    <main class="container mt-5">
        <h2 class="text-center mb-4" style="font-family: 'Fredoka'">Perfil</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-1 shadow p-4 bg-white text-center">


                    <h3>
                        <?= htmlspecialchars($usuario['nombre']) ?>
                    </h3>
                    <p class="text-muted">
                        <?= htmlspecialchars($usuario['email']) ?>
                    </p>

                    <div class="row mt-4 mb-4 g-2">
                        <div class="col-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h4 class="text-warning mb-0">💰</h4>
                                <small class="fw-bold">
                                    <?= $usuario['gold'] ?> Gold
                                </small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h4 class="text-primary mb-0">⭐</h4>
                                <small class="fw-bold">Nivel
                                    <?= $usuario['nivel'] ?>
                                </small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <h4 class="text-success mb-0">📜</h4>
                                <small class="fw-bold">
                                    <?= strtoupper($usuario['rol']) ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="editar_perfil.php" class="btn btn-info text-white fw-bold px-4">
                            <i class="bi bi-pencil-square"></i> Editar Datos
                        </a>

                        <form method="POST"
                            onsubmit="return confirm('¡CUIDADO! \n\nSi borras tu cuenta perderás todo tu oro, nivel y misiones.\n\n¿Estás seguro de querer abandonar el gremio?');">
                            <button type="submit" name="borrar_cuenta" class="btn btn-outline-danger px-4">
                                <i class="bi bi-trash3-fill"></i> Borrar Cuenta
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </main>
</body>

</html>