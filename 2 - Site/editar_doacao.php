<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}
require_once 'conexao.php';

$logado   = isset($_SESSION['id_usuario']);
$nome_ses = $logado ? explode(' ', $_SESSION['nome'])[0] : '';
$is_admin = $logado && $_SESSION['tipo_usuario'] === 'superadmin';

$id_usuario = $_SESSION['id_usuario'];
$id_doacao = $_GET['id'] ?? ($_POST['id_doacao'] ?? null);

// Segurança: Verifica se o ID foi passado e se a doação pertence a este usuário
if (!$id_doacao) {
    header('Location: painel_usuario.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM doacao WHERE id_doacao = ? AND id_usuario = ?");
$stmt->execute([$id_doacao, $id_usuario]);
$doacao = $stmt->fetch();

if (!$doacao) {
    header('Location: painel_usuario.php');
    exit;
}

// Busca as fotos atuais para exibir no preview
$stmt_fotos = $pdo->prepare("SELECT caminho FROM doacao_fotos WHERE id_doacao = ? ORDER BY ordem");
$stmt_fotos->execute([$id_doacao]);
$fotos_atuais = $stmt_fotos->fetchAll(PDO::FETCH_COLUMN);

// ── Credenciais Cloudinary ──────────────────────────────────────
define('CLOUDINARY_CLOUD_NAME', 'doacaomais');   // substitua
define('CLOUDINARY_API_KEY',    'teste');       // substitua
define('CLOUDINARY_API_SECRET', 'teste');    // substitua
define('CLOUDINARY_FOLDER',     'doacaomais');

function uploadCloudinary(string $tmpPath, string $nomeOriginal): ?string
{
    $timestamp  = time();
    $folder     = CLOUDINARY_FOLDER;
    $paramsSign = "folder={$folder}&timestamp={$timestamp}" . CLOUDINARY_API_SECRET;
    $signature  = sha1($paramsSign);
    $url = 'https://api.cloudinary.com/v1_1/' . CLOUDINARY_CLOUD_NAME . '/image/upload';

    $dados = [
        'file'      => new CURLFile($tmpPath, mime_content_type($tmpPath), $nomeOriginal),
        'api_key'   => CLOUDINARY_API_KEY,
        'timestamp' => (string)$timestamp,
        'folder'    => $folder,
        'signature' => $signature,
    ];

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $dados,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $resposta = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($resposta, true);
    return $json['secure_url'] ?? null;
}

// ── Processamento da Edição ─────────────────────────────────
$erro    = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo      = trim($_POST['titulo']      ?? '');
    $categoria   = trim($_POST['categoria']   ?? '');
    $descricao   = trim($_POST['descricao']   ?? '');
    $cep         = preg_replace('/\D/', '', $_POST['cep'] ?? '');
    $logradouro  = trim($_POST['rua']         ?? '');
    $numero      = trim($_POST['numero']      ?? '');
    $complemento = trim($_POST['complemento'] ?? '');
    $bairro      = trim($_POST['bairro']      ?? '');
    $localidade  = trim($_POST['cidade']      ?? '');
    $uf          = trim($_POST['uf']          ?? '');

    if (empty($titulo)) { $erro = 'Informe o título do item.'; }
    elseif (empty($categoria)) { $erro = 'Selecione uma categoria.'; }
    elseif (strlen($cep) !== 8) { $erro = 'CEP inválido.'; }
    elseif (empty($logradouro) || empty($numero) || empty($bairro) || empty($localidade) || empty($uf)) {
        $erro = 'Preencha o endereço completo.';
    } else {
        $cep_fmt = substr($cep, 0, 5) . '-' . substr($cep, 5);

        // ATUALIZA os dados da doação e FORÇA a situação para 'Pendente'
        $stmt_update = $pdo->prepare("
            UPDATE doacao SET 
                categoria = ?, titulo = ?, descricao = ?, cep = ?, 
                logradouro = ?, numero = ?, complemento = ?, bairro = ?, localidade = ?, uf = ?,
                situacao = 'Pendente'
            WHERE id_doacao = ? AND id_usuario = ?
        ");
        
        $stmt_update->execute([
            $categoria, $titulo, $descricao, $cep_fmt, 
            $logradouro, $numero, $complemento, $bairro, $localidade, $uf, 
            $id_doacao, $id_usuario
        ]);

        // Upload das novas fotos para o Cloudinary (Se houver novas fotos selecionadas)
        if (!empty($_FILES['fotos']['name'][0])) {
            
            // Apaga os registros das fotos antigas no banco
            $del_fotos = $pdo->prepare("DELETE FROM doacao_fotos WHERE id_doacao = ?");
            $del_fotos->execute([$id_doacao]);

            $total = min(count($_FILES['fotos']['name']), 5); 
            $ordem = 1;

            for ($i = 0; $i < $total; $i++) {
                if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) continue;

                $ext = strtolower(pathinfo($_FILES['fotos']['name'][$i], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($ext, $permitidos)) continue;

                $url_cloudinary = uploadCloudinary(
                    $_FILES['fotos']['tmp_name'][$i],
                    $_FILES['fotos']['name'][$i]
                );

                if ($url_cloudinary) {
                    $foto = $pdo->prepare("INSERT INTO doacao_fotos (id_doacao, caminho, ordem) VALUES (?, ?, ?)");
                    $foto->execute([$id_doacao, $url_cloudinary, $ordem]);
                    $ordem++;
                }
            }
        }

        $sucesso = 'Alterações salvas!<br><small>O anúncio voltou para análise do administrador.</small>';
        
        // Atualiza a variável $doacao para mostrar os novos dados na tela logo após salvar
        $stmt->execute([$id_doacao, $id_usuario]);
        $doacao = $stmt->fetch();
        
        $stmt_fotos->execute([$id_doacao]);
        $fotos_atuais = $stmt_fotos->fetchAll(PDO::FETCH_COLUMN);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Editar Doação</title>
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
        .saudacao-header { display: flex; align-items: center; gap: 8px; font-size: .9rem; color: #555; font-weight: 600; }
        .saudacao-header strong { color: var(--cor-primaria); }
        .saudacao-header .dropdown { position: relative; }
        .saudacao-header .dropdown-menu { display: none; position: absolute; right: 0; top: 110%; background: #fff; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,.12); min-width: 170px; z-index: 100; overflow: hidden; }
        .saudacao-header .dropdown:hover .dropdown-menu { display: block; }
        .saudacao-header .dropdown-menu a { display: block; padding: 11px 16px; color: #333; text-decoration: none; font-size: .88rem; font-weight: 500; transition: background .15s; }
        .saudacao-header .dropdown-menu a:hover { background: #f4f7f6; }
        .saudacao-header .dropdown-menu a.sair { color: var(--vermelho-erro); }
        .preview-fotos { display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
        .preview-fotos img { width:100px; height:100px; object-fit:cover; border-radius:8px; border:2px solid var(--cor-primaria); }
        .upload-area { border:2px dashed var(--cor-primaria); border-radius:10px; padding:20px; text-align:center; cursor:pointer; color:var(--cor-primaria); background:var(--cinza-input); transition:background .2s; }
        .upload-area:hover { background:#e8f5e9; }
        .upload-area i { font-size:2rem; display:block; margin-bottom:8px; }
        .cloudinary-badge { display:inline-flex; align-items:center; gap:6px; background:#fff3cd; border:1px solid #ffeeba; border-radius:20px; padding:8px 12px; font-size:0.85rem; color:#856404; font-weight:600; margin-top:8px; margin-bottom: 5px; }
        .btn-voltar { display: inline-block; color: #666; text-decoration: none; margin-bottom: 20px; font-weight: 600; }
        .btn-voltar:hover { color: var(--cor-primaria); }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="doar-container">

    <a href="painel_usuario.php" class="btn-voltar"><i class="fas fa-arrow-left"></i> Voltar ao Painel</a>

    <?php if ($erro): ?>
        <div class="alert-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <div class="alert-sucesso"><?= $sucesso ?></div>
    <?php endif; ?>

    <div class="doar-header">
        <h1>Editar Doação</h1>
        <p>Altere os dados do seu item publicado (#<?= $id_doacao ?>)</p>
    </div>

    <form class="doar-form" method="POST" action="editar_doacao.php" enctype="multipart/form-data">
        <input type="hidden" name="id_doacao" value="<?= $id_doacao ?>">

        <div class="form-group">
            <label><i class="fas fa-tag"></i> Título do Item</label>
            <input type="text" name="titulo" value="<?= htmlspecialchars($doacao['titulo']) ?>" required>
        </div>

        <div class="form-group">
            <label><i class="fas fa-list"></i> Categoria</label>
            <div class="categoria-opcoes">
                <?php
                $cats = ['Móveis','Eletrônicos','Roupas','Livros','Brinquedos',
                         'Calçados','Eletrodomésticos','Alimentos','Materiais Escolares','Outros'];
                foreach ($cats as $cat):
                    $checked = ($doacao['categoria'] === $cat) ? 'checked' : '';
                ?>
                <label class="categoria-item">
                    <input type="radio" name="categoria" value="<?= $cat ?>" <?= $checked ?> required> <?= $cat ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group">
            <label><i class="fas fa-align-left"></i> Descrição</label>
            <textarea name="descricao"><?= htmlspecialchars($doacao['descricao']) ?></textarea>
        </div>

        <div class="form-group full-width">
            <div style="display:flex; gap:10px; align-items:flex-end;">
                <div style="flex:2;">
                    <label for="cep" style="margin-bottom:5px;">
                        <i class="fas fa-search-location"></i> CEP
                    </label>
                    <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($doacao['cep']) ?>" maxlength="9">
                </div>
                <div style="flex:1;">
                    <button type="button" id="btnBuscarCep" class="btn-buscar-cep">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>

        <div class="form-group full-width">
            <input type="text" id="rua" name="rua" value="<?= htmlspecialchars($doacao['logradouro']) ?>" required>
        </div>

        <div class="form-group">
            <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($doacao['numero']) ?>" maxlength="6" required>
        </div>
        <div class="form-group">
            <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($doacao['bairro']) ?>" required>
        </div>

        <div class="form-group full-width">
            <input type="text" id="complemento" name="complemento" value="<?= htmlspecialchars($doacao['complemento']) ?>">
        </div>

        <div class="form-group">
            <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($doacao['localidade']) ?>" required>
        </div>
        <div class="form-group">
            <select id="uf" name="uf" required>
                <option value="">UF</option>
                <?php 
                $estados = ['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'];
                foreach($estados as $estado):
                    $selecionado = ($doacao['uf'] === $estado) ? 'selected' : '';
                ?>
                    <option value="<?= $estado ?>" <?= $selecionado ?>><?= $estado ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group full-width">
            <label><i class="fas fa-camera"></i> Fotos do Item</label>
            <div class="cloudinary-badge">
                <i class="fas fa-exclamation-triangle"></i> Atenção: Qualquer alteração salva fará com que o anúncio volte para análise do administrador.
            </div>
            
            <div class="upload-area" id="uploadArea" onclick="document.getElementById('fotos').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                Clique para alterar as fotos
                <small style="display:block; color:#888; margin-top:5px;">JPG, PNG ou WEBP — até 5 fotos</small>
            </div>
            <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple
                   style="display:none;" onchange="previewFotos(this)">
            
            <div class="preview-fotos" id="previewFotos">
                <?php foreach($fotos_atuais as $foto): ?>
                    <img src="<?= htmlspecialchars($foto) ?>" alt="Foto atual">
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="btn-anunciar" style="background-color: #007bff;">
            <i class="fas fa-save"></i> Salvar Alterações
        </button>
    </form>
</main>

<?php include 'footer.php'; ?>

<script>
document.getElementById('cep').addEventListener('input', function () {
    let v = this.value.replace(/\D/g, '');
    v = v.replace(/^(\d{5})(\d)/, '$1-$2');
    this.value = v;
});

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

const uploadArea = document.getElementById('uploadArea');

uploadArea.addEventListener('dragover', function (e) {
    e.preventDefault();
    this.style.background = '#e8f5e9';
    this.style.borderColor = '#007bff';
});

uploadArea.addEventListener('dragleave', function () {
    this.style.background = '';
    this.style.borderColor = '';
});

uploadArea.addEventListener('drop', function (e) {
    e.preventDefault();
    this.style.background = '';
    this.style.borderColor = '';

    const input = document.getElementById('fotos');
    const dt = e.dataTransfer;
    input.files = dt.files;
    previewFotos(input);
});

function previewFotos(input) {
    const preview = document.getElementById('previewFotos');
    preview.innerHTML = '';
    const files = Array.from(input.files).slice(0, 5);
    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
</body>
</html>