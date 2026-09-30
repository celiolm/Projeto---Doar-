<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
require_once 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];
$id_doacao  = (int)($_GET['id'] ?? 0);

if (!$id_doacao) {
    header('Location: painel_usuario.php');
    exit;
}

// Busca doação — garante que pertence ao usuário logado
$stmt = $pdo->prepare("
    SELECT d.id_doacao, d.titulo, d.categoria, d.descricao, d.situacao,
           d.logradouro, d.numero, d.complemento, d.bairro, d.localidade, d.uf, d.cep
    FROM doacao d
    WHERE d.id_doacao = ? AND d.id_usuario = ?
");
$stmt->execute([$id_doacao, $id_usuario]);
$doacao = $stmt->fetch();

if (!$doacao) {
    header('Location: painel_usuario.php');
    exit;
}

// Fotos
$fotos = $pdo->prepare("SELECT caminho FROM doacao_fotos WHERE id_doacao = ? ORDER BY ordem");
$fotos->execute([$id_doacao]);
$fotos = $fotos->fetchAll(PDO::FETCH_COLUMN);

$badge_cores = [
    'Pendente'  => ['bg' => '#fff3cd', 'cor' => '#856404'],
    'Aprovado'  => ['bg' => '#d4edda', 'cor' => '#155724'],
    'Recusado'  => ['bg' => '#f8d7da', 'cor' => '#721c24'],
    'Histórico' => ['bg' => '#d1ecf1', 'cor' => '#0c5460'],
];
$badge = $badge_cores[$doacao['situacao']] ?? ['bg' => '#eee', 'cor' => '#333'];

$logado   = true;
$nome_ses = explode(' ', $_SESSION['nome'])[0];
$is_admin = $_SESSION['tipo_usuario'] === 'superadmin';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Minha Doação #<?= $id_doacao ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arvo:wght@400;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/componentes.css">
    <link rel="stylesheet" href="css/paginas.css">
    <link rel="stylesheet" href="css/responsivo.css">
    <link rel="stylesheet" href="css/header_footer.css">
    <style>
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

        .detalhe-wrapper {
            max-width: 850px; margin: 40px auto; padding: 0 20px;
        }
        .btn-voltar {
            display: inline-flex; align-items: center; gap: 7px;
            color: var(--cor-primaria); text-decoration: none;
            font-weight: 600; font-size: .9rem; margin-bottom: 22px;
        }
        .btn-voltar:hover { text-decoration: underline; }

        .detalhe-card {
            background: #fff; border-radius: 16px;
            box-shadow: var(--sombra-suave); overflow: hidden;
        }

        /* Galeria */
        .galeria { background: #f4f7f6; padding: 20px; }
        .foto-principal {
    width: 100%; height: 500px; object-fit: contain;
    border-radius: 10px; margin-bottom: 12px;
    background: #f4f7f6;
}
        .sem-foto-principal {
            width: 100%; height: 350px; border-radius: 10px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            display: flex; align-items: center; justify-content: center;
            font-size: 4rem; color: var(--cor-primaria); margin-bottom: 12px;
        }
        .miniaturas { display: flex; gap: 10px; flex-wrap: wrap; }
        .miniaturas img {
            width: 75px; height: 75px; object-fit: cover;
            border-radius: 8px; cursor: pointer;
            border: 2px solid transparent; transition: border .2s;
        }
        .miniaturas img:hover,
        .miniaturas img.ativa { border-color: var(--cor-primaria); }

        /* Info */
        .detalhe-info { padding: 28px; }
        .detalhe-topo {
            display: flex; align-items: flex-start;
            justify-content: space-between; flex-wrap: wrap; gap: 12px;
            margin-bottom: 20px;
        }
        .detalhe-topo h1 { font-family: 'Arvo', serif; font-size: 1.6rem; color: var(--texto-escuro); }
        .badge-situacao {
            display: inline-block; padding: 6px 16px; border-radius: 20px;
            font-size: .88rem; font-weight: 700; white-space: nowrap;
        }

        .info-bloco { margin-bottom: 22px; }
        .info-bloco h3 {
            font-size: .82rem; text-transform: uppercase; letter-spacing: .05em;
            color: #aaa; font-weight: 700; margin-bottom: 6px;
        }
        .info-bloco p { font-size: .95rem; color: #444; line-height: 1.7; }

        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Timeline situação */
        .timeline {
            display: flex; gap: 0; margin: 25px 0 10px;
        }
        .timeline-step {
            flex: 1; text-align: center; position: relative;
        }
        .timeline-step::before {
            content: ''; position: absolute; top: 16px; left: 50%;
            width: 100%; height: 2px; background: #e0e0e0; z-index: 0;
        }
        .timeline-step:last-child::before { display: none; }
        .timeline-dot {
            width: 32px; height: 32px; border-radius: 50%;
            background: #e0e0e0; display: flex; align-items: center;
            justify-content: center; margin: 0 auto 6px;
            position: relative; z-index: 1; font-size: .85rem; color: #aaa;
        }
        .timeline-dot.ativo { background: var(--cor-primaria); color: #fff; }
        .timeline-dot.recusado { background: var(--vermelho-erro); color: #fff; }
        .timeline-label { font-size: .75rem; color: #888; font-weight: 600; }
        .timeline-label.ativo { color: var(--cor-primaria); }
        .timeline-label.recusado { color: var(--vermelho-erro); }

        .categoria-badge {
            display: inline-block; background: #e8f5e9; color: var(--cor-primaria);
            font-size: .78rem; font-weight: 700; padding: 3px 12px;
            border-radius: 20px; margin-bottom: 16px;
        }

        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
            .foto-principal { height: 220px; }
        }
    </style>
</head>
<body style="background:var(--cinza-claro);">

<?php include 'header.php'; ?>

<div class="detalhe-wrapper">

    <a href="painel_usuario.php" class="btn-voltar">
        <i class="fas fa-arrow-left"></i> Voltar ao Painel
    </a>

    <div class="detalhe-card">

        <!-- Galeria -->
        <div class="galeria">
            <?php if (!empty($fotos)): ?>
                <img class="foto-principal" id="fotoPrincipal"
                     src="<?= htmlspecialchars($fotos[0]) ?>" alt="Foto principal">
                <?php if (count($fotos) > 1): ?>
                <div class="miniaturas">
                    <?php foreach ($fotos as $i => $f): ?>
                        <img src="<?= htmlspecialchars($f) ?>"
                             class="<?= $i === 0 ? 'ativa' : '' ?>"
                             onclick="trocarFoto(this, '<?= htmlspecialchars($f) ?>')">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="sem-foto-principal"><i class="fas fa-gift"></i></div>
            <?php endif; ?>
        </div>

        <!-- Info -->
        <div class="detalhe-info">
            <div class="detalhe-topo">
                <div>
                    <span class="categoria-badge"><?= htmlspecialchars($doacao['categoria']) ?></span>
                    <h1><?= htmlspecialchars($doacao['titulo']) ?></h1>
                </div>
                <span class="badge-situacao"
                      style="background:<?= $badge['bg'] ?>; color:<?= $badge['cor'] ?>;">
                    <?= $doacao['situacao'] ?>
                </span>
            </div>

            <!-- Timeline -->
            <div class="info-bloco">
                <h3>Acompanhamento</h3>
                <div class="timeline">
                    <?php
                    $steps = [
                        ['label' => 'Enviada',  'icon' => 'fa-paper-plane'],
                        ['label' => 'Pendente', 'icon' => 'fa-clock'],
                        ['label' => 'Aprovado', 'icon' => 'fa-check'],
                    ];
                    $situacao = $doacao['situacao'];
                    $ativos = ['Pendente' => 2, 'Aprovado' => 3, 'Recusado' => 2, 'Histórico' => 3];
                    $qtd_ativos = $ativos[$situacao] ?? 1;

                    foreach ($steps as $i => $step):
                        $ativo   = ($i + 1) <= $qtd_ativos;
                        $recusado = $situacao === 'Recusado' && $i === 2;
                    ?>
                    <div class="timeline-step">
                        <div class="timeline-dot <?= $recusado ? 'recusado' : ($ativo ? 'ativo' : '') ?>">
                            <i class="fas <?= $recusado ? 'fa-times' : $step['icon'] ?>"></i>
                        </div>
                        <div class="timeline-label <?= $recusado ? 'recusado' : ($ativo ? 'ativo' : '') ?>">
                            <?= $recusado ? 'Recusado' : $step['label'] ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($doacao['descricao']): ?>
            <div class="info-bloco">
                <h3>Descrição</h3>
                <p><?= nl2br(htmlspecialchars($doacao['descricao'])) ?></p>
            </div>
            <?php endif; ?>

            <div class="info-bloco">
                <h3>Endereço da Doação</h3>
                <p>
                    <?= htmlspecialchars($doacao['logradouro']) ?>, <?= htmlspecialchars($doacao['numero']) ?>
                    <?= $doacao['complemento'] ? ', ' . htmlspecialchars($doacao['complemento']) : '' ?><br>
                    <?= htmlspecialchars($doacao['bairro']) ?> — <?= htmlspecialchars($doacao['localidade']) ?>/<?= htmlspecialchars($doacao['uf']) ?>
                    &nbsp;·&nbsp; CEP: <?= htmlspecialchars($doacao['cep']) ?>
                </p>
            </div>

            <div class="info-bloco">
                <h3>Número da Doação</h3>
                <p style="color:#aaa; font-size:.88rem;">#<?= $doacao['id_doacao'] ?></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function trocarFoto(el, src) {
    document.getElementById('fotoPrincipal').src = src;
    document.querySelectorAll('.miniaturas img').forEach(i => i.classList.remove('ativa'));
    el.classList.add('ativa');
}
</script>
</body>
</html>
