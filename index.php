<?php

require 'db.php';

$sql = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Inicia a sessão
session_start(); 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>GameX</title>
    <link rel="stylesheet" href="home/styles.css">
    <link rel="shortcut icon" href="assets/gamex-favicon.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"> 
    <!-- Serve para linkar o css do bootstrap mais facilmente. -->
</head>
<body style="background-color: pink;">

<?php

// Verifica se o usuário está logado
if (isset($_SESSION['perfil'])) {
    // Verifica se o perfil é master
    if ($_SESSION['perfil'] == 'master') {
        // Exibe o botão de modo de edição
        echo '<a href="editar_produto.php"><button class="btn editar">Modo de Edição</button></a>';
    } else {
        // Caso o usuário não seja master, exibe a mensagem
        echo '<p>Você não tem permissão para editar.</p>';
    }
} else {
    // Se não estiver logado, não exibe nada
    echo '';
}
?>


    <!-- Navbar -->

    <header>
        <nav>
            <div class="logo">
                <img src="assets/gamex-logo-original2.png" alt="">
            </div>
            <div class="nav-container">
                <ul id="nav-links" class="nav-links">
                    <li>
                        <a href="error/error.html">Novidades</a>
                    </li>
                    <li>
                        <a href="#ofertas">Ofertas</a>
                    </li>
                    <li>
                        <a href="error/error.html">Assinaturas</a>
                    </li>
                    <li>
                        <a href="#contato">Contato</a>
                    </li>
                    <li>
                        <a href="MER/mer.html">MER</a>
                    </li>
                    <li>
                        <a href="log.php">LOG</a>
                    </li>
                </ul>
                <div class="nav-actions">
     
                    <input type="text" placeholder="Pesquisar jogos..." class="search-bar">

                <!-- Ícone do carrinho -->
                <div class="cart-icon">
                    <a href="carrinho.php">
                        <img src="assets/icons/cart.png" alt="Carrinho">
                    <span id="cart-count">0</span> <!-- Quantidade de itens -->
                    </a>
                </div>                       

                <?php if (isset($_SESSION['nome'])): ?> 
        
                    <!-- Mostra o nome do usuário e botão de logout -->
                    <p>Olá, <?= $_SESSION['nome']; ?>!</p>
                    <a href="logout.php">Sair</a>
    
                <?php else: ?>
                    

                <!-- Mostra o botão de login e cadastro -->
                <a href="login.php">
                    <button class="btn login">Login</button>
                </a>
                <a href="cadastro.php">
                    <button class="btn cadastro">Cadastre-se</button>
                </a>
                <?php endif; ?>             
  
                </div>
                <div class="hamburguer" id="hamburguer">
                    ☰
                </div>
            </div>
        </nav>
    </header>

       <!-- Carrossel -->

       <div class="carousel slide" id="carouselDemo" data-bs-wrap="true" data-bs-ride="carousel" data-bs-interval="2000">

            <div class="carousel-inner">

                <div class="carousel-item active">
                    <img src="assets/banner/stone.png" class="w-100" alt="">
                </div>

                <div class="carousel-item">
                    <img src="assets/banner/stardew.png" class="w-100" alt="">
                </div>
                
                <div class="carousel-item">
                    <img src="assets/banner/gta.png" class="w-100" alt="">
            </div>

</div>

        <button class="carousel-control-prev" 
        type="button"
        data-bs-target="#carouselDemo" data-bs-slide="prev">
            <span class="carosel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button"
        data-bs-target="#carouselDemo" data-bs-slide="next">
            <span class="carosel-control-next-icon"></span>
        </button>


</div>

<!-- Filtro -->

<section class="filtro-jogos">
<label for="categoria-select">Filtrar por:</label>
<select id="categoria-select">
    <option value="todos">Todos</option>
    <option value="acao">Ação</option>
    <option value="aventura">Aventura</option>
    <option value="luta">Luta</option>
    <option value="rpg">RPG</option>
    <option value="terror">Terror</option>
</select>
</section>

<!-- Catálogo de Jogos -->

