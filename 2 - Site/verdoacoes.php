<?php
session_start();
require_once 'conexao.php';

$logado   = isset($_SESSION['id_usuario']);
$nome_ses = $logado ? explode(' ', $_SESSION['nome'])[0] : '';
$is_admin = $logado && $_SESSION['tipo_usuario'] === 'superadmin';

// Filtro por categoria
$filtro_cat = trim($_GET['categoria'] ?? '');

$cats = ['Móveis','Eletrônicos','Roupas','Livros','Brinquedos',
         'Calçados','Eletrodomésticos','Alimentos','Materiais Escolares','Outros'];

// Busca doações aprovadas
$sql = "
    SELECT d.id_doacao, d.titulo, d.categoria, d.descricao,
           d.localidade, d.uf,
           u.nome AS doador,
           (SELECT caminho FROM doacao_fotos WHERE id_doacao = d.id_doacao ORDER BY ordem LIMIT 1) AS capa
    FROM doacao d
    JOIN usuario u ON u.id_usuario = d.id_usuario
    WHERE d.situacao = 'Aprovado'
";
$params = [];
if ($filtro_cat) {
    $sql .= " AND d.categoria = ?";
    $params[] = $filtro_cat;
}
$sql .= " ORDER BY d.id_doacao DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doacoes = $stmt->fetchAll();

