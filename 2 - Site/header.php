<?php
// header.php — incluir no topo de todas as páginas
// Requer que session_start() já tenha sido chamado antes do include

$logado   = isset($_SESSION['id_usuario']);
$nome_ses = $logado ? explode(' ', $_SESSION['nome'])[0] : '';
$is_admin = $logado && ($_SESSION['tipo_usuario'] === 'superadmin');

$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <div class="container-header">

        <!-- Logo -->
        <div class="logo-area">
            <a href="index.php">
                <img src="imagens/logoDoar.png" alt="Logo Doar+" class="minha-logo">
            </a>
        </div>

        <!-- Menu desktop -->
        <nav class="menu" id="menuDesktop">
            <ul>
                <li><a href="index.php"     <?= $pagina_atual === 'index.php'      ? 'class="active"' : '' ?>>Início</a></li>
                <li><a href="verdoacoes.php" <?= $pagina_atual === 'verdoacoes.php' ? 'class="active"' : '' ?>>Ver Doações</a></li>
                <li><a href="doar.php"       <?= $pagina_atual === 'doar.php'       ? 'class="active"' : '' ?>>Quero Doar</a></li>
                <li><a href="contato.php"    <?= $pagina_atual === 'contato.php'    ? 'class="active"' : '' ?>>Contato</a></li>
            </ul>
        </nav>

        <!-- Ações: login/dropdown + hamburguer -->
        <div class="acoes-header">
            <?php if ($logado): ?>
                <div class="saudacao-header">
                    <i class="fas fa-hand-wave" style="color:var(--cor-primaria);"></i>
                    <div class="dropdown" id="dropdownHeader">
                        <span onclick="toggleDropdown()">
                            Olá, <strong><?= htmlspecialchars($nome_ses) ?>!</strong>
                            <i class="fas fa-chevron-down" style="font-size:.75rem; color:var(--cor-primaria);"></i>
                        </span>
                        <div class="dropdown-menu" id="dropdownMenu">
                            <?php if ($is_admin): ?>
                                <a href="painel_admin.php"><i class="fas fa-shield-alt"></i> Painel Admin</a>
                                <a href="doar.php"><i class="fas fa-hand-holding-heart"></i> Fazer Doação</a>
                                <a href="conta_admin.php"><i class="fas fa-user-cog"></i> Meu Perfil</a>
                            <?php else: ?>
                                <a href="painel_usuario.php"><i class="fas fa-tachometer-alt"></i> Meu Painel</a>
                                <a href="doar.php"><i class="fas fa-hand-holding-heart"></i> Fazer Doação</a>
                                <a href="minha_conta.php"><i class="fas fa-user-cog"></i> Meu Perfil</a>
                            <?php endif; ?>
                            <div class="divisor"></div>
                            <a href="logout.php" class="sair"><i class="fas fa-sign-out-alt"></i> Sair</a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="btn-login-moderno">Entrar</a>
            <?php endif; ?>

            <!-- Botão hamburguer (só mobile) -->
            <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- Menu mobile -->
    <nav class="menu-mobile" id="menuMobile">
        <ul>
            <li><a href="index.php"     <?= $pagina_atual === 'index.php'      ? 'class="active"' : '' ?>>Início</a></li>
            <li><a href="verdoacoes.php" <?= $pagina_atual === 'verdoacoes.php' ? 'class="active"' : '' ?>>Ver Doações</a></li>
            <li><a href="doar.php"       <?= $pagina_atual === 'doar.php'       ? 'class="active"' : '' ?>>Quero Doar</a></li>
            <li><a href="contato.php"    <?= $pagina_atual === 'contato.php'    ? 'class="active"' : '' ?>>Contato</a></li>
            <?php if ($logado): ?>
                <li class="menu-mobile-divider"></li>
                <?php if ($is_admin): ?>
                    <li><a href="painel_admin.php"><i class="fas fa-shield-alt"></i> Painel Admin</a></li>
                    <li><a href="conta_admin.php"><i class="fas fa-user-cog"></i> Meu Perfil</a></li>
                <?php else: ?>
                    <li><a href="painel_usuario.php"><i class="fas fa-tachometer-alt"></i> Meu Painel</a></li>
                    <li><a href="minha_conta.php"><i class="fas fa-user-cog"></i> Meu Perfil</a></li>
                <?php endif; ?>
                <li><a href="logout.php" style="color:#f44336;"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
            <?php else: ?>
                <li class="menu-mobile-divider"></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Entrar</a></li>
                <li><a href="cadastro.php"><i class="fas fa-user-plus"></i> Criar Conta</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script>
function toggleDropdown() {
    const menu = document.getElementById('dropdownMenu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdownHeader');
    if (dropdown && !dropdown.contains(e.target)) {
        const menu = document.getElementById('dropdownMenu');
        if (menu) menu.style.display = 'none';
    }
});

function toggleMenu() {
    const menu    = document.getElementById('menuMobile');
    const btn     = document.getElementById('hamburger');
    const aberto  = menu.classList.toggle('aberto');
    btn.classList.toggle('ativo', aberto);
}

// Fecha menu mobile ao clicar em link
document.querySelectorAll('.menu-mobile a').forEach(link => {
    link.addEventListener('click', () => {
        document.getElementById('menuMobile').classList.remove('aberto');
        document.getElementById('hamburger').classList.remove('ativo');
    });
});
</script>
