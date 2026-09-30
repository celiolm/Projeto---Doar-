<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] !== 'superadmin') {
    header('Location: login.php');
    exit;
}
require_once 'conexao.php';

const ITENS_POR_PAGINA = 30;

function paginaAtual($chave) {
    $p = (int)($_GET[$chave] ?? 1);
    return $p > 0 ? $p : 1;
}

function totalPaginas($total) {
    return max(1, (int)ceil($total / ITENS_POR_PAGINA));
}

function renderPaginacao($chave, $paginaAtual, $totalPaginas) {
    if ($totalPaginas <= 1) return;
    $params = $_GET;
    echo '<div class="paginacao">';
    if ($paginaAtual > 1) {
        $params[$chave] = $paginaAtual - 1;
        echo '<a href="?' . htmlspecialchars(http_build_query($params)) . '#aba-' . substr($chave, 3) . '" class="pg-link">&lsaquo;</a>';
    }
    for ($i = 1; $i <= $totalPaginas; $i++) {
        $params[$chave] = $i;
        $ativo = $i === $paginaAtual ? ' ativo' : '';
        echo '<a href="?' . htmlspecialchars(http_build_query($params)) . '#aba-' . substr($chave, 3) . '" class="pg-link' . $ativo . '">' . $i . '</a>';
    }
    if ($paginaAtual < $totalPaginas) {
        $params[$chave] = $paginaAtual + 1;
        echo '<a href="?' . htmlspecialchars(http_build_query($params)) . '#aba-' . substr($chave, 3) . '" class="pg-link">&rsaquo;</a>';
    }
    echo '</div>';
}

$mensagem = '';
$id_usuario_logado = $_SESSION['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao       = $_POST['acao']       ?? '';
    $id_doacao  = (int)($_POST['id_doacao']  ?? 0);
    $id_usuario = (int)($_POST['id_usuario'] ?? 0);

    if ($acao === 'aprovar' && $id_doacao) {
        $pdo->prepare("UPDATE doacao SET situacao = 'Aprovado' WHERE id_doacao = ?")->execute([$id_doacao]);
        $mensagem = 'Doação aprovada.';
    } elseif ($acao === 'recusar' && $id_doacao) {
        $pdo->prepare("UPDATE doacao SET situacao = 'Recusado' WHERE id_doacao = ?")->execute([$id_doacao]);
        $mensagem = 'Doação recusada.';
    } elseif ($acao === 'historico' && $id_doacao) {
        $pdo->prepare("UPDATE doacao SET situacao = 'Histórico' WHERE id_doacao = ?")->execute([$id_doacao]);
        $mensagem = 'Doação movida para histórico.';
    } elseif ($acao === 'excluir_usuario' && $id_usuario) {
        $pdo->prepare("DELETE FROM usuario WHERE id_usuario = ? AND tipo_usuario != 'superadmin'")->execute([$id_usuario]);
        $mensagem = 'Usuário excluído.';
    } elseif ($acao === 'marcar_lida') {
        $id_contato = (int)($_POST['id_contato'] ?? 0);
        if ($id_contato) {
            $pdo->prepare("UPDATE contato SET lida = 1 WHERE id_contato = ?")->execute([$id_contato]);
            $mensagem = 'Mensagem marcada como lida.';
        }
    } elseif ($acao === 'resolver_denuncia') {
        $id_denuncia = (int)($_POST['id_denuncia'] ?? 0);
        if ($id_denuncia) {
            $pdo->prepare("UPDATE denuncia SET resolvida = 1 WHERE id_denuncia = ?")->execute([$id_denuncia]);
            $mensagem = 'Denúncia marcada como resolvida.';
        }
    } elseif ($acao === 'bloquear' && $id_usuario) {
        $motivo = trim($_POST['motivo_bloqueio'] ?? '');
        if ($id_usuario !== $id_usuario_logado) {
            $pdo->prepare("UPDATE usuario SET bloqueado = 1, motivo_bloqueio = ? WHERE id_usuario = ?")
                ->execute([$motivo, $id_usuario]);
            $mensagem = 'Usuário bloqueado.';
        }
    } elseif ($acao === 'desbloquear' && $id_usuario) {
        $pdo->prepare("UPDATE usuario SET bloqueado = 0, motivo_bloqueio = NULL WHERE id_usuario = ?")
            ->execute([$id_usuario]);
        $mensagem = 'Usuário desbloqueado.';
    }
}

