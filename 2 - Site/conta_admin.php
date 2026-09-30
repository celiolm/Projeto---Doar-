<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'superadmin') {
    header('Location: login.php');
    exit;
}
require_once 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];
$erro    = '';
$sucesso = '';

$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    // ── Atualizar dados pessoais ──
    if ($acao === 'dados') {
        $nome        = trim($_POST['nome']       ?? '');
        $tel         = trim($_POST['telefone']    ?? '');

        if (!$nome) {
            $erro = 'O nome é obrigatório.';
        } else {
            $pdo->prepare("UPDATE usuario SET nome=?, tel=? WHERE id_usuario=?")
                ->execute([$nome, $tel, $id_usuario]);
            $_SESSION['nome'] = $nome;
            $sucesso = 'Dados atualizados com sucesso!';
            $stmt->execute([$id_usuario]);
            $usuario = $stmt->fetch();
        }
    }

    // ── Alterar senha ──
    if ($acao === 'senha') {
        $senha_atual = $_POST['senha_atual'] ?? '';
        $nova_senha  = $_POST['nova_senha']  ?? '';
        $confirmar   = $_POST['confirmar']   ?? '';

        if (!password_verify($senha_atual, $usuario['senha'])) {
            $erro = 'Senha atual incorreta.';
        } elseif (strlen($nova_senha) < 6) {
            $erro = 'A nova senha deve ter pelo menos 6 caracteres.';
        } elseif ($nova_senha !== $confirmar) {
            $erro = 'As senhas não coincidem.';
        } else {
            $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE usuario SET senha=? WHERE id_usuario=?")->execute([$hash, $id_usuario]);
            $sucesso = 'Senha alterada com sucesso!';
        }
    }

    // ── Gerenciar outros admins ──
    if ($acao === 'novo_admin') {
        $email_admin = trim($_POST['email_admin'] ?? '');
        if ($email_admin) {
            $check = $pdo->prepare("SELECT id_usuario, tipo_usuario FROM usuario WHERE email = ?");
            $check->execute([$email_admin]);
            $u = $check->fetch();
            if (!$u) {
                $erro = 'Usuário não encontrado com este e-mail.';
            } elseif ($u['tipo_usuario'] === 'superadmin') {
                $erro = 'Este usuário já é superadmin.';
            } else {
                $pdo->prepare("UPDATE usuario SET tipo_usuario = 'superadmin' WHERE id_usuario = ?")
                    ->execute([$u['id_usuario']]);
                $sucesso = 'Usuário promovido a superadmin!';
            }
        }
    }

    if ($acao === 'remover_admin') {
        $id_remover = (int)($_POST['id_remover'] ?? 0);
        if ($id_remover && $id_remover !== $id_usuario) {
            $pdo->prepare("UPDATE usuario SET tipo_usuario = 'comum' WHERE id_usuario = ?")
                ->execute([$id_remover]);
            $sucesso = 'Acesso admin removido.';
        } else {
            $erro = 'Não é possível remover seu próprio acesso.';
        }
    }
}

// Lista de admins
$admins = $pdo->query("SELECT id_usuario, nome, email FROM usuario WHERE tipo_usuario = 'superadmin' ORDER BY nome")->fetchAll();

