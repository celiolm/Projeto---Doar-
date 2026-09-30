<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
require_once 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];

// Dados do usuário
$stmt = $pdo->prepare("SELECT * FROM usuario WHERE id_usuario = ?");
$stmt->execute([$id_usuario]);
$usuario = $stmt->fetch();

// Doações do usuário (Removido o filtro para que o histórico continue aparecendo na tela)
$stmt = $pdo->prepare("
    SELECT d.id_doacao, d.categoria, d.situacao,
           (SELECT caminho FROM doacao_fotos WHERE id_doacao = d.id_doacao ORDER BY ordem LIMIT 1) AS capa
    FROM doacao d
    WHERE d.id_usuario = ?
    ORDER BY d.id_doacao DESC
");
$stmt->execute([$id_usuario]);
$doacoes = $stmt->fetchAll();

// Contadores
$total     = count($doacoes);
$pendentes = count(array_filter($doacoes, fn($d) => $d['situacao'] === 'Pendente'));
$aprovados = count(array_filter($doacoes, fn($d) => $d['situacao'] === 'Aprovado'));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Meu Painel</title>
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
        .painel-wrapper { max-width:1000px; margin:100px auto 40px; padding:0 20px; }
        .cards-resumo { display:flex; gap:20px; margin-bottom:30px; flex-wrap:wrap; }
        .card-resumo {
            flex:1; min-width:150px; background:#fff; border-radius:12px;
            padding:20px; text-align:center; box-shadow:var(--sombra-suave);
        }
        .card-resumo .numero { font-size:2rem; font-weight:700; color:var(--cor-primaria); }
        .card-resumo .label  { color:#666; font-size:0.9rem; margin-top:5px; }
        .tabela-doacoes { width:100%; border-collapse:collapse; background:#fff;
            border-radius:12px; overflow:hidden; box-shadow:var(--sombra-suave); }
        .tabela-doacoes th { background:var(--cor-primaria); color:#fff; padding:12px 15px; text-align:left; }
        .tabela-doacoes td { padding:12px 15px; border-bottom:1px solid #eee; }
        .tabela-doacoes tr:last-child td { border-bottom:none; }
        .tabela-doacoes img { width:55px; height:55px; object-fit:cover; border-radius:6px; }
        
        /* Badges de Status */
        .badge { display:inline-block; padding:3px 12px; border-radius:20px; font-size:0.8rem; font-weight:600; }
        .badge-Pendente  { background:#fff3cd; color:#856404; }
        .badge-Aprovado  { background:#d4edda; color:#155724; }
        .badge-Recusado  { background:#f8d7da; color:#721c24; }
        .badge-Entregue  { background:#d1ecf1; color:#0c5460; }
        .badge-Cancelado { background:#e2e3e5; color:#383d41; }

        /* Botões */
        .btn-nova-doacao {
            display:inline-block; background:var(--cor-primaria); color:#fff;
            padding:12px 25px; border-radius:8px; text-decoration:none;
            font-weight:600; margin-bottom:25px;
        }
        .btn-nova-doacao:hover { background:var(--verde-escuro); }
        .secao-titulo { font-size:1.2rem; font-weight:700; color:var(--texto-escuro); margin-bottom:15px; }
        
        .btn-acao {
            display: inline-block; padding: 6px 12px; border-radius: 6px;
            color: #fff; font-size: 0.85rem; font-weight: bold;
            border: none; cursor: pointer; margin-right: 5px; margin-bottom: 5px;
            transition: background 0.2s;
        }
        .btn-entregue { background: #28a745; }
        .btn-entregue:hover { background: #218838; }
        .btn-cancelar { background: #dc3545; }
        .btn-cancelar:hover { background: #c82333; }
        
        .texto-finalizado { color: #666; font-size: 0.9rem; font-weight: bold; }
        .btn-editar { background: #007bff; text-decoration: none; }
        .btn-editar:hover { background: #0056b3; }
    </style>
</head>
<body style="background:var(--cinza-claro);">

<?php include 'header.php'; ?>

<div class="painel-wrapper">

    <h1 style="margin-bottom:25px; color:var(--texto-escuro);">Meu Painel</h1>

    <div class="cards-resumo">
        <div class="card-resumo">
            <div class="numero"><?= $total ?></div>
            <div class="label">Total de Doações</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#856404;"><?= $pendentes ?></div>
            <div class="label">Pendentes</div>
        </div>
        <div class="card-resumo">
            <div class="numero" style="color:#155724;"><?= $aprovados ?></div>
            <div class="label">Aprovadas</div>
        </div>
    </div>

    <a href="doar.php" class="btn-nova-doacao">
        <i class="fas fa-plus"></i> Nova Doação
    </a>

    <div class="secao-titulo">Minhas Doações</div>

    <?php if (empty($doacoes)): ?>
        <p style="color:#666;">Você não possui doações no momento. <a href="doar.php">Doe agora!</a></p>
    <?php else: ?>
        <table class="tabela-doacoes">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Categoria</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($doacoes as $d): ?>
                <tr>
                    <td>
                        <a href="detalhe_minha_doacao.php?id=<?= $d['id_doacao'] ?>"
                           style="color:var(--cor-primaria); font-weight:600;">
                            #<?= $d['id_doacao'] ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($d['capa']): ?>
                            <img src="<?= htmlspecialchars($d['capa']) ?>" alt="Foto">
                        <?php else: ?>
                            <span style="color:#aaa;">Sem foto</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($d['categoria']) ?></td>
                    <td>
                        <?php if(empty($d['situacao'])): ?>
                            <span style="color:#aaa;">-</span>
                        <?php else: ?>
                            <span class="badge badge-<?= $d['situacao'] ?>"><?= $d['situacao'] ?></span>
                        <?php endif; ?>
                    </td>
                    
                    <td>
                        <?php if ($d['situacao'] === 'Entregue' || $d['situacao'] === 'Cancelado'): ?>
                            <span class="texto-finalizado">
                                <i class="fas fa-check-circle"></i> <?= $d['situacao'] ?>
                            </span>
                        <?php else: ?>
                            <a href="editar_doacao.php?id=<?= $d['id_doacao'] ?>" class="btn-acao btn-editar">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            <form action="atualizar_status_doacao.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_doacao" value="<?= $d['id_doacao'] ?>">
                                <input type="hidden" name="novo_status" value="Entregue">
                                <button type="submit" class="btn-acao btn-entregue">
                                    <i class="fas fa-check"></i> Entregue
                                </button>
                            </form>
                            
                            <form action="atualizar_status_doacao.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id_doacao" value="<?= $d['id_doacao'] ?>">
                                <input type="hidden" name="novo_status" value="Cancelado">
                                <button type="submit" class="btn-acao btn-cancelar">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>