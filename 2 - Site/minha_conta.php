<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
require_once 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];
$erro    = '';
$sucesso = '';

// Busca dados atuais
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';

    // ── Atualizar dados pessoais ──
    if ($acao === 'dados') {
        $nome        = trim($_POST['nome']        ?? '');
        $tel         = trim($_POST['telefone']     ?? '');
        $cep         = preg_replace('/\D/', '', $_POST['cep'] ?? '');
        $logradouro  = trim($_POST['rua']          ?? '');
        $numero      = trim($_POST['numero']       ?? '');
        $complemento = trim($_POST['complemento']  ?? '');
        $bairro      = trim($_POST['bairro']       ?? '');
        $localidade  = trim($_POST['cidade']       ?? '');
        $uf          = trim($_POST['uf']           ?? '');

        if (!$nome || !$logradouro || !$numero || !$bairro || !$localidade || !$uf) {
            $erro = 'Preencha todos os campos obrigatórios.';
        } else {
            $cep_fmt = strlen($cep) === 8 ? substr($cep,0,5).'-'.substr($cep,5) : $usuario['cep'];
            $pdo->prepare("
                UPDATE usuario SET nome=?, tel=?, cep=?, logradouro=?, numero=?,
                complemento=?, bairro=?, localidade=?, uf=? WHERE id_usuario=?
            ")->execute([$nome, $tel, $cep_fmt, $logradouro, $numero,
                         $complemento, $bairro, $localidade, $uf, $id_usuario]);
            $_SESSION['nome'] = $nome;
            $sucesso = 'Dados atualizados com sucesso!';
            $stmt->execute([$id_usuario]);
            $usuario = $stmt->fetch();
        }
    }

    // ── Alterar senha ──
    if ($acao === 'senha') {
        $senha_atual  = $_POST['senha_atual']  ?? '';
        $nova_senha   = $_POST['nova_senha']   ?? '';
        $confirmar    = $_POST['confirmar']     ?? '';

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
}

$logado   = true;
$nome_ses = explode(' ', $_SESSION['nome'])[0];
$is_admin = false;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Minha Conta</title>
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
        .form-group input, .form-group select {
            width:100%; padding:11px 14px; border:1px solid #ddd;
            border-radius:8px; font-size:.93rem; font-family:'Roboto',sans-serif;
            background:var(--cinza-input); outline:none; transition:border .2s;
        }
        .form-group input:focus, .form-group select:focus {
            border-color:var(--cor-primaria); background:#fff;
        }
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
        @media(max-width:600px){ .form-row { grid-template-columns:1fr; } }
    </style>
</head>
<body style="background:var(--cinza-claro);">

<?php include 'header.php'; ?>

<div class="conta-wrapper">

    <a href="painel_usuario.php" class="btn-voltar">
        <i class="fas fa-arrow-left"></i> Voltar ao Painel
    </a>

    <h1 style="margin-bottom:25px; color:var(--texto-escuro);">Minha Conta</h1>

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

    <!-- Dados fixos (não editáveis) -->
    <div class="conta-card">
        <h2><i class="fas fa-id-card"></i> Dados da Conta</h2>
        <div class="info-readonly">CPF: <span><?= htmlspecialchars($usuario['cpf']) ?></span></div>
        <div class="info-readonly">E-mail: <span><?= htmlspecialchars($usuario['email']) ?></span></div>
        <div class="info-readonly">Data de Nascimento: <span><?= date('d/m/Y', strtotime($usuario['data_nasc'])) ?></span></div>
        <small style="color:#aaa;">CPF, e-mail e data de nascimento não podem ser alterados.</small>
    </div>

    <!-- Dados pessoais editáveis -->
    <div class="conta-card">
        <h2><i class="fas fa-user-edit"></i> Editar Dados Pessoais</h2>
        <form method="POST" action="minha_conta.php">
            <input type="hidden" name="acao" value="dados">
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1;">
                    <label><i class="fas fa-user"></i> Nome Completo *</label>
                    <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Telefone</label>
                    <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario['tel']) ?>">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-search-location"></i> CEP</label>
                    <div style="display:flex; gap:8px;">
                        <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($usuario['cep']) ?>" maxlength="9" style="flex:1;">
                        <button type="button" id="btnBuscarCep" class="btn-salvar" style="padding:11px 14px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label><i class="fas fa-road"></i> Logradouro *</label>
                    <input type="text" id="rua" name="rua" value="<?= htmlspecialchars($usuario['logradouro']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Número *</label>
                    <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($usuario['numero']) ?>" maxlength="6" required>
                </div>
                <div class="form-group">
                    <label>Bairro *</label>
                    <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($usuario['bairro']) ?>" required>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label>Complemento</label>
                    <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($usuario['complemento']) ?>">
                </div>
                <div class="form-group">
                    <label>Cidade *</label>
                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($usuario['localidade']) ?>" required>
                </div>
                <div class="form-group">
                    <label>UF *</label>
                    <select id="uf" name="uf" required>
                        <option value="">UF</option>
                        <?php
                        $ufs = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG',
                                'PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
                        foreach ($ufs as $u_uf):
                        ?>
                        <option value="<?= $u_uf ?>" <?= $usuario['uf'] === $u_uf ? 'selected' : '' ?>><?= $u_uf ?></option>
                        <?php endforeach; ?>
                    </select>
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
        <form method="POST" action="minha_conta.php">
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

</div>

<?php include 'footer.php'; ?>

<script>
// Máscara telefone
document.getElementById('telefone').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    v = v.replace(/^(\d{2})(\d)/, '($1) $2');
    v = v.replace(/(\d{5})(\d{1,4})$/, '$1-$2');
    this.value = v;
});

// Máscara CEP
document.getElementById('cep').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    v = v.replace(/^(\d{5})(\d)/, '$1-$2');
    this.value = v;
});

// ViaCEP
document.getElementById('btnBuscarCep').addEventListener('click', function () {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    if (cep.length !== 8) { alert('CEP inválido.'); return; }
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(r => r.json())
        .then(data => {
            if (data.erro) { alert('CEP não encontrado.'); return; }
            document.getElementById('rua').value    = data.logradouro || '';
            document.getElementById('bairro').value = data.bairro     || '';
            document.getElementById('cidade').value = data.localidade || '';
            const uf = document.getElementById('uf');
            for (let i = 0; i < uf.options.length; i++) {
                if (uf.options[i].value === data.uf) { uf.selectedIndex = i; break; }
            }
            document.getElementById('numero').focus();
        })
        .catch(() => alert('Erro ao buscar CEP.'));
});
</script>
</body>
</html>
