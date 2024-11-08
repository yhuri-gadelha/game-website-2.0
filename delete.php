<?php
require 'db.php'; // Conexão com o banco de dados
session_start(); // Inicia a sessão

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $delete_id]);
    exit();  // Encerra o script sem redirecionamento
}

$sql = "SELECT * FROM usuarios ORDER BY nome ASC";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

