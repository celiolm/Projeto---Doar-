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

// ── Credenciais Cloudinary ──────────────────────────────────────
define('CLOUDINARY_CLOUD_NAME', 'doacaomais');   // substitua
define('CLOUDINARY_API_KEY',    'teste');       // substitua
define('CLOUDINARY_API_SECRET', 'teste');    // substitua
define('CLOUDINARY_FOLDER',     'doacaomais');

// ── Função: upload de uma imagem para o Cloudinary ──────────────
function uploadCloudinary(string $tmpPath, string $nomeOriginal): ?string
{
    $timestamp  = time();
    $folder     = CLOUDINARY_FOLDER;

    // Parâmetros em ordem alfabética para assinatura correta
    $paramsSign = "folder={$folder}&timestamp={$timestamp}" . CLOUDINARY_API_SECRET;
    $signature  = sha1($paramsSign);

    // Teste: mostra resposta completa do Cloudinary
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
        CURLOPT_SSL_VERIFYPEER => false, // necessário no WAMP local
    ]);
    $resposta = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($resposta, true);
    return $json['secure_url'] ?? null;
}

// ── Processamento do formulário ─────────────────────────────────
$erro    = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario  = $_SESSION['id_usuario'];
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

    if (empty($titulo))     { $erro = 'Informe o título do item.'; }
    elseif (empty($categoria)) { $erro = 'Selecione uma categoria.'; }
    elseif (strlen($cep) !== 8) { $erro = 'CEP inválido.'; }
    elseif (empty($logradouro) || empty($numero) || empty($bairro) || empty($localidade) || empty($uf)) {
        $erro = 'Preencha o endereço completo.';
    } else {
        $cep_fmt = substr($cep, 0, 5) . '-' . substr($cep, 5);

        // Insere doação
        $stmt = $pdo->prepare("
            INSERT INTO doacao
                (id_usuario, categoria, titulo, descricao, cep, logradouro, numero, complemento, bairro, localidade, uf)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $id_usuario, $categoria, $titulo, $descricao,
            $cep_fmt, $logradouro, $numero, $complemento,
            $bairro, $localidade, $uf
        ]);
        $id_doacao = $pdo->lastInsertId();

        // Upload das fotos para o Cloudinary
        if (!empty($_FILES['fotos']['name'][0])) {
            $total = min(count($_FILES['fotos']['name']), 5); // máximo 5
            $ordem = 1;

            for ($i = 0; $i < $total; $i++) {
                if ($_FILES['fotos']['error'][$i] !== UPLOAD_ERR_OK) continue;

                $ext       = strtolower(pathinfo($_FILES['fotos']['name'][$i], PATHINFO_EXTENSION));
                $permitidos = ['jpg', 'jpeg', 'png', 'webp'];
                if (!in_array($ext, $permitidos)) continue;

                $url_cloudinary = uploadCloudinary(
                    $_FILES['fotos']['tmp_name'][$i],
                    $_FILES['fotos']['name'][$i]
                );

                if ($url_cloudinary) {
                    $foto = $pdo->prepare("
                        INSERT INTO doacao_fotos (id_doacao, caminho, ordem) VALUES (?, ?, ?)
                    ");
                    $foto->execute([$id_doacao, $url_cloudinary, $ordem]);
                    $ordem++;
                }
            }
        }

        $sucesso = 'Doação anunciada com sucesso! Aguarde aprovação.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Anunciar Doação</title>
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
        .preview-fotos { display:flex; flex-wrap:wrap; gap:10px; margin-top:10px; }
        .preview-fotos img {
            width:100px; height:100px; object-fit:cover;
            border-radius:8px; border:2px solid var(--cor-primaria);
        }
        .upload-area {
            border:2px dashed var(--cor-primaria); border-radius:10px;
            padding:20px; text-align:center; cursor:pointer;
            color:var(--cor-primaria); background:var(--cinza-input);
            transition:background .2s;
        }
        .upload-area:hover { background:#e8f5e9; }
        .upload-area i { font-size:2rem; display:block; margin-bottom:8px; }
        .cloudinary-badge {
            display:inline-flex; align-items:center; gap:6px;
            background:#f0f4ff; border:1px solid #c7d2fe;
            border-radius:20px; padding:4px 12px;
            font-size:0.78rem; color:#4338ca; font-weight:600;
            margin-top:8px;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<main class="doar-container">

    <?php if ($erro): ?>
        <div class="alert-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
        <div class="alert-sucesso"><?= htmlspecialchars($sucesso) ?></div>
    <?php endif; ?>

    <div class="doar-header">
        <h1>Anunciar Doação</h1>
        <p>Preencha os dados abaixo para anunciar seu item</p>
    </div>

    <form class="doar-form" method="POST" action="doar.php" enctype="multipart/form-data">

        <!-- Título -->
        <div class="form-group">
            <label><i class="fas fa-tag"></i> Título do Item</label>
            <input type="text" name="titulo" placeholder="Ex: Sofá em bom estado, Livros infantis..." required>
        </div>

        <!-- Categoria -->
        <div class="form-group">
            <label><i class="fas fa-list"></i> Categoria</label>
            <div class="categoria-opcoes">
                <?php
                $cats = ['Móveis','Eletrônicos','Roupas','Livros','Brinquedos',
                         'Calçados','Eletrodomésticos','Alimentos','Materiais Escolares','Outros'];
                foreach ($cats as $cat):
                ?>
                <label class="categoria-item">
                    <input type="radio" name="categoria" value="<?= $cat ?>" required> <?= $cat ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Descrição -->
        <div class="form-group">
            <label><i class="fas fa-align-left"></i> Descrição</label>
            <textarea name="descricao" placeholder="Descreva o item, estado de conservação, detalhes importantes..."></textarea>
        </div>

        <!-- CEP -->
        <div class="form-group full-width">
            <div style="display:flex; gap:10px; align-items:flex-end;">
                <div style="flex:0 0 120px;">
                    <label for="cep" style="margin-bottom:5px;">
                        <i class="fas fa-search-location"></i> CEP
                    </label>
                    <input type="text" id="cep" name="cep" placeholder="00000-000" maxlength="9" style="width:120px;">
                </div>
                <div>
                    <button type="button" id="btnBuscarCep" class="btn-buscar-cep">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
            <small style="color:#666; display:block; margin-top:5px;">
                <i class="fas fa-info-circle"></i> Digite o CEP e clique em Buscar para preencher automaticamente
            </small>
        </div>

        <!-- Rua -->
        <div class="form-group full-width">
            <input type="text" id="rua" name="rua" placeholder="Rua / Avenida" required>
        </div>

        <!-- Número e Bairro -->
        <div class="form-group">
            <input type="text" id="numero" name="numero" placeholder="Número" maxlength="6" required>
        </div>
        <div class="form-group">
            <input type="text" id="bairro" name="bairro" placeholder="Bairro" required>
        </div>

        <!-- Complemento -->
        <div class="form-group full-width">
            <input type="text" id="complemento" name="complemento" placeholder="Complemento (opcional)">
        </div>

        <!-- Cidade e UF -->
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

        <!-- Fotos -->
        <div class="form-group">
            <label><i class="fas fa-camera"></i> Fotos do Item</label>
            <div class="upload-area" id="uploadArea" onclick="document.getElementById('fotos').click()">
    <i class="fas fa-cloud-upload-alt"></i>
    Clique ou arraste as fotos aqui
    <small style="display:block; color:#888; margin-top:5px;">JPG, PNG ou WEBP — até 5 fotos</small>
</div>
            <input type="file" id="fotos" name="fotos[]" accept="image/*" multiple
                   style="display:none;" onchange="previewFotos(this)">
            <div class="cloudinary-badge">
                <i class="fas fa-cloud"></i> Armazenado no Cloudinary
            </div>
            <div class="preview-fotos" id="previewFotos"></div>
        </div>


        <!-- Botão -->
        <button type="submit" class="btn-anunciar">
            <i class="fas fa-hand-holding-heart"></i> Anunciar Doação
        </button>
    </form>
</main>

<?php include 'footer.php'; ?>

<script>
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

// Drag and drop
const uploadArea = document.getElementById('uploadArea');

uploadArea.addEventListener('dragover', function (e) {
    e.preventDefault();
    this.style.background = '#e8f5e9';
    this.style.borderColor = 'var(--verde-escuro)';
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

// Preview fotos
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
