<?php
session_start();
require_once 'conexao.php';

$erro   = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome        = trim($_POST['nome'] ?? '');
    $cpf_limpo = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$cpf = substr($cpf_limpo, 0, 3) . '.' . substr($cpf_limpo, 3, 3) . '.' . substr($cpf_limpo, 6, 3) . '-' . substr($cpf_limpo, 9, 2);
    $email       = trim($_POST['email'] ?? '');
    $data_nasc   = $_POST['nascimento'] ?? '';
    $tel         = trim($_POST['telefone'] ?? '');
    $cep         = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $logradouro  = trim($_POST['rua'] ?? '');
    $numero      = trim($_POST['numero'] ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $bairro      = trim($_POST['bairro'] ?? '');
    $localidade  = trim($_POST['cidade'] ?? '');
    $uf          = trim($_POST['uf'] ?? '');
    $senha       = $_POST['senha'] ?? '';
    $confirmar   = $_POST['confirmar_senha'] ?? '';

    // Validações básicas
    if (strlen($cpf_limpo) !== 11) {
        $erro = 'CPF inválido.';
    } elseif ($senha !== $confirmar) {
        $erro = 'As senhas não coincidem.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha deve ter pelo menos 6 caracteres.';
    } else {
        // Verifica duplicidade
        $stmt = $pdo->prepare("SELECT id_usuario FROM usuario WHERE cpf = ? OR email = ?");
        $stmt->execute([$cpf, $email]);
        if ($stmt->fetch()) {
            $erro = 'CPF ou e-mail já cadastrado.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $cep_fmt = substr($cep, 0, 5) . '-' . substr($cep, 5);

            $stmt = $pdo->prepare("
                INSERT INTO usuario
                    (nome, cpf, email, data_nasc, tel, cep, logradouro, numero, complemento, bairro, localidade, uf, senha)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $nome, $cpf, $email, $data_nasc, $tel,
                $cep_fmt, $logradouro, $numero, $complemento,
                $bairro, $localidade, $uf, $hash
            ]);

            $_SESSION['cadastro_sucesso'] = true;
            header('Location: login.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Criar Conta</title>
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
            <h2>Crie sua conta</h2>
            <p>Faça parte dessa corrente do bem</p>
        </div>

        <?php if ($erro): ?>
            <div class="alert-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form class="login-form" id="formCadastro" method="POST" action="cadastro.php">
            <div class="form-grid">

                <div class="form-group full-width">
                    <label for="nome"><i class="fas fa-user"></i> Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                </div>

                <div class="form-group">
                    <label for="cpf"><i class="fas fa-id-card"></i> CPF</label>
                    <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" maxlength="14" required>
                </div>

                <div class="form-group">
                    <label for="nascimento"><i class="fas fa-calendar-alt"></i> Data de Nascimento</label>
                    <input type="date" id="nascimento" name="nascimento" required>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> E-mail</label>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>

                <div class="form-group">
                    <label for="telefone"><i class="fas fa-phone"></i> Telefone</label>
                    <input type="tel" id="telefone" name="telefone" placeholder="(11) 99999-9999" required>
                </div>

                <div class="form-group full-width">
                    <label><i class="fas fa-map-marker-alt"></i> Endereço</label>
                </div>

                <div class="form-group full-width">
                    <div style="display:flex; gap:8px; align-items:center; justify-content:flex-start;">
    <div style="flex:1;">
        <label for="cep"><i class="fas fa-search-location"></i> CEP</label>
        <div style="display:flex; gap:6px;">
            <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9" style="width:150px;">
            <button type="button" id="btnBuscarCep" class="btn-buscar-cep" style="white-space:nowrap;">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>
    </div>
</div>
                </div>

                <div class="form-group full-width">
                    <input type="text" id="rua" name="rua" placeholder="Rua / Avenida" required>
                </div>

                <div class="form-group">
                    <input type="text" id="numero" name="numero" placeholder="Número" maxlength="6" required>
                </div>

                <div class="form-group">
                    <input type="text" id="bairro" name="bairro" placeholder="Bairro" required>
                </div>

                <div class="form-group full-width">
                    <input type="text" id="complemento" name="complemento" placeholder="Complemento (opcional)">
                </div>

                <div class="form-group">
                    <input type="text" id="cidade" name="cidade" placeholder="Cidade" required>
                </div>

                <div class="form-group">
                    <select id="uf" name="uf" required>
                        <option value="">UF</option>
                        <option value="AC">AC</option> <option value="AL">AL</option>
                        <option value="AP">AP</option> <option value="AM">AM</option>
                        <option value="BA">BA</option> <option value="CE">CE</option>
                        <option value="DF">DF</option> <option value="ES">ES</option>
                        <option value="GO">GO</option> <option value="MA">MA</option>
                        <option value="MT">MT</option> <option value="MS">MS</option>
                        <option value="MG">MG</option> <option value="PA">PA</option>
                        <option value="PB">PB</option> <option value="PR">PR</option>
                        <option value="PE">PE</option> <option value="PI">PI</option>
                        <option value="RJ">RJ</option> <option value="RN">RN</option>
                        <option value="RS">RS</option> <option value="RO">RO</option>
                        <option value="RR">RR</option> <option value="SC">SC</option>
                        <option value="SP">SP</option> <option value="SE">SE</option>
                        <option value="TO">TO</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
                </div>

                <div class="form-group">
                    <label for="confirmar_senha"><i class="fas fa-lock"></i> Confirmar Senha</label>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme sua senha" required>
                </div>
            </div>

            <div class="termos-group">
                <input type="checkbox" id="termos" required>
                <label for="termos">Li e aceito os <a href="#" onclick="abrirTermos(); return false;" style="color:var(--cor-primaria);">Termos de Uso</a></label>
            </div>

            <button type="submit" class="btn-cadastro">
                <i class="fas fa-hand-holding-heart"></i> Criar minha conta
            </button>

            <p style="text-align:center; margin-top:15px;">
                Já tem conta? <a href="login.php" style="color:var(--cor-primaria);">Entrar</a>
            </p>
        </form>
    </div>
</main>

<!-- Modal Termos de Uso -->
<div id="modalTermos" style="display:none; position:fixed; inset:0;
     background:rgba(0,0,0,.6); backdrop-filter:blur(4px);
     -webkit-backdrop-filter:blur(4px); z-index:9000;
     align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:18px; max-width:720px; width:100%;
                max-height:88vh; overflow-y:auto; padding:40px; position:relative;
                box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <button onclick="fecharTermos()"
                style="position:absolute; top:16px; right:20px; background:#f4f7f6;
                       border:none; border-radius:50%; width:36px; height:36px;
                       font-size:1.1rem; cursor:pointer; color:#666;">
            <i class="fas fa-times"></i>
        </button>
        <div id="termosConteudo" style="color:#444; font-size:.93rem; line-height:1.8;">
            <p style="text-align:center; color:#aaa;">
                <i class="fas fa-spinner fa-spin"></i> Carregando...
            </p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
// Máscara CPF
document.getElementById('cpf').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d)/, '$1.$2');
    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    this.value = v;
});

// Máscara Telefone
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

function abrirTermos() {
    const modal = document.getElementById('modalTermos');
    modal.style.display = 'flex';

    if (document.getElementById('termosConteudo').querySelector('.fa-spinner')) {
        fetch('termos.php?modal=1')
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const conteudo = doc.querySelector('.termos-conteudo');
                if (conteudo) {
                    // Remove o botão "Li e aceito" que fecha janela
                    const btnAceitar = conteudo.querySelector('.btn-aceitar');
                    if (btnAceitar) btnAceitar.remove();
                    document.getElementById('termosConteudo').innerHTML = conteudo.innerHTML;
                }
            });
    }
}

function fecharTermos() {
    document.getElementById('modalTermos').style.display = 'none';
}

// Fechar clicando fora
document.getElementById('modalTermos').addEventListener('click', function(e) {
    if (e.target === this) fecharTermos();
});
</script>
</body>
</html>
