<?php
session_start();
require_once 'conexao.php';

if (isset($_SESSION['id_usuario'])) {
    header('Location: ' . ($_SESSION['tipo_usuario'] === 'superadmin' ? 'painel_admin.php' : 'painel_usuario.php'));
    exit;
}

$erro = '';
$sucesso_cadastro = '';

if (isset($_SESSION['cadastro_sucesso'])) {
    $sucesso_cadastro = 'Conta criada com sucesso! Faça login.';
    unset($_SESSION['cadastro_sucesso']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT id_usuario, nome, senha, tipo_usuario, bloqueado, motivo_bloqueio FROM usuario WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        if ($user['bloqueado']) {
            $motivo = $user['motivo_bloqueio'] ? htmlspecialchars($user['motivo_bloqueio']) : 'não informado';
            $erro = 'Sua conta foi bloqueada. Motivo: ' . $motivo . '. Entre em contato pelo suporte.';
        } else {
            $_SESSION['id_usuario']   = $user['id_usuario'];
            $_SESSION['nome']         = $user['nome'];
            $_SESSION['tipo_usuario'] = $user['tipo_usuario'];

            $ip_raw      = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['REMOTE_ADDR'];
            $ip          = trim(explode(',', $ip_raw)[0]);
            $dispositivo = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 100);
            $log = $pdo->prepare("INSERT INTO log_acesso (id_usuario, ip, dispositivo, data_hora) VALUES (?, ?, ?, NOW())");
            $log->execute([$user['id_usuario'], $ip, $dispositivo]);

            header('Location: ' . ($user['tipo_usuario'] === 'superadmin' ? 'painel_admin.php' : 'painel_usuario.php'));
            exit;
        }
    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Entrar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/componentes.css">
    <link rel="stylesheet" href="css/formularios.css">
    <link rel="stylesheet" href="css/paginas.css">
    <link rel="stylesheet" href="css/responsivo.css">
</head>
<body class="body-login">

<header class="header-login">
    <div class="container-header-login">
        <a href="index.php" class="logo-area">
            <img src="imagens/logoDoar.png" alt="Logo Doar+" class="minha-logo">
        </a>
        <a href="index.php" class="btn-voltar-inicio">
            <i class="fas fa-arrow-left"></i> Voltar para Início
        </a>
    </div>
</header>

<main class="login-main">
    <div class="login-container">
        <div class="login-header">
            <h2>Bem-vindo de volta!</h2>
            <p>Entre na sua conta para continuar</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert-erro"><?= $erro ?></div>
        <?php endif; ?>
        <?php if ($sucesso_cadastro): ?>
            <div class="alert-sucesso"><?= htmlspecialchars($sucesso_cadastro) ?></div>
        <?php endif; ?>

        <form class="login-form" method="POST" action="login.php">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> E-mail</label>
                <input type="email" id="email" name="email" placeholder="seu@email.com" required>
            </div>

            <div class="form-group">
                <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
            </div>

            <button type="submit" class="btn-cadastro">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>

            <p style="text-align:center; margin-top:15px;">
                Não tem conta? <a href="cadastro.php" style="color:var(--cor-primaria);">Criar conta</a>
            </p>
        </form>
    </div>
</main>

<footer class="footer-login">
    <div class="container-footer-login">
        <p>&copy; 2026 Doar+ Transforme Vidas através da doação</p>
    </div>
</footer>
</body>
</html>
