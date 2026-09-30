<?php
session_start();
$logado   = isset($_SESSION['id_usuario']);
$nome_ses = $logado ? explode(' ', $_SESSION['nome'])[0] : '';
$is_admin = $logado && ($_SESSION['tipo_usuario'] === 'superadmin');

// Verifica se foi aberto como modal (via ?modal=1)
$is_modal = isset($_GET['modal']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doar+ | Termos de Uso</title>
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
        /* ── Overlay (quando aberto como modal) ── */
        <?php if ($is_modal): ?>
        body {
            background: transparent !important;
            margin: 0; padding: 0;
        }
        .modal-termos-overlay {
            position: fixed; inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 9000;
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
        }
        .modal-termos-box {
            background: #fff;
            border-radius: 18px;
            max-width: 720px;
            width: 100%;
            max-height: 88vh;
            overflow-y: auto;
            padding: 40px;
            position: relative;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            animation: slideUp .3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .btn-fechar-modal {
            position: absolute; top: 16px; right: 20px;
            background: #f4f7f6; border: none; border-radius: 50%;
            width: 36px; height: 36px; font-size: 1.1rem;
            cursor: pointer; color: #666;
            display: flex; align-items: center; justify-content: center;
            transition: background .2s;
        }
        .btn-fechar-modal:hover { background: #e0e0e0; }
        <?php else: ?>
        /* ── Página normal ── */
        .termos-wrapper {
            max-width: 800px;
            margin: 100px auto 40px;
            padding: 0 20px;
        }
        <?php endif; ?>

        /* ── Conteúdo dos termos ── */
        .termos-conteudo h1 {
            font-family: 'Arvo', serif;
            font-size: 1.7rem;
            color: var(--texto-escuro);
            margin-bottom: 6px;
        }
        .termos-conteudo .data-atualizacao {
            font-size: .83rem; color: #aaa; margin-bottom: 28px;
        }
        .termos-conteudo h2 {
            font-family: 'Arvo', serif;
            font-size: 1.05rem;
            color: var(--cor-primaria);
            margin: 28px 0 10px;
            display: flex; align-items: center; gap: 8px;
        }
        .termos-conteudo h2 i { font-size: .95rem; }
        .termos-conteudo p {
            font-size: .93rem;
            color: #444;
            line-height: 1.8;
            margin-bottom: 10px;
            text-align: justify;
        }
        .termos-conteudo ul {
            padding-left: 20px;
            margin-bottom: 10px;
        }
        .termos-conteudo ul li {
            font-size: .93rem;
            color: #444;
            line-height: 1.8;
            margin-bottom: 4px;
        }
        .termos-divider {
            border: none;
            border-top: 1px solid #eee;
            margin: 24px 0;
        }
        .termos-destaque {
            background: #f1f8e9;
            border-left: 4px solid var(--cor-primaria);
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            margin: 16px 0;
            font-size: .9rem;
            color: #2e7d32;
        }
        .btn-aceitar {
            display: block; width: 100%;
            background: var(--cor-primaria); color: #fff;
            border: none; padding: 14px;
            border-radius: 10px; font-size: 1rem;
            font-weight: 700; cursor: pointer;
            margin-top: 28px; transition: background .2s;
            text-align: center;
        }
        .btn-aceitar:hover { background: var(--verde-escuro); }
    </style>
</head>
<body <?= !$is_modal ? 'style="background:var(--cinza-claro);"' : '' ?>>

<?php if ($is_modal): ?>
<!-- Modo modal: overlay com blur -->
<div class="modal-termos-overlay" onclick="fecharSeClicarFora(event)">
    <div class="modal-termos-box" id="modalBox">
        <button class="btn-fechar-modal" onclick="window.close()" title="Fechar">
            <i class="fas fa-times"></i>
        </button>
        <div class="termos-conteudo">
<?php else: ?>
<!-- Modo página normal -->
<?php include 'header.php'; ?>
<div class="termos-wrapper">
    <div class="termos-conteudo">
<?php endif; ?>

        <h1><i class="fas fa-file-contract" style="color:var(--cor-primaria);"></i> Termos de Uso</h1>
        <p class="data-atualizacao">Última atualização: Janeiro de 2026</p>

        <div class="termos-destaque">
            Ao criar uma conta no Doar+, você declara que leu, compreendeu e concorda com todos os termos e condições descritos neste documento.
        </div>

        <h2><i class="fas fa-info-circle"></i> 1. Sobre a Plataforma</h2>
        <p>O Doar+ é uma plataforma digital gratuita que conecta pessoas que desejam realizar doações de itens com pessoas que necessitam desses itens. Não somos intermediários comerciais e não cobramos qualquer taxa pelos serviços prestados.</p>
        <p>Atuamos como facilitadores do processo de doação, não nos responsabilizando pelo estado, qualidade ou entrega dos itens anunciados.</p>

        <hr class="termos-divider">

        <h2><i class="fas fa-user-check"></i> 2. Cadastro e Conta</h2>
        <p>Para utilizar os recursos completos da plataforma, é necessário criar uma conta. Ao se cadastrar, você concorda em:</p>
        <ul>
            <li>Fornecer informações verdadeiras, precisas e completas;</li>
            <li>Manter seus dados cadastrais atualizados;</li>
            <li>Ser responsável pela confidencialidade da sua senha;</li>
            <li>Notificar o Doar+ imediatamente caso suspeite de uso não autorizado de sua conta;</li>
            <li>Ter idade mínima de 18 anos ou autorização de um responsável legal.</li>
        </ul>

        <hr class="termos-divider">

        <h2><i class="fas fa-hand-holding-heart"></i> 3. Regras para Doações</h2>
        <p>Ao anunciar uma doação, o usuário se compromete a:</p>
        <ul>
            <li>Doar apenas itens de sua propriedade ou para os quais tenha autorização;</li>
            <li>Descrever os itens de forma honesta, incluindo o real estado de conservação;</li>
            <li>Disponibilizar fotos reais e atuais do item;</li>
            <li>Cumprir o combinado com o interessado após a aprovação;</li>
            <li>Não anunciar itens proibidos por lei ou que possam causar dano a terceiros.</li>
        </ul>

        <p><strong>Itens proibidos incluem, mas não se limitam a:</strong> armas, drogas, medicamentos vencidos, produtos falsificados, material pornográfico, animais vivos e itens roubados ou de procedência duvidosa.</p>

        <hr class="termos-divider">

        <h2><i class="fas fa-shield-alt"></i> 4. Moderação e Aprovação</h2>
        <p>Todas as doações passam por análise antes de serem publicadas. O Doar+ se reserva o direito de recusar, remover ou suspender anúncios que:</p>
        <ul>
            <li>Violem estes Termos de Uso;</li>
            <li>Contenham conteúdo ofensivo, enganoso ou inadequado;</li>
            <li>Sejam duplicados ou tenham caráter comercial;</li>
            <li>Recebam denúncias recorrentes de outros usuários.</li>
        </ul>

        <hr class="termos-divider">

        <h2><i class="fas fa-ban"></i> 5. Condutas Proibidas</h2>
        <p>É expressamente proibido:</p>
        <ul>
            <li>Usar a plataforma para fins comerciais ou de revenda;</li>
            <li>Criar múltiplas contas para burlar restrições;</li>
            <li>Assediar, ameaçar ou discriminar outros usuários;</li>
            <li>Publicar informações falsas ou enganosas;</li>
            <li>Tentar acessar áreas restritas ou comprometer a segurança da plataforma;</li>
            <li>Usar bots, scrapers ou qualquer automação não autorizada.</li>
        </ul>

        <hr class="termos-divider">

        <h2><i class="fas fa-lock"></i> 6. Privacidade e Dados Pessoais</h2>
        <p>Coletamos apenas os dados necessários para o funcionamento da plataforma (nome, CPF, e-mail, telefone e endereço). Suas informações são utilizadas exclusivamente para:</p>
        <ul>
            <li>Gerenciar sua conta e autenticar seu acesso;</li>
            <li>Facilitar o contato entre doador e interessado;</li>
            <li>Garantir a segurança da plataforma;</li>
            <li>Cumprir obrigações legais.</li>
        </ul>
        <p>Não vendemos, alugamos ou compartilhamos seus dados com terceiros para fins comerciais.</p>

        <hr class="termos-divider">

        <h2><i class="fas fa-exclamation-triangle"></i> 7. Responsabilidades</h2>
        <p>O Doar+ não se responsabiliza por:</p>
        <ul>
            <li>A qualidade, estado ou funcionamento dos itens doados;</li>
            <li>Acordos ou combinações feitos diretamente entre usuários;</li>
            <li>Danos decorrentes do uso indevido da plataforma;</li>
            <li>Indisponibilidade temporária do serviço por manutenção ou falhas técnicas.</li>
        </ul>

        <hr class="termos-divider">

        <h2><i class="fas fa-user-slash"></i> 8. Suspensão e Encerramento</h2>
        <p>O Doar+ poderá suspender ou encerrar contas que violem estes Termos, sem aviso prévio em casos graves. Usuários também podem solicitar a exclusão de sua conta a qualquer momento pelo suporte.</p>

        <hr class="termos-divider">

        <h2><i class="fas fa-edit"></i> 9. Alterações nos Termos</h2>
        <p>Podemos atualizar estes Termos periodicamente. Em caso de alterações significativas, os usuários serão notificados por e-mail ou aviso na plataforma. O uso continuado após as alterações implica aceitação dos novos termos.</p>

        <hr class="termos-divider">

        <h2><i class="fas fa-envelope"></i> 10. Contato</h2>
        <p>Em caso de dúvidas sobre estes Termos de Uso, entre em contato pelo e-mail <strong>contato@doarmais.com.br</strong> ou acesse nossa <a href="contato.php" style="color:var(--cor-primaria);">página de contato</a>.</p>

        <div class="termos-destaque" style="margin-top:24px;">
            <i class="fas fa-heart"></i> Obrigado por fazer parte do Doar+. Juntos, transformamos vidas através da solidariedade.
        </div>

        <?php if ($is_modal): ?>
        <button class="btn-aceitar" onclick="window.close()">
            <i class="fas fa-check-circle"></i> Li e aceito os Termos de Uso
        </button>
        <?php endif; ?>

<?php if ($is_modal): ?>
        </div><!-- termos-conteudo -->
    </div><!-- modal-termos-box -->
</div><!-- modal-termos-overlay -->
<script>
function fecharSeClicarFora(e) {
    if (e.target.classList.contains('modal-termos-overlay')) window.close();
}
</script>
<?php else: ?>
    </div><!-- termos-conteudo -->
</div><!-- termos-wrapper -->
<?php include 'footer.php'; ?>
<?php endif; ?>

</body>
</html>