$totais = $pdo->query("
    SELECT COUNT(*) AS total,
        SUM(situacao = 'Pendente')  AS pendentes,
        SUM(situacao = 'Aprovado')  AS aprovados,
        SUM(situacao = 'Recusado')  AS recusados
    FROM doacao
")->fetch();

$total_usuarios  = $pdo->query("SELECT COUNT(*) FROM usuario WHERE tipo_usuario = 'comum'")->fetchColumn();
$total_contatos  = $pdo->query("SELECT COUNT(*) FROM contato WHERE lida = 0")->fetchColumn();
$total_denuncias = $pdo->query("SELECT COUNT(*) FROM denuncia WHERE resolvida = 0")->fetchColumn();

$filtro_situacao = $_GET['situacao'] ?? '';
$sql = "
    SELECT d.id_doacao, d.categoria, d.situacao,
           u.nome AS nome_usuario, u.email,
           (SELECT caminho FROM doacao_fotos WHERE id_doacao = d.id_doacao ORDER BY ordem LIMIT 1) AS capa
    FROM doacao d
    JOIN usuario u ON u.id_usuario = d.id_usuario
";
$params = [];
if ($filtro_situacao) {
    $sql .= " WHERE d.situacao = ?";
    $params[] = $filtro_situacao;
}
$total_doacoes_rows = $pdo->prepare("SELECT COUNT(*) FROM doacao d" . ($filtro_situacao ? " WHERE d.situacao = ?" : ""));
$total_doacoes_rows->execute($params);
$total_doacoes_rows = (int)$total_doacoes_rows->fetchColumn();
$pg_doacoes = paginaAtual('pg_doacoes');
$total_pg_doacoes = totalPaginas($total_doacoes_rows);
$offset_doacoes = (min($pg_doacoes, $total_pg_doacoes) - 1) * ITENS_POR_PAGINA;
$sql .= " ORDER BY d.id_doacao DESC LIMIT " . ITENS_POR_PAGINA . " OFFSET " . $offset_doacoes;
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doacoes = $stmt->fetchAll();

$total_usuarios_rows = (int)$pdo->query("SELECT COUNT(*) FROM usuario")->fetchColumn();
$pg_usuarios = paginaAtual('pg_usuarios');
$total_pg_usuarios = totalPaginas($total_usuarios_rows);
$offset_usuarios = (min($pg_usuarios, $total_pg_usuarios) - 1) * ITENS_POR_PAGINA;
$usuarios = $pdo->query("SELECT id_usuario, nome, email, cpf, localidade, uf, tipo_usuario, bloqueado, motivo_bloqueio FROM usuario ORDER BY nome LIMIT " . ITENS_POR_PAGINA . " OFFSET " . $offset_usuarios)->fetchAll();

$total_logs_rows = (int)$pdo->query("SELECT COUNT(*) FROM log_acesso")->fetchColumn();
$pg_logs = paginaAtual('pg_logs');
$total_pg_logs = totalPaginas($total_logs_rows);
$offset_logs = (min($pg_logs, $total_pg_logs) - 1) * ITENS_POR_PAGINA;
$logs = $pdo->query("
    SELECT l.data_hora, u.nome, l.ip, l.dispositivo
    FROM log_acesso l
    JOIN usuario u ON u.id_usuario = l.id_usuario
    ORDER BY l.data_hora DESC LIMIT " . ITENS_POR_PAGINA . " OFFSET " . $offset_logs)->fetchAll();

$total_contatos_rows = (int)$pdo->query("SELECT COUNT(*) FROM contato")->fetchColumn();
$pg_contatos = paginaAtual('pg_contatos');
$total_pg_contatos = totalPaginas($total_contatos_rows);
$offset_contatos = (min($pg_contatos, $total_pg_contatos) - 1) * ITENS_POR_PAGINA;
$contatos = $pdo->query("SELECT * FROM contato ORDER BY id_contato DESC LIMIT " . ITENS_POR_PAGINA . " OFFSET " . $offset_contatos)->fetchAll();

$total_denuncias_rows = (int)$pdo->query("SELECT COUNT(*) FROM denuncia")->fetchColumn();
$pg_denuncias = paginaAtual('pg_denuncias');
$total_pg_denuncias = totalPaginas($total_denuncias_rows);
$offset_denuncias = (min($pg_denuncias, $total_pg_denuncias) - 1) * ITENS_POR_PAGINA;
$denuncias = $pdo->query("
    SELECT dn.id_denuncia, dn.motivo, dn.descricao, dn.resolvida, dn.data_envio,
           dn.id_doacao, d.titulo AS titulo_doacao, d.situacao AS situacao_doacao,
           u.nome AS nome_denunciante
    FROM denuncia dn
    JOIN doacao d ON d.id_doacao = dn.id_doacao
    LEFT JOIN usuario u ON u.id_usuario = dn.id_usuario
    ORDER BY dn.resolvida ASC, dn.data_envio DESC LIMIT " . ITENS_POR_PAGINA . " OFFSET " . $offset_denuncias . "
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Painel Admin</title>
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
        .admin-wrapper { max-width:1100px; margin:100px auto 40px; padding:0 20px; }

        /* ── Abas ── */
        .tabs {
            display: flex;
            gap: 5px;
            margin-bottom: 25px;
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding-bottom: 2px;
        }
        .tabs::-webkit-scrollbar { display: none; }
        .tab-btn {
            flex-shrink: 0;
            padding: 10px 20px;
            border: none;
            border-radius: 8px 8px 0 0;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            background: #ddd;
            color: #555;
            white-space: nowrap;
        }
        .tab-btn.ativo { background: var(--cor-primaria); color: #fff; }
        .tab-content { display: none; }
        .tab-content.ativo { display: block; }

        /* ── Paginação ── */
        .paginacao { display:flex; gap:6px; justify-content:center; margin-top:20px; flex-wrap:wrap; }
        .pg-link {
            display:inline-flex; align-items:center; justify-content:center;
            min-width:34px; height:34px; padding:0 10px;
            border-radius:8px; background:#f0f0f0; color:#333;
            text-decoration:none; font-weight:600; font-size:.9rem; transition:all .2s;
        }
        .pg-link:hover { background:#ddd; }
        .pg-link.ativo { background:var(--cor-primaria); color:#fff; }

        /* ── Cards ── */
        .cards-resumo { display:flex; gap:20px; margin-bottom:30px; flex-wrap:wrap; }
        .card-resumo {
            flex:1; min-width:100px; background:#fff; border-radius:12px;
            padding:20px; text-align:center; box-shadow:var(--sombra-suave);
        }
        .card-resumo .numero { font-size:2rem; font-weight:700; color:var(--cor-primaria); }
        .card-resumo .label  { color:#666; font-size:0.85rem; margin-top:5px; }

        /* ── Tabela com scroll horizontal ── */
        .tabela-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
            box-shadow: var(--sombra-suave);
        }
        .tabela {
            width: 100%;
            min-width: 580px;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }
        .tabela th { background:var(--cor-primaria); color:#fff; padding:11px 14px; text-align:left; font-size:0.9rem; }
        .tabela td { padding:11px 14px; border-bottom:1px solid #eee; font-size:0.9rem; vertical-align:middle; }
        .tabela tr:last-child td { border-bottom:none; }
        .tabela img { width:55px; height:55px; object-fit:cover; border-radius:6px; }

        /* ── Badges ── */
        .badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.78rem; font-weight:600; }
        .badge-Pendente  { background:#fff3cd; color:#856404; }
        .badge-Aprovado  { background:#d4edda; color:#155724; }
        .badge-Recusado  { background:#f8d7da; color:#721c24; }
        .badge-Histórico { background:#d1ecf1; color:#0c5460; }
        .badge-superadmin{ background:#e8d5ff; color:#5a189a; }
        .badge-comum     { background:#e2e3e5; color:#383d41; }
        .badge-contador  { border-radius:50%; padding:1px 6px; font-size:.75rem; margin-left:4px; color:#fff; }

        /* ── Botões ── */
        .btn-acao { padding:5px 11px; border:none; border-radius:6px; cursor:pointer; font-size:0.8rem; font-weight:600; margin:2px; }
        .btn-aprovar    { background:#d4edda; color:#155724; }
        .btn-recusar    { background:#f8d7da; color:#721c24; }
        .btn-historico  { background:#d1ecf1; color:#0c5460; }
        .btn-excluir    { background:#f8d7da; color:#721c24; }
        .btn-bloquear   { background:#fff3cd; color:#856404; }
        .btn-desbloquear{ background:#d4edda; color:#155724; }

        /* ── Filtros ── */
        .filtros { display:flex; gap:10px; margin-bottom:15px; flex-wrap:wrap; }
        .filtros a { padding:6px 14px; border-radius:20px; text-decoration:none; font-size:0.85rem; font-weight:600; background:#eee; color:#555; }
        .filtros a.ativo { background:var(--cor-primaria); color:#fff; }

        .alert-sucesso { background:#4caf50; color:#fff; padding:10px 20px; border-radius:8px; margin-bottom:20px; font-weight:600; }

        /* ── Modal ── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.55); z-index: 2000;
            align-items: center; justify-content: center;
        }
        .modal-overlay.aberto { display: flex; }
        .modal-box {
            background: #fff; border-radius: 16px; max-width: 560px;
            width: 90%; max-height: 90vh; overflow-y: auto;
            padding: 28px; position: relative;
        }
        .modal-fechar {
            position: absolute; top: 14px; right: 16px;
            background: none; border: none; font-size: 1.4rem;
            cursor: pointer; color: #888;
        }
        .modal-fotos img.principal {
            width: 100%; height: 330px;
            object-fit: contain; border-radius: 10px;
            margin-bottom: 8px; background: #f4f7f6;
        }
        .modal-fotos { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
        .modal-fotos img {
            width: 110px; height: 110px; object-fit: cover;
            border-radius: 8px; border: 2px solid #eee; cursor: pointer; transition: border .2s;
        }
        .modal-fotos img:hover { border-color: var(--cor-primaria); }
        .modal-info p { font-size: 0.92rem; color: #555; margin-bottom: 6px; }
        .sem-foto {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem; color: var(--cor-primaria);
        }

        /* ── Responsivo ── */
        @media (max-width: 768px) {
            .admin-wrapper { margin: 80px auto 40px; padding: 0 12px; }

            .tab-btn { padding: 8px 12px; font-size: 0.82rem; }

            .cards-resumo { gap: 10px; }
            .card-resumo { min-width: calc(50% - 10px); padding: 14px; }
            .card-resumo .numero { font-size: 1.6rem; }

            .filtros a { padding: 5px 10px; font-size: 0.78rem; }
            .btn-acao { padding: 4px 8px; font-size: 0.75rem; }

            .modal-fotos img { width: 80px; height: 80px; }
            .modal-fotos img.principal { height: 220px; }
        }

        @media (max-width: 480px) {
            .card-resumo { min-width: calc(50% - 8px); padding: 10px; }
            .card-resumo .numero { font-size: 1.4rem; }
            .card-resumo .label { font-size: 0.72rem; }
            .tab-btn { padding: 7px 10px; font-size: 0.78rem; }
        }
    </style>
</head>
<body style="background:var(--cinza-claro);">

<?php include 'header.php'; ?>

<div class="admin-wrapper">

    <h1 style="margin-bottom:25px; color:var(--texto-escuro);">Painel Administrativo</h1>

    <?php if ($mensagem): ?>
        <div class="alert-sucesso"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>

    <!-- Resumo -->
    <div class="cards-resumo">
        <div class="card-resumo">
            <div class="numero"><?= $totais['total'] ?></div>
            <div class="label">Total Doações</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#856404;"><?= $totais['pendentes'] ?></div>
            <div class="label">Pendentes</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#155724;"><?= $totais['aprovados'] ?></div>
            <div class="label">Aprovadas</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#721c24;"><?= $totais['recusados'] ?></div>
            <div class="label">Recusadas</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:var(--cor-secundaria);"><?= $total_usuarios ?></div>
            <div class="label">Usuários</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#856404;"><?= $total_contatos ?></div>
            <div class="label">Msgs Novas</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#c0392b;"><?= $total_denuncias ?></div>
            <div class="label">Denúncias</div>
        </div>
    </div>

    <!-- Abas (scroll horizontal no mobile) -->
    <div class="tabs">
        <button class="tab-btn ativo" onclick="trocarAba('doacoes', this)">
            <i class="fas fa-hand-holding-heart"></i> Doações
        </button>
        <button class="tab-btn" onclick="trocarAba('usuarios', this)">
            <i class="fas fa-users"></i> Usuários
        </button>
        <button class="tab-btn" onclick="trocarAba('contatos', this)">
            <i class="fas fa-envelope"></i> Contato
            <?php if ($total_contatos > 0): ?>
                <span class="badge-contador" style="background:#856404;"><?= $total_contatos ?></span>
            <?php endif; ?>
        </button>
        <button class="tab-btn" onclick="trocarAba('denuncias', this)">
            <i class="fas fa-flag"></i> Denúncias
            <?php if ($total_denuncias > 0): ?>
                <span class="badge-contador" style="background:#c0392b;"><?= $total_denuncias ?></span>
            <?php endif; ?>
        </button>
        <button class="tab-btn" onclick="trocarAba('logs', this)">
            <i class="fas fa-history"></i> Logs
        </button>
    </div>

    <!-- ABA: Doações -->
    <div id="aba-doacoes" class="tab-content ativo">
        <div class="filtros">
            <a href="painel_admin.php" class="<?= !$filtro_situacao ? 'ativo' : '' ?>">Todas</a>
            <a href="?situacao=Pendente"  class="<?= $filtro_situacao === 'Pendente'  ? 'ativo' : '' ?>">Pendentes</a>
            <a href="?situacao=Aprovado"  class="<?= $filtro_situacao === 'Aprovado'  ? 'ativo' : '' ?>">Aprovadas</a>
            <a href="?situacao=Recusado"  class="<?= $filtro_situacao === 'Recusado'  ? 'ativo' : '' ?>">Recusadas</a>
            <a href="?situacao=Histórico" class="<?= $filtro_situacao === 'Histórico' ? 'ativo' : '' ?>">Histórico</a>
        </div>
        <?php if (empty($doacoes)): ?>
            <p style="color:#666;">Nenhuma doação encontrada.</p>
        <?php else: ?>
        <div class="tabela-wrapper">
        <table class="tabela">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Categoria / Doador</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doacoes as $d): ?>
                <tr>
                    <td><?= $d['id_doacao'] ?></td>
                    <td>
                        <?php if ($d['capa']): ?>
                            <img src="<?= htmlspecialchars($d['capa']) ?>" alt="Foto"
                                 style="cursor:pointer; transition:transform .2s;"
                                 onmouseover="this.style.transform='scale(1.08)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onclick="abrirModal(<?= $d['id_doacao'] ?>)">
                        <?php else: ?>
                            <span style="color:#aaa;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= htmlspecialchars($d['categoria']) ?></strong><br>
                        <span style="font-size:.85rem; color:#555;"><?= htmlspecialchars($d['nome_usuario']) ?></span><br>
                        <small style="color:#aaa;"><?= htmlspecialchars($d['email']) ?></small>
                    </td>
                    <td><span class="badge badge-<?= $d['situacao'] ?>"><?= $d['situacao'] ?></span></td>
                    <td>
                        <?php if ($d['situacao'] !== 'Aprovado'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_doacao" value="<?= $d['id_doacao'] ?>">
                            <input type="hidden" name="acao" value="aprovar">
                            <button class="btn-acao btn-aprovar" type="submit">✔ Aprovar</button>
                        </form>
                        <?php endif; ?>
                        <?php if ($d['situacao'] !== 'Recusado'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_doacao" value="<?= $d['id_doacao'] ?>">
                            <input type="hidden" name="acao" value="recusar">
                            <button class="btn-acao btn-recusar" type="submit">✖ Recusar</button>
                        </form>
                        <?php endif; ?>
                        <?php if ($d['situacao'] !== 'Histórico'): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_doacao" value="<?= $d['id_doacao'] ?>">
                            <input type="hidden" name="acao" value="historico">
                            <button class="btn-acao btn-historico" type="submit">📁 Histórico</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php renderPaginacao('pg_doacoes', min($pg_doacoes, $total_pg_doacoes), $total_pg_doacoes); ?>
        <?php endif; ?>
    </div>

    <!-- ABA: Usuários -->
    <div id="aba-usuarios" class="tab-content">
        <div class="tabela-wrapper">
        <table class="tabela">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>CPF</th>
                    <th>Cidade/UF</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <?php
                    $cpf = htmlspecialchars($u['cpf']);
                    $cpf_mascarado = substr($cpf, 0, 3) . '.***.***-**';
                ?>
                <tr>
                    <td><?= $u['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($u['nome']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <span style="cursor:pointer; font-family:monospace;"
                              data-cpf-completo="<?= $cpf ?>"
                              data-cpf-mascarado="<?= $cpf_mascarado ?>"
                              data-visivel="0"
                              onclick="
                                if(this.dataset.visivel=='0'){
                                    this.textContent=this.dataset.cpfCompleto;
                                    this.dataset.visivel='1';
                                    this.title='Clique para ocultar';
                                } else {
                                    this.textContent=this.dataset.cpfMascarado;
                                    this.dataset.visivel='0';
                                    this.title='Clique para ver CPF completo';
                                }"
                              title="Clique para ver CPF completo">
                            <?= $cpf_mascarado ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($u['localidade']) ?>/<?= htmlspecialchars($u['uf']) ?></td>
                    <td><span class="badge badge-<?= $u['tipo_usuario'] ?>"><?= $u['tipo_usuario'] ?></span></td>
                    <td>
                        <?php if ($u['bloqueado']): ?>
                            <span class="badge" style="background:#f8d7da;color:#721c24;" title="<?= htmlspecialchars($u['motivo_bloqueio'] ?? '') ?>">
                                🚫 Bloqueado
                            </span>
                        <?php else: ?>
                            <span class="badge" style="background:#d4edda;color:#155724;">✔ Ativo</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($u['tipo_usuario'] !== 'superadmin'): ?>
                            <form method="POST" style="display:inline;"
                                  onsubmit="return confirm('Excluir este usuário e todas suas doações?')">
                                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                <input type="hidden" name="acao" value="excluir_usuario">
                                <button class="btn-acao btn-excluir" type="submit">🗑 Excluir</button>
                            </form>
                            <?php if (!$u['bloqueado']): ?>
                            <form method="POST" style="display:inline;" id="formBloquear<?= $u['id_usuario'] ?>">
                                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                <input type="hidden" name="acao" value="bloquear">
                                <input type="hidden" name="motivo_bloqueio" id="motivoInput<?= $u['id_usuario'] ?>" value="">
                                <button type="button" class="btn-acao btn-bloquear"
                                        onclick="
                                            const m = prompt('Motivo do bloqueio:');
                                            if (m !== null && m.trim() !== '') {
                                                document.getElementById('motivoInput<?= $u['id_usuario'] ?>').value = m;
                                                document.getElementById('formBloquear<?= $u['id_usuario'] ?>').submit();
                                            }
                                        ">
                                    🚫 Bloquear
                                </button>
                            </form>
                            <?php else: ?>
                            <form method="POST" style="display:inline;"
                                  onsubmit="return confirm('Desbloquear este usuário?')">
                                <input type="hidden" name="id_usuario" value="<?= $u['id_usuario'] ?>">
                                <input type="hidden" name="acao" value="desbloquear">
                                <button class="btn-acao btn-desbloquear" type="submit">✔ Desbloquear</button>
                            </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color:#aaa; font-size:0.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php renderPaginacao('pg_usuarios', min($pg_usuarios, $total_pg_usuarios), $total_pg_usuarios); ?>
    </div>

    <!-- ABA: Contatos -->
    <div id="aba-contatos" class="tab-content">
        <div class="tabela-wrapper">
        <table class="tabela">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Assunto</th>
                    <th>Mensagem</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatos as $c): ?>
                <tr>
                    <td><?= $c['id_contato'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($c['data_envio'])) ?></td>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= $c['telefone'] ? htmlspecialchars($c['telefone']) : '<span style="color:#aaa;">—</span>' ?></td>
                    <td><?= htmlspecialchars($c['assunto']) ?></td>
                    <td style="max-width:220px;">
                        <span style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; cursor:pointer;"
                              onclick="this.style.webkitLineClamp='unset'; this.style.display='block';"
                              title="Clique para ver tudo">
                            <?= htmlspecialchars($c['mensagem']) ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($c['lida']): ?>
                            <span class="badge" style="background:#d4edda; color:#155724;">Lida</span>
                        <?php else: ?>
                            <span class="badge" style="background:#fff3cd; color:#856404;">Nova</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$c['lida']): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id_contato" value="<?= $c['id_contato'] ?>">
                            <input type="hidden" name="acao" value="marcar_lida">
                            <button class="btn-acao btn-aprovar" type="submit">✔ Marcar lida</button>
                        </form>
                        <?php else: ?>
                            <span style="color:#aaa; font-size:.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php renderPaginacao('pg_contatos', min($pg_contatos, $total_pg_contatos), $total_pg_contatos); ?>
    </div>

    <!-- ABA: Denúncias -->
    <div id="aba-denuncias" class="tab-content">
        <div class="tabela-wrapper">
        <table class="tabela">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Doação</th>
                    <th>Denunciante</th>
                    <th>Motivo</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($denuncias as $dn): ?>
                <tr>
                    <td><?= $dn['id_denuncia'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($dn['data_envio'])) ?></td>
                    <td>
                        <strong>#<?= $dn['id_doacao'] ?></strong><br>
                        <small style="color:#888;"><?= htmlspecialchars($dn['titulo_doacao']) ?></small><br>
                        <span class="badge badge-<?= $dn['situacao_doacao'] ?>"><?= $dn['situacao_doacao'] ?></span>
                    </td>
                    <td><?= $dn['nome_denunciante'] ? htmlspecialchars($dn['nome_denunciante']) : '<span style="color:#aaa;">Anônimo</span>' ?></td>
                    <td><?= htmlspecialchars($dn['motivo']) ?></td>
                    <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?= $dn['descricao'] ? htmlspecialchars($dn['descricao']) : '<span style="color:#aaa;">—</span>' ?>
                    </td>
                    <td>
                        <?php if ($dn['resolvida']): ?>
                            <span class="badge" style="background:#d4edda; color:#155724;">Resolvida</span>
                        <?php else: ?>
                            <span class="badge" style="background:#f8d7da; color:#721c24;">Pendente</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$dn['resolvida']): ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="id_denuncia" value="<?= $dn['id_denuncia'] ?>">
                                <input type="hidden" name="acao" value="resolver_denuncia">
                                <button class="btn-acao btn-aprovar" type="submit">✔ Resolver</button>
                            </form>
                            <form method="POST" style="display:inline;"
                                  onsubmit="return confirm('Recusar a doação denunciada?')">
                                <input type="hidden" name="id_doacao" value="<?= $dn['id_doacao'] ?>">
                                <input type="hidden" name="acao" value="recusar">
                                <button class="btn-acao btn-recusar" type="submit">✖ Recusar Doação</button>
                            </form>
                        <?php else: ?>
                            <span style="color:#aaa; font-size:.8rem;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php renderPaginacao('pg_denuncias', min($pg_denuncias, $total_pg_denuncias), $total_pg_denuncias); ?>
    </div>

    <!-- ABA: Logs -->
    <div id="aba-logs" class="tab-content">
        <div class="tabela-wrapper">
        <table class="tabela">
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Usuário</th>
                    <th>IP</th>
                    <th>Dispositivo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['data_hora'] ? date('d/m/Y H:i:s', strtotime($log['data_hora'])) : '-' ?></td>
                    <td><?= htmlspecialchars($log['nome']) ?></td>
                    <td><?= htmlspecialchars($log['ip']) ?></td>
                    <td style="max-width:300px;">
                        <span style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; cursor:pointer;"
                              onclick="this.style.webkitLineClamp='unset'; this.style.display='block';"
                              title="Clique para ver tudo">
                            <?= htmlspecialchars($log['dispositivo']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php renderPaginacao('pg_logs', min($pg_logs, $total_pg_logs), $total_pg_logs); ?>
    </div>

</div>

<!-- Modal detalhes doação -->
<div class="modal-overlay" id="modalOverlay" onclick="fecharModalFora(event)">
    <div class="modal-box" id="modalBox">
        <button class="modal-fechar" onclick="fecharModal()">
            <i class="fas fa-times"></i>
        </button>
        <div id="modalConteudo">
            <p style="text-align:center; color:#aaa;">Carregando...</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
function trocarAba(id, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('ativo'));
    document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('ativo'));
    document.getElementById('aba-' + id).classList.add('ativo');
    btn.classList.add('ativo');
}

(function () {
    const hash = window.location.hash.replace('#aba-', '');
    if (!hash) return;
    const btn = document.querySelector('.tab-btn[onclick*="\'' + hash + '\'"]');
    if (btn) trocarAba(hash, btn);
})();

function abrirModal(id) {
    document.getElementById('modalOverlay').classList.add('aberto');
    document.getElementById('modalConteudo').innerHTML =
        '<p style="text-align:center;color:#aaa;padding:30px 0;"><i class="fas fa-spinner fa-spin"></i> Carregando...</p>';

    fetch('detalhe_doacao.php?id=' + id)
        .then(r => r.json())
        .then(d => {
            if (d.erro) { document.getElementById('modalConteudo').innerHTML = '<p>' + d.erro + '</p>'; return; }

            let fotosHtml = '';
            if (d.fotos && d.fotos.length > 0) {
                fotosHtml += '<img class="principal" id="fotoPrincipal" src="' + d.fotos[0] + '" alt="Foto principal">';
                if (d.fotos.length > 1) {
                    fotosHtml += '<div class="modal-fotos">';
                    d.fotos.forEach((f, i) => {
                        fotosHtml += '<img src="' + f + '" onclick="trocarFoto(\'' + f + '\')" ' +
                                     (i === 0 ? 'style="border-color:var(--cor-primaria);"' : '') + '>';
                    });
                    fotosHtml += '</div>';
                }
            } else {
                fotosHtml = '<div class="sem-foto" style="height:180px;border-radius:10px;margin-bottom:12px;"><i class="fas fa-gift"></i></div>';
            }

            document.getElementById('modalConteudo').innerHTML = `
                <div class="modal-fotos" style="flex-direction:column;">${fotosHtml}</div>
                <div class="modal-info">
                    <span class="categoria-badge" style="background:#e8f5e9;color:var(--cor-primaria);
                          font-size:.78rem;font-weight:700;padding:3px 10px;border-radius:20px;
                          display:inline-block;margin-bottom:10px;">${d.categoria}</span>
                    <h3 style="font-size:1.15rem;margin-bottom:10px;">${d.titulo}</h3>
                    <p>${d.descricao || '<em style="color:#bbb;">Sem descrição.</em>'}</p>
                    <p style="margin-top:8px;"><strong><i class="fas fa-map-marker-alt" style="color:var(--cor-primaria);"></i> Local:</strong>
                        ${d.logradouro}, ${d.numero}${d.complemento ? ', ' + d.complemento : ''} — ${d.bairro}, ${d.localidade}/${d.uf}
                    </p>
                    <p style="margin-top:6px;"><strong><i class="fas fa-user" style="color:var(--cor-primaria);"></i> Doador:</strong> ${d.doador}</p>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('modalConteudo').innerHTML = '<p style="color:red;">Erro ao carregar detalhes.</p>';
        });
}

function trocarFoto(src) {
    const el = document.getElementById('fotoPrincipal');
    if (el) el.src = src;
}

function fecharModal() {
    document.getElementById('modalOverlay').classList.remove('aberto');
}

function fecharModalFora(e) {
    if (e.target === document.getElementById('modalOverlay')) fecharModal();
}
</script>
</body>
</html>
