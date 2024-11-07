<?php
// Conexão com o banco de dados
require 'db.php';

// Inicia a sessão para verificar se o usuário é master
session_start();

// Verifica se o usuário tem permissão de master
if (!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 'master') {
    // Redireciona se não for master
    header("Location: index.php");
    exit();
}

// Caso o formulário de pesquisa seja enviado
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search_term'];
    // Prepara a consulta para pesquisar o produto pelo nome
    $sql = "SELECT * FROM produtos WHERE nome LIKE :search_term";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':search_term', "%$searchTerm%");
    $stmt->execute();
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $produtos = [];
}

// Caso o formulário de edição seja enviado
if (isset($_POST['edit_product'])) {
    // Recebe os dados do formulário de edição
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $foto = $_POST['foto'];

    // Atualiza o produto no banco de dados
    $sql = "UPDATE produtos SET nome = :nome, descricao = :descricao, preco = :preco, categoria = :categoria, foto = :foto WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':nome', $nome);
    $stmt->bindValue(':descricao', $descricao);
    $stmt->bindValue(':preco', $preco);
    $stmt->bindValue(':categoria', $categoria);
    $stmt->bindValue(':foto', $foto);
    $stmt->execute();

    // Redireciona após a edição
    header("Location: editar-produto.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="home/styles.css">
</head>
<body>

<header>
    <!-- Aqui você pode colocar a estrutura da navbar se necessário -->
</header>

<!-- Formulário de Pesquisa -->
<form method="POST" action="editar-produto.php">
    <input type="text" name="search_term" placeholder="Pesquisar produto..." required>
    <button type="submit" name="search">Buscar</button>
</form>

<!-- Exibe os produtos encontrados -->
<?php if (!empty($produtos)): ?>
    <h2>Resultados da Pesquisa</h2>
    <ul>
        <?php foreach ($produtos as $produto): ?>
            <li>
                <p><?= $produto['nome']; ?> - <?= $produto['preco']; ?></p>
                <a href="editar-produto.php?id=<?= $produto['id']; ?>">Editar</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Formulário de Edição -->
<?php
if (isset($_GET['id'])) {
    // Recupera o produto selecionado para edição
    $id = $_GET['id'];
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($produto):
?>
    <h2>Editar Produto: <?= $produto['nome']; ?></h2>
    <form method="POST" action="editar-produto.php">
        <input type="hidden" name="id" value="<?= $produto['id']; ?>">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?= $produto['nome']; ?>" required><br>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" required><?= $produto['descricao']; ?></textarea><br>

        <label for="preco">Preço:</label>
        <input type="text" name="preco" value="<?= $produto['preco']; ?>" required><br>

        <label for="categoria">Categoria:</label>
        <input type="text" name="categoria" value="<?= $produto['categoria']; ?>" required><br>

        <label for="foto">Foto (URL):</label>
        <input type="text" name="foto" value="<?= $produto['foto']; ?>" required><br>

        <button type="submit" name="edit_product">Salvar Alterações</button>
    </form>
<?php
    endif;
}
?>

</body>
</html>
