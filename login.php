
<?php
require 'db.php'; // Arquivo que faz a conexão com o banco de dados
session_start(); // Inicia a sessão

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica o usuário no banco de dados
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    function registrarLog($pdo, $user_id, $nome_usuario, $perfil_usuario, $tentativas_2fa, $status) {
        $sql_log = "INSERT INTO log_acesso (user_id, nome_usuario, perfil_usuario, tentativas_2fa, status) 
                    VALUES (:user_id, :nome_usuario, :perfil_usuario, :tentativas_2fa, :status)";
        $stmt_log = $pdo->prepare($sql_log);
        $stmt_log->execute([
            'user_id' => $user_id,
            'nome_usuario' => $nome_usuario,
            'perfil_usuario' => $perfil_usuario,
            'tentativas_2fa' => $tentativas_2fa,
            'status' => $status
        ]);
    }
    
    // Verifica se o usuário existe e se a senha está correta
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Armazena o ID do usuário na sessão
        $_SESSION['user_id'] = $usuario['id'];  // Certifique-se de que a coluna é 'id'
        $_SESSION['email_2fa'] = $email;
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['perfil'] = $usuario['perfil'];  // Armazena o perfil na sessão
    
        // Contabiliza tentativas do 2FA (exemplo de como controlar)
        $tentativas_2fa = 0; // Inicia o contador de tentativas 2FA
    
        // Armazena as perguntas de segurança, se necessário
        $_SESSION['perguntas'] = [
            ['pergunta' => $usuario['pergunta1'], 'resposta' => $usuario['resposta1']],
            ['pergunta' => $usuario['pergunta2'], 'resposta' => $usuario['resposta2']],
            ['pergunta' => $usuario['pergunta3'], 'resposta' => $usuario['resposta3']],
        ];
    
        // Escolhe uma pergunta aleatória
        $pergunta_escolhida = $_SESSION['perguntas'][array_rand($_SESSION['perguntas'])];
        $_SESSION['pergunta_selecionada'] = $pergunta_escolhida;
    
        // Registra o log de sucesso no login
        registrarLog($pdo, $_SESSION['user_id'], $_SESSION['nome'], $_SESSION['perfil'], $tentativas_2fa, 'sucesso');
    
        // Agora você pode verificar o perfil do usuário e redirecionar de acordo
        if ($_SESSION['perfil'] === 'master') {
            // Se for perfil "master", o redirecionamento pode ser diferente, como para um painel de administração, por exemplo
            header("Location: editar_produto.php");  // Ajuste o redirecionamento conforme sua necessidade
        } else {
            // Se for perfil "comum", redireciona para a página padrão do usuário
            header("Location: dashboard_comum.php");  // Ajuste o redirecionamento conforme sua necessidade
        }
    
        // Redireciona para a página de autenticação 2FA
        header("Location: 2fa.php");
        exit();
    } else {
        // Caso a validação falhe, registra um log de falha
        $erro = "Email ou senha incorretos!";
        registrarLog($pdo, $usuario['id'], $usuario['nome'], $usuario['perfil'], 0, 'falha');
    }
    
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Entrar</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="login/css/style.css">
    <link rel="shortcut icon" href="assets/gamex-favicon.png" type="image/x-icon">
</head>
<body>

    <div class="form">
    
    <!-- Exibe a mensagem de sucesso ao se cadastrar -->
    <?php if (isset($_SESSION['mensagem'])): ?>
        <p style="color: green;"><?= $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></p>
    <?php endif; ?>

    <!-- unset($_SESSION[' ']) é para evitar que seja exibida novamente ao recarregar a página -->

    <!-- Exibe mensagem de erro caso ocorra -->
    <?php if (isset($erro)): ?>
        <p style="color: red;"><?= $erro; ?></p>
    <?php endif; ?>

        <form method="POST" action="login.php" id="form">
            <div class="header">
                <div class="switch">
                    
                    <h1>Login</h1>
                    
                    <img id="theme-toggle" src="login/midia/light-mode-icon.gif" alt="Switch to dark mode">
                
                </div>
            
            </div>
        

            <div class="input-box">

                <input type="email" placeholder="E-mail" name="email" required>
                <div id="login-error" class="error"></div>

            </div>
            
            <div class="input-box">

                <input type="password" placeholder="Senha" name="senha" required>
                <div id="senha-error" class="error"></div>
            

            </div>
            

            <div class="create">

                <p><a href="cadastro.php">Cadastrar-se</a></p>


            </div>


            <div class="create">
                

                <p><a href="alterar_senha.php">Esqueci minha senha</a></p>


            </div>

            <div class="entrar">

                <button><a href="#">INSERT COIN</a></button>

            </div>

        
        </form>


    </div>

    <script src="login/js/script.js"></script>

</body>
</html>
