<?php
session_start();
require_once 'conexao.php';

$logado   = isset($_SESSION['id_usuario']);
$nome_ses = $logado ? explode(' ', $_SESSION['nome'])[0] : '';
$is_admin = $logado && $_SESSION['tipo_usuario'] === 'superadmin';

$erro    = '';
$sucesso = '';

// ── Pré-preenche nome/email se logado ──────────────────────────
$pre_nome  = '';
$pre_email = '';
if ($logado) {
    $stmt = $pdo->prepare("SELECT nome, email FROM usuario WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['id_usuario']]);
    $u = $stmt->fetch();
    $pre_nome  = $u['nome']  ?? '';
    $pre_email = $u['email'] ?? '';
}

// ── Processa formulário ────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $assunto  = trim($_POST['assunto']  ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if (!$nome || !$email || !$assunto || !$mensagem) {
        $erro = 'Preencha todos os campos obrigatórios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO contato (nome, email, telefone, assunto, mensagem)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$nome, $email, $telefone, $assunto, $mensagem]);
        $sucesso = 'Mensagem enviada com sucesso! Retornaremos em breve.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Contato</title>
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
        /* ── Header dropdown (igual index.php) ── */
        .saudacao-header {
            display: flex; align-items: center; gap: 8px;
            font-size: .9rem; color: #555; font-weight: 600;
        }
        .saudacao-header strong { color: var(--cor-primaria); }
        .saudacao-header .dropdown { position: relative; }
        .saudacao-header .dropdown-menu {
            display: none; position: absolute; right: 0; top: 110%;
            background: #fff; border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,.12);
            min-width: 170px; z-index: 100; overflow: hidden;
        }
        .saudacao-header .dropdown:hover .dropdown-menu { display: block; }
        .saudacao-header .dropdown-menu a {
            display: block; padding: 11px 16px; color: #333;
            text-decoration: none; font-size: .88rem; font-weight: 500;
            transition: background .15s;
        }
        .saudacao-header .dropdown-menu a:hover { background: #f4f7f6; }
        .saudacao-header .dropdown-menu a.sair { color: var(--vermelho-erro); }

        /* ── Hero contato ── */
        .contato-hero {
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            padding: calc(12vh) 20px 40px; text-align: center; color: #fff;
        }
        .contato-hero h1 { font-family: 'Arvo', serif; font-size: 2.2rem; margin-bottom: 8px; }
        .contato-hero p  { opacity: .85; font-size: 1rem; }

        /* ── Layout grid ── */
        .contato-wrapper {
            max-width: 1050px; margin: 50px auto; padding: 0 20px;
            display: grid; grid-template-columns: 1fr 1.4fr; gap: 35px;
        }

        /* ── Info lateral ── */
        .contato-info-card {
            background: #fff; border-radius: 16px;
            box-shadow: var(--sombra-suave); padding: 32px 28px;
            align-self: start;
        }
        .contato-info-card h2 {
            font-family: 'Arvo', serif; font-size: 1.3rem;
            color: var(--texto-escuro); margin-bottom: 24px;
            padding-bottom: 12px; border-bottom: 2px solid #e8f5e9;
        }
        .info-item {
            display: flex; gap: 15px; align-items: flex-start;
            margin-bottom: 22px;
        }
        .info-item .icone {
            width: 42px; height: 42px; min-width: 42px;
            background: #ffffff; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: var(--cor-primaria); font-size: 1rem;
        }
        .info-item h3 { font-size: .88rem; font-weight: 700; color: var(--texto-escuro); margin-bottom: 4px; }
        .info-item p  { font-size: .87rem; color: #666; line-height: 1.6; margin: 0; }

        .redes-sociais { display: flex; gap: 12px; margin-top: 8px; }
        .rede-btn {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; text-decoration: none; transition: transform .2s;
        }
        .rede-btn:hover { transform: translateY(-3px); }
        .rede-fb { background: #ffffff; color: #1877f2; }
        .rede-ig { background: #ffffff; color: #c2185b; }
        .rede-wa { background: #ffffff; color: #25d366; }

        /* ── Formulário ── */
        .contato-form-card {
            background: #fff; border-radius: 16px;
            box-shadow: var(--sombra-suave); padding: 32px 28px;
        }
        .contato-form-card h2 {
            font-family: 'Arvo', serif; font-size: 1.3rem;
            color: var(--texto-escuro); margin-bottom: 24px;
            padding-bottom: 12px; border-bottom: 2px solid #e8f5e9;
        }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: .88rem; font-weight: 600;
            color: var(--texto-escuro); margin-bottom: 6px;
        }
        .form-group label i { color: var(--cor-primaria); margin-right: 5px; }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%; padding: 11px 14px; border: 1px solid #ddd;
            border-radius: 8px; font-size: .93rem; font-family: 'Roboto', sans-serif;
            background: var(--cinza-input); outline: none; transition: border .2s;
        }
        .form-group input:focus,
        .form-group textarea:focus { border-color: var(--cor-primaria); background: #fff; }
        .form-group textarea { min-height: 120px; resize: vertical; }
        .btn-enviar {
            width: 100%; padding: 13px; background: var(--cor-primaria);
            color: #fff; border: none; border-radius: 10px; font-size: 1rem;
            font-weight: 700; cursor: pointer; transition: background .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-enviar:hover { background: var(--verde-escuro); }

        /* ── Mapa ── */
        .mapa-section {
            max-width: 1050px; margin: 0 auto 50px; padding: 0 20px;
        }
        .mapa-section h2 {
            font-family: 'Arvo', serif; font-size: 1.3rem;
            color: var(--texto-escuro); margin-bottom: 16px;
        }
        .mapa-section iframe {
            border-radius: 16px; box-shadow: var(--sombra-suave);
        }

        /* ── Footer melhorado ── */
        .footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            color: #ccc; padding-top: 55px;
        }
        .container-footer {
            max-width: 1100px; margin: 0 auto; padding: 0 20px 40px;
            display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 35px;
        }
        .footer-brand .minha-logo { height: 38px; margin-bottom: 14px; filter: brightness(0) invert(1); }
        .footer-brand p { font-size: .88rem; line-height: 1.7; color: #aaa; margin-bottom: 18px; }
        .footer-social { display: flex; gap: 10px; }
        .footer-social a {
            width: 36px; height: 36px; border-radius: 8px;
            background: rgba(255,255,255,.08); display: flex;
            align-items: center; justify-content: center;
            color: #ccc; font-size: .95rem; text-decoration: none;
            transition: background .2s, color .2s;
        }
        .footer-social a:hover { background: var(--cor-primaria); color: #fff; }

        .footer-col h4 {
            font-family: 'Arvo', serif; font-size: .95rem;
            color: #fff; margin-bottom: 18px;
            padding-bottom: 8px; border-bottom: 2px solid rgba(255,255,255,.08);
        }
        .footer-col ul { list-style: none; padding: 0; margin: 0; }
        .footer-col ul li { margin-bottom: 10px; }
        .footer-col ul li a {
            color: #aaa; text-decoration: none; font-size: .88rem;
            transition: color .2s; display: flex; align-items: center; gap: 7px;
        }
        .footer-col ul li a::before {
            content: '›'; color: var(--verde-claro); font-size: 1rem;
        }
        .footer-col ul li a:hover { color: #fff; }

        .footer-col .contato-item {
            display: flex; align-items: flex-start; gap: 10px;
            margin-bottom: 12px; font-size: .87rem; color: #aaa;
        }
        .footer-col .contato-item i {
            color: var(--verde-claro); width: 16px; margin-top: 2px; flex-shrink: 0;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.07);
            padding: 18px 20px; text-align: center;
        }
        .footer-bottom-inner {
            max-width: 1100px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 10px;
        }
        .footer-bottom p { font-size: .83rem; color: #666; margin: 0; }
        .footer-bottom .feito-com { font-size: .83rem; color: #666; }
        .footer-bottom .feito-com i { color: #e57373; }

        @media (max-width: 900px) {
            .contato-wrapper { grid-template-columns: 1fr; }
            .container-footer { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
            .container-footer { grid-template-columns: 1fr; }
            .footer-bottom-inner { justify-content: center; text-align: center; }
        }
    </style>
</head>
<body>

<!-- ── Header ── -->
<?php include 'header.php'; ?>

<!-- ── Hero ── -->
<section class="contato-hero">
    <h1><i class="fas fa-envelope-open-text"></i> Fale Conosco</h1>
    <p>Estamos aqui para ajudar e responder suas dúvidas</p>
</section>

<!-- ── Alertas ── -->
<?php if ($erro): ?>
    <div class="alert-erro" style="position:static; margin:20px auto; max-width:1050px; border-radius:10px;">
        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erro) ?>
    </div>
<?php endif; ?>
<?php if ($sucesso): ?>
    <div class="alert-sucesso" style="position:static; margin:20px auto; max-width:1050px; border-radius:10px;">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($sucesso) ?>
    </div>
<?php endif; ?>

<!-- ── Grid principal ── -->
<div class="contato-wrapper">

    <!-- Info lateral -->
    <div class="contato-info-card">
        <h2>Informações de Contato</h2>

        <div class="info-item">
            <div class="icone"><i class="fas fa-map-marker-alt"></i></div>
            <div>
                <h3>Endereço</h3>
                <p>São Paulo - SP, Brasil</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icone"><i class="fas fa-phone"></i></div>
            <div>
                <h3>Telefone</h3>
                <p>(11) 99999-9999</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icone"><i class="fas fa-envelope"></i></div>
            <div>
                <h3>E-mail</h3>
                <p>contato@doarmais.com.br</p>
                <p>suporte@doarmais.com.br</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icone"><i class="fas fa-clock"></i></div>
            <div>
                <h3>Horário de Atendimento</h3>
                <p>Segunda a Sexta: 9h às 18h</p>
                <p>Sábado: 9h às 13h</p>
            </div>
        </div>

        <div class="info-item">
            <div class="icone"><i class="fas fa-share-alt"></i></div>
            <div>
                <h3>Redes Sociais</h3>
                <div class="redes-sociais">
                    <a href="#" class="rede-btn rede-fb" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="rede-btn rede-ig" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="rede-btn rede-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulário -->
    <div class="contato-form-card">
        <h2>Envie uma Mensagem</h2>
        <form method="POST" action="contato.php">
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Nome *</label>
                    <input type="text" name="nome"
                           value="<?= htmlspecialchars($pre_nome) ?>"
                           placeholder="Seu nome completo" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> E-mail *</label>
                    <input type="email" name="email"
                           value="<?= htmlspecialchars($pre_email) ?>"
                           placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Telefone</label>
                    <input type="tel" name="telefone" id="telefone" placeholder="(11) 99999-9999">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> Assunto *</label>
                    <select name="assunto" required>
                        <option value="">Selecione...</option>
                        <option value="Dúvida geral">Dúvida geral</option>
                        <option value="Problema com doação">Problema com doação</option>
                        <option value="Sugestão">Sugestão</option>
                        <option value="Denúncia">Denúncia</option>
                        <option value="Parceria">Parceria</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label><i class="fas fa-comment-dots"></i> Mensagem *</label>
                <textarea name="mensagem" placeholder="Digite sua mensagem..."></textarea>
            </div>

            <button type="submit" class="btn-enviar">
                <i class="fas fa-paper-plane"></i> Enviar Mensagem
            </button>
        </form>
    </div>
</div>

<!-- ── Mapa ── -->
<div class="mapa-section">
    <h2><i class="fas fa-map-marked-alt" style="color:var(--cor-primaria);"></i> Nossa Localização</h2>
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.197476704382!2d-46.654987!3d-23.561354!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94ce59c8da0aa315%3A0xd59f9431f2c9776a!2sAv.%20Paulista%2C%20S%C3%A3o%20Paulo%20-%20SP!5e0!3m2!1spt-BR!2sbr!4v1699999999999!5m2!1spt-BR!2sbr"
        width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
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
</script>
</body>
</html>