// Total por categoria (para badges)
$contagem = [];
$stmtCont = $pdo->query("SELECT categoria, COUNT(*) as qtd FROM doacao WHERE situacao = 'Aprovado' GROUP BY categoria");
foreach ($stmtCont->fetchAll() as $row) {
    $contagem[$row['categoria']] = $row['qtd'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Ver Doações</title>
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

        .verdoacoes-hero {
            background: linear-gradient(135deg, #1b5e20, #2e7d32);
            color: #fff;
            text-align: center;
            padding: calc(12vh) 20px 40px;
        }
        .verdoacoes-hero h1 { font-family: 'Arvo', serif; font-size: 2.2rem; margin-bottom: 8px; }
        .verdoacoes-hero p  { opacity: .85; font-size: 1rem; }

        .verdoacoes-wrapper { max-width: 1100px; margin: 35px auto; padding: 0 20px; }

        /* Busca */
        .barra-busca {
            display: flex; gap: 10px; margin-bottom: 25px;
        }
        .barra-busca input {
            flex: 1; padding: 11px 16px; border: 1px solid #ddd;
            border-radius: 8px; font-size: 0.95rem; outline: none;
        }
        .barra-busca input:focus { border-color: var(--cor-primaria); }

        /* Filtros categoria */
        .filtros-cat {
            display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 30px;
        }
        .filtro-tag {
            padding: 6px 14px; border-radius: 20px; text-decoration: none;
            font-size: 0.83rem; font-weight: 600;
            background: #eee; color: #555; transition: background .2s;
        }
        .filtro-tag:hover  { background: #ddd; }
        .filtro-tag.ativo  { background: var(--cor-primaria); color: #fff; }
        .filtro-tag .qtd   { font-size: 0.75rem; opacity: .75; margin-left: 4px; }

        /* Grid */
        .grid-doacoes {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 22px;
        }

        /* Card */
        .card-doacao {
            background: #fff; border-radius: 14px;
            box-shadow: var(--sombra-suave); overflow: hidden;
            display: flex; flex-direction: column;
            transition: transform .2s, box-shadow .2s;
        }
        .card-doacao:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0,0,0,.13);
            cursor: pointer;
        }
        .card-doacao .capa {
            width: 100%; height: 190px; object-fit: cover;
        }
        .card-doacao .sem-foto {
            width: 100%; height: 190px;
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            display: flex; align-items: center; justify-content: center;
            color: var(--cor-primaria); font-size: 3rem;
        }
        .card-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
        .card-body .categoria-badge {
            display: inline-block; background: #e8f5e9; color: var(--cor-primaria);
            font-size: 0.75rem; font-weight: 700; padding: 3px 10px;
            border-radius: 20px; margin-bottom: 8px;
        }
        .card-body h3 {
            font-size: 1rem; font-weight: 700; color: var(--texto-escuro);
            margin-bottom: 6px; line-height: 1.3;
        }
        .card-body .descricao {
            font-size: 0.87rem; color: #666; flex: 1;
            display: -webkit-box; -webkit-line-clamp: 3;
            -webkit-box-orient: vertical; overflow: hidden;
            margin-bottom: 12px;
        }
        .card-footer-info {
            display: flex; align-items: center; justify-content: space-between;
            border-top: 1px solid #f0f0f0; padding-top: 10px; margin-top: auto;
        }
        .card-footer-info .local {
            font-size: 0.8rem; color: #888;
        }
        .card-footer-info .local i { color: var(--cor-primaria); margin-right: 3px; }
        .btn-interesse {
            display: inline-block; background: var(--cor-primaria); color: #fff;
            padding: 7px 14px; border-radius: 7px; font-size: 0.85rem;
            font-weight: 600; text-decoration: none; border: none; cursor: pointer;
            transition: background .2s;
        }
        .btn-interesse:hover { background: var(--verde-escuro); }

        /* Vazio */
        .estado-vazio {
            text-align: center; padding: 60px 20px; color: #999;
        }
        .estado-vazio i { font-size: 3rem; display: block; margin-bottom: 12px; }

        /* Modal */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.55); z-index: 1000;
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
        .modal-fotos { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
        .modal-fotos img {
            width: 110px; height: 110px; object-fit: contain;
            border-radius: 8px; border: 2px solid #eee; cursor: pointer;
        }
        .modal-fotos img.principal { width: 100%; height: 330px; margin-bottom: 8px; }
        .modal-info p { font-size: 0.92rem; color: #555; margin-bottom: 6px; }
        .modal-info strong { color: var(--texto-escuro); }

        /* Resultado busca */
        .resultado-info {
            font-size: 0.88rem; color: #888; margin-bottom: 15px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<!-- Hero -->
<section class="verdoacoes-hero">
    <h1><i class="fas fa-hand-holding-heart"></i> Doações Disponíveis</h1>
    <p>Encontre itens disponíveis para retirada na sua região</p>
</section>

<div class="verdoacoes-wrapper">

<?php if (isset($_GET['sucesso']) && $_GET['sucesso'] === 'denuncia'): ?>
    <div class="alert-sucesso" style="position:static; border-radius:10px; margin-bottom:20px;">
        <i class="fas fa-check-circle"></i> Denúncia enviada com sucesso. Analisaremos em breve.
    </div>
<?php endif; ?>
<?php if (isset($_GET['aviso']) && $_GET['aviso'] === 'ja_denunciado'): ?>
    <div class="alert-erro" style="position:static; border-radius:10px; margin-bottom:20px;">
        <i class="fas fa-exclamation-circle"></i> Você já denunciou esta doação.
    </div>
<?php endif; ?>

    <!-- Busca -->
    <div class="barra-busca">
        <input type="text" id="campoBusca" placeholder="Buscar por título, categoria ou cidade...">
    </div>

    <!-- Filtros -->
    <div class="filtros-cat">
        <a href="verdoacoes.php"
           class="filtro-tag <?= !$filtro_cat ? 'ativo' : '' ?>">
            Todas
        </a>
        <?php foreach ($cats as $cat): ?>
        <a href="verdoacoes.php?categoria=<?= urlencode($cat) ?>"
           class="filtro-tag <?= $filtro_cat === $cat ? 'ativo' : '' ?>">
            <?= $cat ?>
            <?php if (!empty($contagem[$cat])): ?>
                <span class="qtd">(<?= $contagem[$cat] ?>)</span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- Info resultado -->
    <div class="resultado-info" id="resultadoInfo">
        <?= count($doacoes) ?> doação(ões) encontrada(s)
        <?= $filtro_cat ? ' em <strong>' . htmlspecialchars($filtro_cat) . '</strong>' : '' ?>
    </div>

    <!-- Grid -->
    <?php if (empty($doacoes)): ?>
        <div class="estado-vazio">
            <i class="fas fa-box-open"></i>
            Nenhuma doação disponível no momento.
        </div>
    <?php else: ?>
    <div class="grid-doacoes" id="gridDoacoes">
        <?php foreach ($doacoes as $d): ?>
        <div class="card-doacao"
     data-titulo="<?= strtolower(htmlspecialchars($d['titulo'])) ?>"
     data-categoria="<?= strtolower(htmlspecialchars($d['categoria'])) ?>"
     data-cidade="<?= strtolower(htmlspecialchars($d['localidade'])) ?>"
     onclick="abrirModal(<?= $d['id_doacao'] ?>)"
     style="cursor:pointer;">

            <?php if ($d['capa']): ?>
                <img class="capa" src="<?= htmlspecialchars($d['capa']) ?>"
                     alt="<?= htmlspecialchars($d['titulo']) ?>">
            <?php else: ?>
                <div class="sem-foto"><i class="fas fa-gift"></i></div>
            <?php endif; ?>

            <div class="card-body">
                <span class="categoria-badge"><?= htmlspecialchars($d['categoria']) ?></span>
                <h3><?= htmlspecialchars($d['titulo']) ?></h3>
                <p class="descricao">
                    <?= $d['descricao'] ? htmlspecialchars($d['descricao']) : '<em style="color:#bbb;">Sem descrição.</em>' ?>
                </p>
                <div class="card-footer-info">
                    <span class="local">
                        <i class="fas fa-map-marker-alt"></i>
                        <?= htmlspecialchars($d['localidade']) ?>/<?= htmlspecialchars($d['uf']) ?>
                    </span>
                    <button class="btn-interesse"
                            onclick="abrirModal(<?= $d['id_doacao'] ?>)">
                        <i class="fas fa-eye"></i> Ver mais
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Modal detalhes -->
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

<!-- Modal Denúncia -->
<div id="modalDenuncia" style="display:none; position:fixed; inset:0;
     background:rgba(0,0,0,.55); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:28px; max-width:450px;
                width:90%; position:relative;">
        <button onclick="document.getElementById('modalDenuncia').style.display='none'"
                style="position:absolute; top:12px; right:16px; background:none;
                       border:none; font-size:1.3rem; cursor:pointer; color:#888;">
            <i class="fas fa-times"></i>
        </button>
        <h3 style="margin-bottom:16px; color:#c0392b;">
            <i class="fas fa-flag"></i> Denunciar Doação
        </h3>
        <form method="POST" action="denunciar.php">
            <input type="hidden" name="id_doacao" id="idDoacaoDenuncia">
            <div style="margin-bottom:14px;">
                <label style="font-size:.88rem; font-weight:600; display:block; margin-bottom:6px;">Motivo</label>
                <select name="motivo" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;">
                    <option value="">Selecione...</option>
                    <option>Item inexistente ou falso</option>
                    <option>Conteúdo impróprio</option>
                    <option>Spam ou anúncio duplicado</option>
                    <option>Comportamento suspeito</option>
                    <option>Outro</option>
                </select>
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:.88rem; font-weight:600; display:block; margin-bottom:6px;">Descrição</label>
                <textarea name="descricao" rows="3" placeholder="Descreva o problema..."
                          style="width:100%; padding:10px; border:1px solid #ddd;
                                 border-radius:8px; resize:vertical; font-family:inherit;"></textarea>
            </div>
            <button type="submit" style="width:100%; background:#c0392b; color:#fff;
                    border:none; padding:12px; border-radius:8px; font-weight:700;
                    font-size:.95rem; cursor:pointer;">
                <i class="fas fa-paper-plane"></i> Enviar Denúncia
            </button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- detalhe_doacao.php retorna JSON com os dados completos -->
<script>
// ── Busca em tempo real ──────────────────────────────────────────
document.getElementById('campoBusca').addEventListener('input', function () {
    const termo = this.value.toLowerCase().trim();
    const cards = document.querySelectorAll('.card-doacao');
    let visiveis = 0;

    cards.forEach(card => {
        const titulo    = card.dataset.titulo    || '';
        const categoria = card.dataset.categoria || '';
        const cidade    = card.dataset.cidade    || '';
        const bate = !termo || titulo.includes(termo) || categoria.includes(termo) || cidade.includes(termo);
        card.style.display = bate ? '' : 'none';
        if (bate) visiveis++;
    });

    document.getElementById('resultadoInfo').innerHTML =
        visiveis + ' doação(ões) encontrada(s)';
});

// ── Modal ────────────────────────────────────────────────────────
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
                    fotosHtml += '<div class="modal-fotos" style="margin-top:0;">';
                    d.fotos.forEach((f, i) => {
                        fotosHtml += '<img src="' + f + '" onclick="trocarFoto(\'' + f + '\')" ' +
                                     (i === 0 ? 'style="border-color:var(--cor-primaria);"' : '') + '>';
                    });
                    fotosHtml += '</div>';
                }
            } else {
                fotosHtml = '<div class="sem-foto" style="height:180px;border-radius:10px;margin-bottom:12px;">' +
                            '<i class="fas fa-gift"></i></div>';
            }

            document.getElementById('modalConteudo').innerHTML = `
                <div class="modal-fotos" style="flex-direction:column;">${fotosHtml}</div>
                <div class="modal-info">
                    <span class="categoria-badge" style="background:#e8f5e9;color:var(--cor-primaria);
                          font-size:.78rem;font-weight:700;padding:3px 10px;border-radius:20px;
                          display:inline-block;margin-bottom:10px;">${d.categoria}</span>
                    <h3 style="font-size:1.15rem;margin-bottom:10px;">${d.titulo}</h3>
                    <p><strong>Descrição:</strong> ${d.descricao || '<em style="color:#bbb;">Sem descrição.</em>'}</p>
                    <p style="margin-top:8px;"><strong><i class="fas fa-map-marker-alt" style="color:var(--cor-primaria);"></i> Local:</strong>
                        ${d.logradouro}, ${d.numero}${d.complemento ? ', ' + d.complemento : ''} — ${d.bairro}, ${d.localidade}/${d.uf}
                    </p>
                    <p style="margin-top:6px;"><strong><i class="fas fa-user" style="color:var(--cor-primaria);"></i> Doador:</strong> ${d.doador}</p>
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-top:14px; gap:10px; border-top:1px solid #eee; padding-top:14px;">
    ${!d.logado ?
    '<p style="margin-top:14px;background:#fff3cd;padding:10px;border-radius:8px;font-size:.88rem;">' +
    '<i class="fas fa-lock"></i> <a href="login.php">Faça login</a> para ver os dados do doador.</p>'
    :
    '<div style="margin-top:14px;background:#f1f8e9;border-radius:10px;padding:14px;border:1px solid #c8e6c9;">' +
    '<p style="font-size:.85rem;font-weight:700;color:var(--cor-primaria);margin-bottom:8px;"><i class="fas fa-user-circle"></i> Dados do Doador</p>' +
    '<p style="font-size:.9rem;color:#333;margin-bottom:6px;"><i class="fas fa-user" style="color:var(--cor-primaria);width:16px;"></i> ' + d.doador + '</p>' +
    (d.doador_tel ? '<p style="font-size:.9rem;color:#333;margin-bottom:6px;"><i class="fas fa-phone" style="color:var(--cor-primaria);width:16px;"></i> ' + d.doador_tel + '</p>' : '') +
    '<p style="font-size:.9rem;color:#333;"><i class="fas fa-envelope" style="color:var(--cor-primaria);width:16px;"></i> ' + d.doador_email + '</p>' +
    '</div>'
}
    <button onclick="abrirDenuncia(${d.id_doacao})"
            style="background:#fff0f0; color:#c0392b; border:1px solid #f5c6cb;
                   padding:9px 18px; border-radius:8px; font-weight:600;
                   font-size:.85rem; cursor:pointer; white-space:nowrap;">
        <i class='fas fa-flag'></i> Denunciar
    </button>
</div>
                </div>
            `;
        })
        .catch(() => {
            document.getElementById('modalConteudo').innerHTML =
                '<p style="color:red;">Erro ao carregar detalhes.</p>';
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

function abrirDenuncia(id) {
    document.getElementById('idDoacaoDenuncia').value = id;
    document.getElementById('modalDenuncia').style.display = 'flex';
}
</script>
</body>
</html>
