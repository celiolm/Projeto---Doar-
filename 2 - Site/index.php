<?php
session_start();

$logado    = isset($_SESSION['id_usuario']);
$nome      = $logado ? explode(' ', $_SESSION['nome'])[0] : ''; // primeiro nome
$is_admin  = $logado && $_SESSION['tipo_usuario'] === 'superadmin';

// Página atual para marcar menu ativo
$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Doe Itens, Mude Histórias</title>
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
        /* ── Hero melhorado ── */
        .hero {
    min-height: 88vh;
    background: url('imagens/fundo1.avif') center center / cover no-repeat;
    display: flex; align-items: center; justify-content: center;
    text-align: center; padding: calc(12vh + 60px) 20px 60px;
    position: relative; overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(4px);
}
.hero-content {
    position: relative; z-index: 1; max-width: 700px;
}
        .hero-content h1 {
            font-family: 'Arvo', serif;
            font-size: clamp(2.2rem, 6vw, 3.8rem);
            color: #fff; line-height: 1.2; margin-bottom: 20px;
        }
        .hero-content h1 span { color: var(--destaque-amarelo); }
        .hero-content p {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            color: rgba(255,255,255,.88); margin-bottom: 35px; line-height: 1.7;
        }
        .hero-btns { display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; }
        .btn-hero-primary {
            background: var(--destaque-amarelo); color: #1a1a1a;
            padding: 14px 32px; border-radius: 50px; font-weight: 700;
            font-size: 1rem; text-decoration: none; transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 15px rgba(254,178,54,.4);
        }
        .btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(254,178,54,.5); }
        .btn-hero-secondary {
            background: rgba(255,255,255,.15); color: #fff;
            padding: 14px 32px; border-radius: 50px; font-weight: 600;
            font-size: 1rem; text-decoration: none; border: 2px solid rgba(255,255,255,.4);
            transition: background .2s;
        }
        .btn-hero-secondary:hover { background: rgba(255,255,255,.25); }

        /* ── Saudação logado ── */
        .saudacao-logado {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3);
            color: #fff; border-radius: 50px; padding: 8px 18px;
            font-size: .95rem; font-weight: 600; margin-bottom: 22px;
        }
        .saudacao-logado i { color: var(--destaque-amarelo); }

        /* ── Stats ── */
        .stats-bar {
            background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }
        .stats-inner {
            max-width: 900px; margin: 0 auto;
            display: flex; justify-content: space-around; flex-wrap: wrap;
            padding: 28px 20px;
        }
        .stat-item { text-align: center; padding: 10px 20px; }
        .stat-item .num {
            font-family: 'Arvo', serif; font-size: 2.2rem;
            font-weight: 700; color: var(--cor-primaria);
        }
        .stat-item .label { font-size: .88rem; color: #777; margin-top: 4px; }

        /* ── Como funciona ── */
        .como-funciona {
            max-width: 1000px; margin: 70px auto; padding: 0 20px; text-align: center;
        }
        .secao-titulo { font-family: 'Arvo', serif; font-size: 1.9rem; color: var(--texto-escuro); margin-bottom: 8px; }
        .secao-sub    { color: #777; font-size: 1rem; margin-bottom: 45px; }
        .steps-grid   { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px; }
        .step-card {
            background: #fff; border-radius: 16px; padding: 30px 20px;
            box-shadow: var(--sombra-suave); position: relative;
            transition: transform .2s;
        }
        .step-card:hover { transform: translateY(-4px); }
        .step-num {
            width: 38px; height: 38px; background: var(--cor-primaria); color: #fff;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .95rem; margin: 0 auto 16px;
        }
        .step-card i   { font-size: 2rem; color: var(--cor-primaria); margin-bottom: 12px; display: block; }
        .step-card h3  { font-size: 1rem; font-weight: 700; color: var(--texto-escuro); margin-bottom: 8px; }
        .step-card p   { font-size: .88rem; color: #777; line-height: 1.6; }

        /* ── Sobre ── */
        .sobre-section {
            background: linear-gradient(135deg, #f1f8e9, #e8f5e9);
            padding: 70px 20px;
        }
        .sobre-inner {
            max-width: 1000px; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: center;
        }
        .sobre-inner img {
            width: 100%; border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,.12);
        }
        .sobre-texto h2 { font-family: 'Arvo', serif; font-size: 1.8rem; color: var(--texto-escuro); margin-bottom: 16px; }
        .sobre-texto p  { color: #555; line-height: 1.8; margin-bottom: 14px; font-size: .97rem; text-align: justify; }

        /* ── Valores ── */
        .valores-section { max-width: 1000px; margin: 70px auto; padding: 0 20px; text-align: center; }
        .valores-grid    { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 22px; margin-top: 40px; }
        .valor-card {
            background: #fff; border-radius: 16px; padding: 30px 22px;
            box-shadow: var(--sombra-suave); transition: transform .2s;
            border-top: 4px solid var(--cor-primaria);
        }
        .valor-card:hover { transform: translateY(-4px); }
        .valor-card i  { font-size: 2.2rem; color: var(--cor-primaria); margin-bottom: 14px; display: block; }
        .valor-card h3 { font-size: 1.05rem; font-weight: 700; color: var(--texto-escuro); margin-bottom: 8px; }
        .valor-card p  { font-size: .88rem; color: #777; line-height: 1.6; }

        /* ── CTA final ── */
        .cta-final {
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            padding: 70px 20px; text-align: center; color: #fff;
        }
        .cta-final h2 { font-family: 'Arvo', serif; font-size: 2rem; margin-bottom: 12px; }
        .cta-final p  { opacity: .85; font-size: 1.05rem; margin-bottom: 30px; }

        /* ── Header: saudação ── */
        .saudacao-header {
            display: flex; align-items: center; gap: 8px;
            font-size: .9rem; color: #555; font-weight: 600;
        }
        .saudacao-header strong { color: var(--cor-primaria); }
        .saudacao-header .dropdown { position: relative; }
        .saudacao-header .dropdown-menu {
            display: none; position: absolute; right: 0; top: 110%;
            background: #fff; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,.12);
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

        @media (max-width: 768px) {
            .sobre-inner { grid-template-columns: 1fr; }
            .sobre-inner img { max-height: 250px; object-fit: cover; }
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- ── Hero ── -->
<section class="hero">
    <div class="hero-content">
        <?php if ($logado): ?>
            <div class="saudacao-logado">
                <i class="fas fa-heart"></i>
                Olá, <?= htmlspecialchars($nome) ?>! Que bom ter você aqui.
            </div>
        <?php endif; ?>
        <h1>Doe Itens,<br><span>Mude Histórias</span></h1>
        <p>Conectamos quem quer ajudar com quem mais precisa.<br>
           Transforme o que está parado na sua casa em esperança para alguém.</p>
        <div class="hero-btns">
            <a href="doar.php" class="btn-hero-primary">
                <i class="fas fa-hand-holding-heart"></i> Quero Doar
            </a>
            <a href="verdoacoes.php" class="btn-hero-secondary">
                <i class="fas fa-search"></i> Ver Doações
            </a>
        </div>
    </div>
</section>

<!-- ── Stats ── -->
<div class="stats-bar">
    <div class="stats-inner">
        <?php
        require_once 'conexao.php';
        try {
            $total_doacoes  = $pdo->query("SELECT COUNT(*) FROM doacao WHERE situacao = 'Aprovado'")->fetchColumn();
            $total_usuarios = $pdo->query("SELECT COUNT(*) FROM usuario WHERE tipo_usuario = 'comum'")->fetchColumn();
            $total_cidades  = $pdo->query("SELECT COUNT(DISTINCT localidade) FROM usuario")->fetchColumn();
        } catch (PDOException $e) {
            echo "<p style='color:red; font-weight:bold;'>ERRO AO BUSCAR DADOS: " . htmlspecialchars($e->getMessage()) . "</p>";
            $total_doacoes = $total_usuarios = $total_cidades = 0;
        }
        ?>
        <div class="stat-item">
            <div class="num"><?= number_format($total_doacoes) ?>+</div>
            <div class="label">Doações Realizadas</div>
        </div>
        <div class="stat-item">
            <div class="num"><?= number_format($total_usuarios) ?>+</div>
            <div class="label">Usuários Cadastrados</div>
        </div>
        <div class="stat-item">
            <div class="num"><?= number_format($total_cidades) ?>+</div>
            <div class="label">Cidades Atendidas</div>
        </div>
    </div>
</div>

<!-- ── Como funciona ── -->
<section class="como-funciona">
    <h2 class="secao-titulo">Como Funciona</h2>
    <p class="secao-sub">Em 3 passos simples você já está ajudando</p>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <i class="fas fa-user-plus"></i>
            <h3>Crie sua Conta</h3>
            <p>Cadastre-se gratuitamente em menos de 2 minutos.</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <i class="fas fa-camera"></i>
            <h3>Anuncie o Item</h3>
            <p>Tire fotos, escolha a categoria e publique sua doação.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <i class="fas fa-handshake"></i>
            <h3>Conecte-se</h3>
            <p>Converse com o interessado e combine a retirada.</p>
        </div>
    </div>
</section>

<!-- ── Sobre ── -->
<section class="sobre-section">
    <div class="sobre-inner">
        <div>
            <img src="imagens/coracaoMaos.avif" alt="Mãos com coração">
        </div>
        <div class="sobre-texto">
            <h2>Sobre o Doar+</h2>
            <p>O Doar+ nasceu em 2026 com um propósito simples: transformar a solidariedade em ação. Percebemos que muitas pessoas têm itens em boas condições parados em casa, enquanto outras precisam exatamente desses itens.</p>
            <p>Criamos então uma plataforma que conecta doadores a pessoas que precisam, de forma simples, segura e gratuita. Hoje, já ajudamos milhares de famílias em todo o Brasil.</p>
            <p><strong>Você pode mudar uma história hoje mesmo!</strong></p>
            <?php if (!$logado): ?>
                <a href="cadastro.php" class="btn-hero-primary" style="display:inline-block; margin-top:10px;">
                    <i class="fas fa-arrow-right"></i> Criar conta grátis
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── Valores ── -->
<section class="valores-section">
    <h2 class="secao-titulo">Nossos Valores</h2>
    <p class="secao-sub">O que nos move todos os dias</p>
    <div class="valores-grid">
        <div class="valor-card">
            <i class="fas fa-heart"></i>
            <h3>Solidariedade</h3>
            <p>Acreditamos no poder da união e da ajuda ao próximo.</p>
        </div>
        <div class="valor-card">
            <i class="fas fa-hand-holding-heart"></i>
            <h3>Generosidade</h3>
            <p>Compartilhar é um ato de amor que transforma vidas.</p>
        </div>
        <div class="valor-card">
            <i class="fas fa-globe-americas"></i>
            <h3>Impacto Social</h3>
            <p>Trabalhamos para construir uma sociedade mais justa.</p>
        </div>
        <div class="valor-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Segurança</h3>
            <p>Todas as doações passam por aprovação antes de serem publicadas.</p>
        </div>
    </div>
</section>

<!-- ── CTA Final ── -->
<?php if (!$logado): ?>
<section class="cta-final">
    <h2>Pronto para fazer a diferença?</h2>
    <p>Junte-se a milhares de pessoas que já estão transformando vidas.</p>
    <div class="hero-btns">
        <a href="cadastro.php" class="btn-hero-primary">
            <i class="fas fa-user-plus"></i> Criar Conta Grátis
        </a>
        <a href="verdoacoes.php" class="btn-hero-secondary">
            <i class="fas fa-eye"></i> Ver Doações Disponíveis
        </a>
    </div>
</section>
<?php else: ?>
<section class="cta-final">
    <h2>Continue fazendo o bem, <?= htmlspecialchars($nome) ?>!</h2>
    <p>Cada doação faz a diferença na vida de alguém.</p>
    <div class="hero-btns">
        <a href="doar.php" class="btn-hero-primary">
            <i class="fas fa-hand-holding-heart"></i> Nova Doação
        </a>
        <a href="verdoacoes.php" class="btn-hero-secondary">
            <i class="fas fa-eye"></i> Ver Doações
        </a>
    </div>
</section>
<?php endif; ?>

<?php include 'footer.php'; ?>

</body>
</html>
