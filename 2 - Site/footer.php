<?php
// footer.php — incluir no final de todas as páginas
// Requer que $logado e $nome_ses já estejam definidos
$logado   = isset($_SESSION['id_usuario']);
$is_admin = $logado && ($_SESSION['tipo_usuario'] === 'superadmin');
?>
<footer class="footer">
    <div class="container-footer">

        <!-- Marca -->
        <div class="footer-brand">
            <img src="imagens/logoDoar.png" alt="Logo Doar+" class="minha-logo">
            <p>Nossa missão é facilitar o ciclo de solidariedade através da tecnologia, tornando a doação de itens algo simples, seguro e gratuito.</p>
            <div class="footer-social">
                <a title="Facebook"><i class="fab fa-facebook-f"></i></a>
                <a title="Instagram"><i class="fab fa-instagram"></i></a>
                <a title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>

        <!-- Navegação -->
        <div class="footer-col">
            <h4>Navegação</h4>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="verdoacoes.php">Ver Doações</a></li>
                <li><a href="doar.php">Quero Doar</a></li>
                <li><a href="contato.php">Contato</a></li>
            </ul>
        </div>

        <!-- Minha Conta -->
        <div class="footer-col">
    <h4>Minha Conta</h4>
    <ul>
        <?php if ($logado): ?>
            <?php if ($is_admin): ?>
    <li><a href="painel_admin.php">Painel Admin</a></li>
    <li><a href="doar.php">Nova Doação</a></li>
    <li><a href="conta_admin.php">Meu Perfil</a></li>
            <?php else: ?>
                <li><a href="painel_usuario.php">Meu Painel</a></li>
                <li><a href="doar.php">Nova Doação</a></li>
                <li><a href="minha_conta.php">Meu Perfil</a></li>
            <?php endif; ?>
            <li><a href="logout.php">Sair</a></li>
        <?php else: ?>
            <li><a href="login.php">Entrar</a></li>
            <li><a href="cadastro.php">Criar Conta</a></li>
        <?php endif; ?>
    </ul>
</div>

        <!-- Contato -->
        <div class="footer-col">
            <h4>Contato</h4>
            <div class="contato-item"><i class="fas fa-envelope"></i> contato@doarmais.com.br</div>
            <div class="contato-item"><i class="fas fa-phone"></i> (31) 99999-9999</div>
            <div class="contato-item"><i class="fas fa-map-marker-alt"></i> Contagem - MG</div>
            <div class="contato-item"><i class="fas fa-clock"></i> Seg–Sex: 9h às 18h</div>
        </div>

    </div>

    <div class="footer-bottom">
        <div class="footer-bottom-inner">
            <p>&copy; 2026 Doar+ — Transforme Vidas através da doação</p>
            <p class="feito-com">Feito com <i class="fas fa-heart"></i> para quem precisa</p>
        </div>
    </div>
</footer>
