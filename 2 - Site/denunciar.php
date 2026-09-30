<?php
session_start();
require_once 'conexao.php';

$id_doacao  = (int)($_POST['id_doacao'] ?? 0);
$motivo     = trim($_POST['motivo']     ?? '');
$descricao  = trim($_POST['descricao']  ?? '');
$id_usuario = $_SESSION['id_usuario']  ?? null;

// Validação básica
if (!$id_doacao || !$motivo) {
    header('Location: verdoacoes.php?erro=denuncia');
    exit;
}

// Verifica se a doação existe e está aprovada
$stmt = $pdo->prepare("SELECT id_doacao FROM doacao WHERE id_doacao = ? AND situacao = 'Aprovado'");
$stmt->execute([$id_doacao]);
if (!$stmt->fetch()) {
    header('Location: verdoacoes.php?erro=denuncia');
    exit;
}

// Evita denúncia duplicada do mesmo usuário logado
if ($id_usuario) {
    $dup = $pdo->prepare("SELECT id_denuncia FROM denuncia WHERE id_doacao = ? AND id_usuario = ?");
    $dup->execute([$id_doacao, $id_usuario]);
    if ($dup->fetch()) {
        header('Location: verdoacoes.php?aviso=ja_denunciado');
        exit;
    }
}

// Insere denúncia
$stmt = $pdo->prepare("
    INSERT INTO denuncia (id_doacao, id_usuario, motivo, descricao)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$id_doacao, $id_usuario, $motivo, $descricao ?: null]);

header('Location: verdoacoes.php?sucesso=denuncia');
exit;
