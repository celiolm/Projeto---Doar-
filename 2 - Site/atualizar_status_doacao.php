<?php
session_start();

// Verifica se está logado e se a requisição é do tipo POST
if (!isset($_SESSION['id_usuario']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: painel_usuario.php');
    exit;
}

require_once 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];
$id_doacao = $_POST['id_doacao'] ?? null;
$novo_status = $_POST['novo_status'] ?? null;

// Valida se os dados chegaram e se o status é válido
if ($id_doacao && in_array($novo_status, ['Entregue', 'Cancelado'])) {
    
    // Regra de Ouro de Segurança: O "AND id_usuario = ?" garante que o usuário 
    // só consiga alterar o status das doações que pertencem a ele mesmo.
    $stmt = $pdo->prepare("UPDATE doacao SET situacao = ? WHERE id_doacao = ? AND id_usuario = ?");
    $stmt->execute([$novo_status, $id_doacao, $id_usuario]);
    
}

// Redireciona de volta para o painel
header('Location: painel_usuario.php');
exit;
?>