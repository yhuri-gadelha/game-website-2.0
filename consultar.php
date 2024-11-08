<?php
// Conectar ao banco de dados
require 'db.php'; // Inclua seu arquivo de conexão com o banco

// Consultar todos os usuários
$sql = "SELECT id, nome, email, perfil FROM usuarios";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consultar Usuários</title>
    <style>
        /* Estilo básico para a tabela de usuários */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 10px;
    text-align: left;
}

th {
    background-color: purple;
}

a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}

body {
    font-family: Arial, sans-serif;
    background-color: pink;
    padding: 20px;
}
/* Estilização do modal */
.modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
</head>
<body>


<div id="confirmModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Tem certeza de que deseja excluir este usuário?</p>
        <button onclick="confirmDelete()">Sim</button>
        <button onclick="closeModal()">Não</button>
    </div>
</div>


    <h1>Consulta de Usuários</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Perfil</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
    <tr id="usuario-<?= $usuario['id'] ?>">
        <td><?= htmlspecialchars($usuario['id']) ?></td>
        <td><?= htmlspecialchars($usuario['nome']) ?></td>
        <td><?= htmlspecialchars($usuario['email']) ?></td>
        <td><?= htmlspecialchars($usuario['perfil']) ?></td>
        <td>
            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>">Editar</a>
            <a href="javascript:void(0);" onclick="showModal(<?= $usuario['id'] ?>)">Excluir</a>
        </td>
    </tr>
<?php endforeach; ?>

        </tbody>
    </table>

    <script>
    let deleteId = '';

    function showModal(id) {
        deleteId = id;
        document.getElementById("confirmModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("confirmModal").style.display = "none";
    }

    function confirmDelete() {
        fetch(`consultar.php?delete_id=${deleteId}`)
            .then(response => {
                if (response.ok) {
                    closeModal();
                    // Remove a linha da tabela correspondente ao usuário excluído
                    const row = document.getElementById(`usuario-${deleteId}`);
                    if (row) {
                        row.remove();
                    }
                } else {
                    alert("Erro ao excluir o usuário.");
                }
            })
            .catch(error => {
                console.error("Erro:", error);
                alert("Erro ao excluir o usuário.");
            });
    }
</script>

<?php

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

</body>
</html>
