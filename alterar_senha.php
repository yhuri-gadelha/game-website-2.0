<?php
// Inicia a sessão
session_start();
require 'db.php'; // Arquivo de conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $email = $_POST['email'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Valida se as senhas coincidem
    if ($nova_senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        // Verifica se o e-mail existe no banco de dados
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // Atualiza a senha no banco de dados
            $sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'senha' => password_hash($nova_senha, PASSWORD_DEFAULT),
                'email' => $email
            ]);

            // Após a alteração, armazena uma mensagem de sucesso para ser exibida na página
            $_SESSION['senha_alterada'] = true;
            header("Location: alterar_senha.php"); // Redireciona para a mesma página para mostrar a modal
            exit();
        } else {
            $erro = "E-mail não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <style>
        /* Estilos para a página */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }

        /* Estilos da Modal */
        .modal {
            display: none; /* Oculta a modal por padrão */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            border-radius: 10px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #ffc0cb;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #ffc0cb;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['senha_alterada']) && $_SESSION['senha_alterada']) : ?>
    <script>
        // Exibe a modal de confirmação
        window.onload = function() {
            document.getElementById('myModal').style.display = 'block';
        }
    </script>
    <?php unset($_SESSION['senha_alterada']); ?>
<?php endif; ?>

<div class="container">
    <h2>Alterar Senha</h2>

    <?php if (isset($erro)) : ?>
        <div style="color: red;"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form action="alterar_senha.php" method="POST">
        <input type="text" name="email" placeholder="Digite seu e-mail" required><br><br>
        <input type="password" name="nova_senha" placeholder="Nova senha" required><br><br>
        <input type="password" name="confirmar_senha" placeholder="Confirmar nova senha" required><br><br>
        <button type="submit" class="btn">Alterar Senha</button>
    </form>
</div>

<!-- Modal de confirmação -->
<div id="myModal" class="modal">
  <div class="modal-content">
    <h2>Senha alterada com sucesso!</h2>
    <p>Deseja voltar para o login e testar sua nova senha?</p>
    <button class="btn" onclick="window.location.href='login.php'">Sim, voltar ao login</button>
    <button class="btn" onclick="document.getElementById('myModal').style.display='none'">Cancelar</button>
  </div>
</div>

<script>
    // Quando o usuário clicar fora da modal, ela se fecha
    window.onclick = function(event) {
        if (event.target == document.getElementById('myModal')) {
            document.getElementById('myModal').style.display = "none";
        }
    }
</script>

</body>
</html>
