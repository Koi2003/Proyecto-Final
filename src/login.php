<?php
session_start();
require 'bd.php';

$error = '';
$email_preservado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_preservado = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email_preservado]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        header('Location: Entradas.html');
        exit;
    } else {
        $error = "Credenciales incorrectas.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login - SideQuest</title>
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
            </section>
        </nav>
    </header>

    <main class="container mt-5">
        <section class="container mt-5 mb-5 text-center">
            <h1>Identifícate</h1>
        </section>

        <div class="row justify-content-center">
            <div class="col-md-6 mb-5">
                <div class="card-1 shadow p-4 bg-white">
                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Correo</label>
                            <input type="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($email_preservado) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-info text-white fw-bold px-5 py-2">Entrar</button>
                        </div>
                        <p class="mt-3 text-center">¿Eres nuevo?<a href="registro.php"
                                style="color: var(--btn-brown);">Regístrate</a></p>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>