$logado   = true;
$nome_ses = explode(' ', $_SESSION['nome'])[0];
$is_admin = true;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Conta Admin</title>
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
    <link rel="stylesheet" href="css/header_footer.css">
    <style>
        .conta-wrapper { max-width:750px; margin:100px auto 40px; padding:0 20px; }
        .conta-card {
            background:#fff; border-radius:16px;
            box-shadow:var(--sombra-suave); padding:30px; margin-bottom:25px;
        }
        .conta-card h2 {
            font-family:'Arvo',serif; font-size:1.2rem;
            color:var(--texto-escuro); margin-bottom:20px;
            padding-bottom:12px; border-bottom:2px solid #e8f5e9;
            display:flex; align-items:center; gap:10px;
        }
        .conta-card h2 i { color:var(--cor-primaria); }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:15px; }
        .form-group { margin-bottom:16px; }
        .form-group label { display:block; font-size:.88rem; font-weight:600;
            color:var(--texto-escuro); margin-bottom:6px; }
        .form-group label i { color:var(--cor-primaria); margin-right:4px; }
        .form-group input {
            width:100%; padding:11px 14px; border:1px solid #ddd;
            border-radius:8px; font-size:.93rem; font-family:'Roboto',sans-serif;
            background:var(--cinza-input); outline:none; transition:border .2s;
        }
        .form-group input:focus { border-color:var(--cor-primaria); background:#fff; }
        .form-group input[readonly] { background:#f0f0f0; color:#888; cursor:not-allowed; }
        .btn-salvar {
            background:var(--cor-primaria); color:#fff; border:none;
            padding:12px 28px; border-radius:8px; font-size:.95rem;
            font-weight:700; cursor:pointer; transition:background .2s;
            display:inline-flex; align-items:center; gap:8px;
        }
        .btn-salvar:hover { background:var(--verde-escuro); }
        .btn-voltar {
            display:inline-flex; align-items:center; gap:7px;
            color:var(--cor-primaria); text-decoration:none;
            font-weight:600; font-size:.9rem; margin-bottom:22px;
        }
        .btn-voltar:hover { text-decoration:underline; }
        .info-readonly {
            background:#f8f9fa; border-radius:10px; padding:14px 18px;
            margin-bottom:16px; font-size:.9rem; color:#555;
        }
        .info-readonly span { font-weight:700; color:var(--texto-escuro); }
        .admin-item {
            display:flex; align-items:center; justify-content:space-between;
            padding:12px 16px; border-radius:10px; background:#f8f9fa;
            margin-bottom:10px;
        }
        .admin-item .info { font-size:.9rem; }
        .admin-item .info strong { display:block; color:var(--texto-escuro); }
        .admin-item .info small { color:#888; }
        .btn-remover {
            background:#f8d7da; color:#721c24; border:none;
            padding:6px 12px; border-radius:6px; font-size:.82rem;
            font-weight:600; cursor:pointer; transition:background .2s;
        }
        .btn-remover:hover { background:#f5c6cb; }
        .badge-voce {
            background:#e8f5e9; color:var(--cor-primaria);
            font-size:.75rem; font-weight:700; padding:2px 8px;
            border-radius:20px; margin-left:8px;
        }
        @media(max-width:600px){ .form-row { grid-template-columns:1fr; } }
    </style>
</head>
<body style="background:var(--cinza-claro);">

<?php include 'header.php'; ?>

<div class="conta-wrapper">

    <a href="painel_admin.php" class="btn-voltar">
        <i class="fas fa-arrow-left"></i> Voltar ao Painel
    </a>

    <h1 style="margin-bottom:25px; color:var(--texto-escuro);">
        <i class="fas fa-shield-alt" style="color:var(--cor-primaria);"></i> Conta Admin
    </h1>

    <?php if ($erro): ?>
        <div class="alert-erro" style="position:static;border-radius:10px;margin-bottom:20px;">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <div class="alert-sucesso" style="position:static;border-radius:10px;margin-bottom:20px;">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($sucesso) ?>
        </div>
    <?php endif; ?>

    <!-- Dados fixos -->
    <div class="conta-card">
        <h2><i class="fas fa-id-card"></i> Dados da Conta</h2>
        <div class="info-readonly">CPF: <span><?= htmlspecialchars($usuario['cpf']) ?></span></div>
        <div class="info-readonly">E-mail: <span><?= htmlspecialchars($usuario['email']) ?></span></div>
        <small style="color:#aaa;">CPF e e-mail não podem ser alterados.</small>
    </div>

    <!-- Dados editáveis -->
    <div class="conta-card">
        <h2><i class="fas fa-user-edit"></i> Editar Dados</h2>
        <form method="POST" action="conta_admin.php">
            <input type="hidden" name="acao" value="dados">
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1;">
                    <label><i class="fas fa-user"></i> Nome Completo *</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label><i class="fas fa-phone"></i> Telefone</label>
                    <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['tel']) ?>">
                </div>
            </div>
            <button type="submit" class="btn-salvar">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
        </form>
    </div>

    <!-- Alterar senha -->
    <div class="conta-card">
        <h2><i class="fas fa-lock"></i> Alterar Senha</h2>
        <form method="POST" action="conta_admin.php">
            <input type="hidden" name="acao" value="senha">
            <div class="form-group">
                <label><i class="fas fa-key"></i> Senha Atual *</label>
                <input type="password" name="senha_atual" placeholder="Digite sua senha atual" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Nova Senha *</label>
                    <input type="password" name="nova_senha" placeholder="Mínimo 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Confirmar Nova Senha *</label>
                    <input type="password" name="confirmar" placeholder="Repita a nova senha" required>
                </div>
            </div>
            <button type="submit" class="btn-salvar">
                <i class="fas fa-key"></i> Alterar Senha
            </button>
        </form>
    </div>

    <!-- Gerenciar admins -->
    <div class="conta-card">
        <h2><i class="fas fa-users-cog"></i> Gerenciar Administradores</h2>

        <!-- Promover usuário -->
        <form method="POST" action="conta_admin.php" style="margin-bottom:25px;">
            <input type="hidden" name="acao" value="novo_admin">
            <div class="form-group">
                <label><i class="fas fa-envelope"></i> E-mail do usuário para promover a Admin</label>
                <div style="display:flex; gap:10px;">
                    <input type="email" name="email_admin" placeholder="email@exemplo.com" style="flex:1;">
                    <button type="submit" class="btn-salvar" style="white-space:nowrap;">
                        <i class="fas fa-user-shield"></i> Promover
                    </button>
                </div>
            </div>
        </form>

        <!-- Lista de admins -->
        <p style="font-size:.88rem; font-weight:700; color:#666; margin-bottom:12px;">
            Administradores atuais (<?= count($admins) ?>):
        </p>
        <?php foreach ($admins as $admin): ?>
        <div class="admin-item">
            <div class="info">
                <strong>
                    <?= htmlspecialchars($admin['nome']) ?>
                    <?php if ($admin['id_usuario'] == $id_usuario): ?>
                        <span class="badge-voce">Você</span>
                    <?php endif; ?>
                </strong>
                <small><?= htmlspecialchars($admin['email']) ?></small>
            </div>
            <?php if ($admin['id_usuario'] != $id_usuario): ?>
            <form method="POST" action="conta_admin.php"
                  onsubmit="return confirm('Remover acesso admin de <?= htmlspecialchars($admin['nome']) ?>?')">
                <input type="hidden" name="acao" value="remover_admin">
                <input type="hidden" name="id_remover" value="<?= $admin['id_usuario'] ?>">
                <button type="submit" class="btn-remover">
                    <i class="fas fa-user-minus"></i> Remover Admin
                </button>
            </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?php include 'footer.php'; ?>

<script>
document.getElementById('telefone').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    v = v.replace(/^(\d{2})(\d)/, '($1) $2');
    v = v.replace(/(\d{5})(\d{1,4})$/, '$1-$2');
    this.value = v;
});
</script>
</body>
</html>