<section class="catalogo" id="ofertas">
    <div id="catalogo-jogos" class="jogos-container">
        <div class="item" data-categoria="rpg">
            <img src="assets/produtos/ELDEN-RING.avif" alt="Jogo 1">
            <h3>Elden Ring</h3>
            <p>R$299,90</p>
            <button class="btn comprar" data-id="1" data-name="Elden Ring" data-price="299.90" onclick="">Comprar</button>
        </div>
        <div class="item" data-categoria="aventura">
            <img src="assets/produtos/Marvel's Spider-Man 2.avif" alt="Jogo 2">
            <h3>Marvel’s Spider-Man 2</h3>
            <p>R$349,90</p>
            <button class="btn comprar" data-id="2" data-name="Marvel’s Spider-Man 2" data-price="349.90" onclick="">Comprar</button>
        </div>

        <div class="item" data-categoria="rpg">
            <img src="assets/produtos/Black Myth-Wukong.avif" alt="Jogo 3">
            <h3>Black Myth: Wukong</h3>
            <p>R$299,90</p>
            <button class="btn comprar" data-id="3" data-name="Black Myth: Wukong" data-price="299.90" onclick="">Comprar</button>
        </div>

        <div class="item" data-categoria="terror">
            <img src="assets/produtos/Resident Evil Village.jpeg" alt="Jogo 4">
            <h3>Resident Evil Village </h3>
            <p>R$184,50</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="luta">
            <img src="assets/produtos/DRAGON BALL.avif" alt="Jogo 5">
            <h3>Dragon Ball</h3>
            <p>R$349,90</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="terror">
            <img src="assets/produtos/Dead by Daylight.webp" alt="Jogo 6">
            <h3>Dead by Daylight</h3>
            <p>R$149,50</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="aventura">
            <img src="assets/produtos/God of War Ragnarök.jpeg" alt="Jogo 7">
            <h3>God of War Ragnarök</h3>
            <p>R$349,90</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="acao">
            <img src="assets/produtos/Cyberpunk 2077.webp" alt="Jogo 8">
            <h3>Cyberpunk 2077</h3>
            <p>R$249,50</p>
            <button class="btn comprar">Comprar</button>
        </div>
        
        <div class="item" data-categoria="rpg">
            <img src="assets/produtos/Hogwarts Legacy.webp" alt="Jogo 9">
            <h3>Hogwarts Legacy</h3>
            <p>R$249,90</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="luta">
            <img src="assets/produtos/Mortal Kombat1.avif" alt="Jogo 10">
            <h3>Mortal Kombat 1</h3>
            <p>R$249,99</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="luta">
            <img src="assets/produtos/NARUTO X BORUTO Ultimate Ninja STORM CONNECTIONS.avif" alt="Jogo 11">
            <h3>Naruto X Boruto</h3>
            <p>R$149,95</p>
            <button class="btn comprar">Comprar</button>
        </div>

        <div class="item" data-categoria="aventura">
            <img src="assets/produtos/The Last of Us™ Part II.avif" alt="Jogo 12">
            <h3>The Last of Us Part II</h3>
            <p>R$199,50</p>
            <button class="btn comprar">Comprar</button>
        </div>
    </div>
        <!-- Adicione os outros jogos aqui -->
    </section>

    <section class="avaliacoes">
        <h2>Avaliações da Loja</h2>
    
        <!-- Área para exibir comentários em colunas -->
        <div id="comments" class="comments-flex">
            <div class="comment">
                <img src="assets/icons/s2.jpeg" alt="Choi Jungeun" class="comment-avatar">
                <div class="comment-content">
                    <p class="comment-text">"Ótima loja, recomendo!"</p>
                    <p class="comment-author">- Choi Jungeun</p>
                </div>
            </div>
            <div class="comment">
                <img src="assets/icons/saebi.jpeg" alt="Jeong Saebi" class="comment-avatar">
                <div class="comment-content">
                    <p class="comment-text">"Excelente variedade de jogos e os preços são ótimos!"</p>
                    <p class="comment-author">- Jeong Saebi</p>
                </div>
            </div>
            <div class="comment">
                <img src="assets/icons/natty.jpeg" alt="Natty" class="comment-avatar">
                <div class="comment-content">
                    <p class="comment-text">"Site bem fácil de dialogar e a compra é simples de se fazer."</p>
                    <p class="comment-author">- Natty</p>
                </div>
            </div>
            <div class="comment">
                <img src="assets/icons/ningning.jpeg" alt="NingNing" class="comment-avatar">
                <div class="comment-content">
                    <p class="comment-text">"Super fácil de comprar, trâmite excelente."</p>
                    <p class="comment-author">- Ning Ning</p>
                </div>
            </div>


        </div>
 
    </section>
    
    

    <!-- Footer -->

    <footer>

    <section id ="contato"></section>

        <div class="footerLeft">
            <div class="footerMenu">
                <h1 class="fMenuTitle">Sobre Nós</h1>
                <ul class="fList">
                    <li class="fListItem">Empresa</li>
                    <li class="fListItem">Contato</li>
                    <li class="fListItem">Lojas</li>
                </ul>
            </div>

            <div class="footerMenu">
                <h1 class="fMenuTitle">Links Úteis</h1>
                <ul class="fList">
                    <li class="fListItem">Suporte</li>
                    <li class="fListItem">Reembolso</li>
                    <li class="fListItem">Feedback</li>
                </ul>
            </div>

            <div class="footerMenu">
                <h1 class="fMenuTitle">Categorias</h1>
                <ul class="fList">
                    <li class="fListItem">Ação</li>
                    <li class="fListItem">Aventura</li>
                    <li class="fListItem">Luta</li>
                    <li class="fListItem">RPG</li>
                    <li class="fListItem">Terror</li>
          
                </ul>
            </div>
        </div>
        <div class="footerRight">

            <div class="footerRightMenu">
                <h1 class="fMenuTitle">Siga-nos</h1>
                <div class="fIcons">
                    <a href="https://www.linkedin.com/in/roberta-fernandes-a067b9167/">
                        <img src="assets/icons/linkedin.png" alt="" class="fIcon">
                    </a>
                    <a href="https://github.com/betanandes">
                        <img src="assets/icons/github.png" alt="" class="fIcon">
                    </a>
                    <a href="https://www.instagram.com/robertanands/">
                        <img src="assets/icons/instagram.png" alt="" class="fIcon">
                    </a>
                </div>
            </div>
            <div class="footerRightMenu">
                <p>&copy; 2024 GameX. Todos os direitos reservados aos alunos UNISUAM.</p>
            </div>
        </div>
    
    </footer>

<button id="dark-mode-toggle">Dark Mode</button>

<script src="home/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Serve para linkar o JS do bootstrap mais facilmente. -->
</body>
</html>
