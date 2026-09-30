<?php
session_start();
require_once 'conexao.php';
header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);
if (!$id) { echo json_encode(['erro' => 'ID inválido.']); exit; }

$stmt = $pdo->prepare("
    SELECT d.id_doacao, d.titulo, d.categoria, d.descricao,
           d.logradouro, d.numero, d.complemento, d.bairro, d.localidade, d.uf,
           u.nome AS doador, u.tel AS doador_tel, u.email AS doador_email
    FROM doacao d
    JOIN usuario u ON u.id_usuario = d.id_usuario
    WHERE d.id_doacao = ?
");
$stmt->execute([$id]);
$doacao = $stmt->fetch();

if (!$doacao) { echo json_encode(['erro' => 'Doação não encontrada.']); exit; }

// Fotos
$fotos = $pdo->prepare("SELECT caminho FROM doacao_fotos WHERE id_doacao = ? ORDER BY ordem");
$fotos->execute([$id]);
$doacao['fotos'] = array_column($fotos->fetchAll(), 'caminho');

// Indica se o usuário está logado (para mostrar botão de contato)
$logado = isset($_SESSION['id_usuario']);
$doacao['logado'] = $logado;

// Esconde o número da casa se não estiver logado
if (!$logado) {
    $doacao['numero'] = '****';
}

echo json_encode($doacao);
