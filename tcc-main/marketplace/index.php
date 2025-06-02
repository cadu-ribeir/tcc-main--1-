<?php 
    include_once"conexao.php";

    session_start();

    // echo $_SESSION['nome'] . " " . " " . $_SESSION['id']. " " . $_SESSION['admin'] ."<a href='login/deslogar.php'>Deslogar</a>"." ".$_SESSION['cep']." ".$_SESSION['url'];

    function produtos($conexao, $categoria){
        $sql = $conexao->prepare("SELECT id, categoria, condicao, frete, foto_1, produto_nome, valor_promocao, preco FROM produtos WHERE categoria = ?");
        $sql -> bind_param('s', $categoria);
        $sql->execute();
        $resultado = $sql->get_result();

        while($dados = $resultado->fetch_assoc()){
            echo "
                <div class='carousel-element' onclick='window.location.href=\"./venda?id_produto=".htmlspecialchars($dados["id"])."&categoria=".htmlspecialchars($dados['categoria'])."\"'>
                    <img src='".htmlspecialchars($dados['foto_1'])."' alt=''>
                    <div class='produtoInfos'>
                        <p>".htmlspecialchars(ucfirst($dados['produto_nome']))."</p>
                        
            ";
                        verificarPromo($dados);
            echo "      <p>".htmlspecialchars(ucfirst($dados['condicao']))."</p>";

                        verificarFrete($dados);
            echo "
                    </div>
                </div>
            ";
        }
    } 

    function verificarPromo($dados){  
        if($dados['valor_promocao'] > 0){
            echo '<p class="valorOriginal">R$'.htmlspecialchars(number_format($dados['preco'], 2, ',','.')).'</p>';
            echo '<strong>R$'.htmlspecialchars(number_format($dados['valor_promocao'], 2, ',','.')).'</strong>';
        }else{
            echo '<strong>R$'.htmlspecialchars(number_format($dados['preco'], 2, ',','.')).'</strong>';
        }
    }

    function verificarFrete($dados){
        if($dados['frete'] > 0){
            echo "<p><span class=\"green\">Frete de </span>R$".htmlspecialchars(number_format($dados['frete'], 2, ",", "."))."</p>";
        }else{
            echo "<p><span class=\"green\">Frete Grátis</p>";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
    <title>Marketplace</title>
</head>
<body>
    <header>
        <span>DNV</span>
        
        <?php 
            if(!isset($_SESSION['cep'])){
                echo '
                <button id="cepBtn"'.(isset($_SESSION['id']) ? 'onclick="window.location.href=\'./cep\'"' : 'onclick="window.location.    href=\'./login\'"').'>
                    <p>Entregar em</p>
                    <strong><i class="bi bi-geo-alt-fill"></i></i>CEP</strong>
                </button>';
            }else{
                echo '
                <button id="cepBtn">
                    <p>Entregar em</p>
                    <strong><i class="bi bi-geo-alt-fill"></i>'.$_SESSION['cep'].'</strong>
                </button>';
            }
        ?>

        <div id="searchBox">
            <input type="text" placeholder="Pesquise seu item" id="searchInput">
            <i class="bi bi-search" id="searchIcon"></i>
            <i class="bi bi-x-lg" id="clearSearch"></i>
        </div>
        
        <div id="userBox">
            <?php 
                if(!isset($_SESSION['id'])){
                    echo '
                        <button id="userLogin" onclick="window.location.href=\'./login\'">
                            <i class="bi bi-person-circle"></i>
                            <p>Entrar</p>
                        </button>      
                    ';
                }else{
                    echo '
                        <button class="logado" id="loginBtn" onclick="">
                            <img src="'.htmlspecialchars($_SESSION['foto']).'"/>
                        </button>      
                    ';
                }
            ?>

            <i class="bi bi-bag-heart-fill" id="carrinhoIcon" <?php echo isset($_SESSION['id']) ? '' :  'onclick="window.location.href=\'./login\'"' ?>></i>
        </div>
    </header>

    <div id="cartBox" class="hidden">
        <button class="closeBtn">
            <i class="bi bi-x-lg"></i>
        </button>
        <ul id="cartItensBox">
            <?php 
                if(isset($_SESSION['id'])){
                    echo '<input type="hidden" id="idUsuario" value="'.$_SESSION['id'].'">';
                    $sqlCart = 
                            "SELECT carrinhos.quantidade, carrinhos.id_produto, carrinhos.id_usuario, produtos.id, produtos.   foto_1, produtos.categoria, produtos.produto_nome, produtos.valor_promocao, produtos.preco 
                            FROM carrinhos 
                            INNER JOIN produtos ON produtos.id = carrinhos.id_produto
                            WHERE id_usuario = ".$_SESSION['id'];

                    $resultadoCart = $conexao->query($sqlCart);

                    $total = 0;

                    while($dadosCart = $resultadoCart->fetch_assoc()){
                        echo '
                                <li class="cartIten">
                                    <input type="hidden" class="idProduto" value="'.$dadosCart['id'].'">

                                    <img src="'.htmlspecialchars($dadosCart['foto_1']).'" alt="" onclick="window.location.href=\'./venda/index.php?id_produto='.htmlspecialchars($dadosCart['id']).'&categoria='.htmlspecialchars($dadosCart['categoria']).'\'">
                                    <div class="itenInfos">
                                        <p>'.htmlspecialchars($dadosCart['produto_nome']).'</p>
                                        <strong>
                        ';
                                        
                                        if($dadosCart['valor_promocao'] > 0){
                                            echo '
                                                    <span class="valorOriginal">R$'.htmlspecialchars(number_format($dadosCart['preco'], 2, ",",".")).'</span>
                                                    <span class="precoItemCart">R$'.htmlspecialchars(number_format($dadosCart['valor_promocao'], 2, ",",".")).'</span>  
                                                ';
                                        }else{
                                            echo '<span class="precoItemCart">R$'.htmlspecialchars(number_format($dadosCart['preco'], 2, ",",".")).'</span>';
                                        }

                        echo            '</strong>
                                    </div>
                                    <div class="contBox">
                                        <button class="moreBtn"><i class="bi bi-plus-lg"></i></button>
                                        <span class="qntDisplay">'.htmlspecialchars($dadosCart['quantidade']).'</span>
                                        <button class="lessBtn"><i class="bi bi-dash"></i></button>
                                    </div>
                                    <p class="itemRemoveBtn">
                                        <i class="bi bi-trash"></i>
                                    </p>
                                </li>
                        ';

                        if($dadosCart['valor_promocao'] > 0){
                            $total += $dadosCart['valor_promocao'] * $dadosCart['quantidade'];
                        }else{
                            $total += $dadosCart['preco'] * $dadosCart['quantidade'];
                        }
                    }

                    if($resultadoCart -> num_rows > 0){
                        echo '
                         <div id="buyBox">
                            <div id="buyInfos">
                                <p>Total</p>
                                <strong id="totalCart">R$'.htmlspecialchars(number_format($total, 2, ",",".")).'</strong>
                            </div>
                            <button id="buyBtn">
                                Comprar tudo
                            </button>
                        </div>
                    ';
                    }
                }
            ?>

            <div id="emptyCart">
                <i class="bi bi-bag-heart"></i>
                <div id="emptyText">
                    <h2>Seu carrinho esta vazil</h2>
                    <p>Aproveite nossas promoções e <br> ofertas para você</p>
                </div>
            </div>
        </ul>
    </div>

    <div id="accountBox" class="hidden">
        <button class="closeBtn">
            <i class="bi bi-x-lg"></i>
        </button>
        <div id="accountInfos">
            <h3>Olá <?=$_SESSION['nome']?></h3>

            <?php 
                if(isset($_SESSION['rua']) && isset($_SESSION['numero'])){
                    echo "
                        <p>".$_SESSION['rua'].", ".$_SESSION['numero']."</p>
                    ";
                }
            ?>

            <ul>
                <li>
                    <a href="">
                        <i class="bi bi-person-circle"></i>
                        <p>Minha conta</p>
                    </a>
                </li>

                
                <?php 
                    if($_SESSION['admin'] === 'sim'){
                        echo '
                            <li>
                                <a href="./vendedor/?nome='.$_SESSION['url'].'">
                                    <i class="bi bi-tablet-landscape"></i>
                                    <p>Painel administrativo</p>
                                </a>
                            </li>
                        ';
                    }
                ?>

                <li>
                    <a href="">
                        <i class="bi bi-cart"></i>
                        <p>Minhas compras</p>
                    </a>
                </li>

                <li>
                    <a href="">
                        <i class="bi bi-clock"></i>
                        <p>Histórico</p>
                    </a>
                </li>

                <li>
                    <a href="">
                        <i class="bi bi-headset"></i>
                        <p>Suporte</p>
                    </a>
                </li>

                <li>
                    <a href="./login/deslogar.php">
                        <i class="bi bi-box-arrow-left"></i>
                        <p>Sair da conta</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="carousel-container">
        <div class="carousel">
            <div class="slide"><img src="./carrouselImg/img1.webp" alt="Oferta 1"></div>
            <div class="slide"><img src="./carrouselImg/img2.webp" alt="Oferta 2"></div>
            <div class="slide"><img src="./carrouselImg/img3.webp" alt="Oferta 3"></div>
        </div>

        <!-- Botões de navegação -->
        <button class="carouselBtn prev">&#10094;</button>
        <button class="carouselBtn next">&#10095;</button>

        <!-- Indicadores -->
        <div class="indicators">
            <input type="radio" name="indicator" id="ind0" checked>
            <input type="radio" name="indicator" id="ind1">
            <input type="radio" name="indicator" id="ind2">
        </div>
    </div>

    <nav>
        <a href="#ofertas">Ofertas</a>
        <a href="#camisetas">Camisetas</a>
        <a href="#calcas">Calças</a>
        <a href="#shorts">Shorts</a>
        <!-- <a href="#acessorios">Acessorios</a> -->
        <a href="#calcados">Calçados</a>
    </nav>

    <div id="sectionContainers">
        <section class="produtos" id="ofertas">
            <strong>Esta procurando ofertas?</strong>
            <div class="containerOrganizador">
                <button class="nav-controls prev-btn"><i class="bi bi-arrow-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                        <?php 
                            // $data = new DateTime();

                            $sqlOferta = "SELECT id, categoria, foto_1, produto_nome, valor_promocao, preco, frete FROM produtos WHERE valor_promocao != 0";

                            $resultadoOferta = $conexao->query($sqlOferta);

                            while($dadosOferta = $resultadoOferta->fetch_assoc()){
                                echo "
                                    <div class='carousel-element' onclick='window.location.href=\"./venda?id_produto=".htmlspecialchars($dadosOferta["id"])."&categoria=".htmlspecialchars($dadosOferta['categoria'])."\"'>
                                        <img src='".htmlspecialchars($dadosOferta['foto_1'])."' alt=''>
                                        <div class='produtoInfos'>
                                            <p>".htmlspecialchars($dadosOferta['produto_nome'])."</p>
                                ";
                                    verificarPromo($dadosOferta);
                                echo "
                                            <p>Frete grátis</p>
                                        </div>
                                    </div>
                                ";
                            }
                        ?>
                    </div>
                </div>
                <button class="nav-controls next-btn"><i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        <section class="produtos" id="camisetas">
            <strong>Esta procurando camisetas?</strong>
            <div class="containerOrganizador">
                <button class="nav-controls prev-btn nove"><i class="bi bi-arrow-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                    <?php 
                        produtos($conexao, 'camisetas');
                    ?>
                    </div>
                </div>
                <button class="nav-controls next-btn nove"><i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        <section class="produtos" id="calcas">
            <strong>Esta procurando calças?</strong>
            <div class="containerOrganizador">
                <button class="nav-controls prev-btn nove"><i class="bi bi-arrow-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                    <?php 
                        produtos($conexao, 'calcas')
                    ?>
                    </div>
                </div>
                <button class="nav-controls next-btn nove"><i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        <section class="produtos" id="shorts">
            <strong>Esta procurando shorts?</strong>
            <div class="containerOrganizador">
                <button class="nav-controls prev-btn nove"><i class="bi bi-arrow-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                    <?php 
                        produtos($conexao, 'shorts')
                    ?>
                    </div>
                </div>
                <button class="nav-controls next-btn nove"><i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        <section class="produtos" id="calcados">
            <strong>Esta procurando calçados?</strong>
            <div class="containerOrganizador">
                <button class="nav-controls prev-btn nove"><i class="bi bi-arrow-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                    <?php 
                        produtos($conexao, 'calcados');
                    ?>
                    </div>
                </div>
                <button class="nav-controls next-btn nove"><i class="bi bi-arrow-right"></i></button>
            </div>
        </section>

        <!-- <section class="produtos" id="masculino">
            <strong>Conheça itens masculinos</strong>
            <button class="nav-controls prev-btn dois"><i class="bi bi-arrow-left"></i></button>
            <div class="carousel-wrapper">
                <div class="carousel-track">
                <?php 
                    $sqlMasculino = "SELECT * FROM produtos WHERE genero = 'masculino'";

                    $resultadoMasculino = $conexao->query($sqlMasculino);

                    while($dadosMasculino = $resultadoMasculino->fetch_assoc()){
                        echo "
                            <div class='carousel-element' onclick='window.location.href=\"./venda?id_produto=".htmlspecialchars($dadosMasculino["id"])."&categoria=".htmlspecialchars($dadosMasculino['categoria'])."\"'>
                                <img src='".htmlspecialchars($dadosMasculino['foto_1'])."' alt=''>
                                <div class='produtoInfos'>
                                    <p>".htmlspecialchars($dadosMasculino['produto_nome'])."</p>
                                    <strong>R$".htmlspecialchars($dadosMasculino['preco'])."</strong>
                                    <p>Frete grátis</p>
                                </div>
                            </div>
                        ";
                    }
                ?>
                </div>
            </div>
            <button class="nav-controls next-btn dois"><i class="bi bi-arrow-right"></i></button>
        </section>
        
        <section class="produtos" id="feminino">
            <strong>Conheça itens femininos</strong>
            <button class="nav-controls prev-btn tres"><i class="bi bi-arrow-left"></i></button>
            <div class="carousel-wrapper">
                <div class="carousel-track">
                <?php 
                    $sqlFeminino = "SELECT * FROM produtos WHERE categoria = 'Feminino'";

                    $resultadoFeminino = $conexao->query($sqlFeminino);

                    while($dadosFeminino = $resultadoFeminino->fetch_assoc()){
                        echo "
                            <div class='carousel-element' onclick='window.location.href=\"./venda?id_produto=".htmlspecialchars($dadosFeminino["id"])."&categoria=".htmlspecialchars($dadosFeminino['categoria'])."\"'>
                                <img src='".htmlspecialchars($dadosFeminino['foto_1'])."' alt=''>
                                <div class='produtoInfos'>
                                    <p>".htmlspecialchars($dadosFeminino['produto_nome'])."</p>
                                    <strong>R$".htmlspecialchars($dadosFeminino['preco'])."</strong>
                                    <p>Frete grátis</p>
                                </div>
                            </div>
                        ";
                    }
                ?>
                </div>
            </div>
            <button class="nav-controls next-btn tres"><i class="bi bi-arrow-right"></i></button>
        </section>

        <section class="produtos" id="infantil">
            <strong>Conheça itens infantis</strong>
            <button class="nav-controls prev-btn quarto sete"><i class="bi bi-arrow-left"></i></button>
            <div class="carousel-wrapper">
                <div class="carousel-track">
                <?php 
                    $sqlInfantil = "SELECT * FROM produtos WHERE categoria = 'Infantil'";

                    $resultadoInfantil = $conexao->query($sqlInfantil);

                    while($dadosInfantil = $resultadoInfantil->fetch_assoc()){
                        echo "
                            <div class='carousel-element' onclick='window.location.href=\"./venda?id_produto=".htmlspecialchars($dadosInfantil["id"])."&categoria=".htmlspecialchars($dadosInfantil['categoria'])."\"'>
                                <img src='".htmlspecialchars($dadosInfantil['foto_1'])."' alt=''>
                                <div class='produtoInfos'>
                                    <p>".htmlspecialchars($dadosInfantil['produto_nome'])."</p>
                                    <strong>R$".htmlspecialchars($dadosInfantil['preco'])."</strong>
                                    <p>Frete grátis</p>
                                </div>
                            </div>
                        ";
                    }
                ?>
                </div>
            </div>
            <button class="nav-controls next-btn quarto sete"><i class="bi bi-arrow-right"></i></button>
        </section> -->
        
        <section class="produtos" id="acessorios">
            <strong>Esta procurando acessórios?</strong>
            <div class="containerOrganizador">
                <button class="nav-controls prev-btn oito"><i class="bi bi-arrow-left"></i></button>
                <div class="carousel-wrapper">
                    <div class="carousel-track">
                    <?php 
                     produtos($conexao, 'acessorios');
                    ?>
                    </div>
                </div>
                <button class="nav-controls next-btn oito"><i class="bi bi-arrow-right"></i></button>
            </div>
        </section>
    </div>

    <footer>
        <ul>
            <li>
                <h4>Informações da empresa</h4>
                <p><a href="#sobre-marketplace">Sobre o Marketplace</a></p>
                <p><a href="#vender-marketplace">Vender no Marketplace</a></p>
                <p><a href="#politica-privacidade">Política de privacidade</a></p>
                <p><a href="#dar-feedback">Dar feedback</a></p>
            </li>

            <li>
                <h4>Ajuda e Suporte</h4>
                <p><a href="#sobre-fetes">Sobre fetes</a></p>
                <p><a href="#devolucoes">Devoluções</a></p>
                <p><a href="#reembolso">Reembolso</a></p>
                <p><a href="#dar-feedback-ajuda">Dar feedback</a></p>
            </li>

            <li>
                <h4>Atendimento ao cliente</h4>
                <p><a href="#contate-nos">Contate-nos</a></p>
                <p><a href="#dar-feedback-atendimento">Dar feedback</a></p>
                <p><a href="#metodos-pagamento">Métodos de pagamento</a></p>
                <p><a href="#sobre-cupons">Sobre cupons</a></p>
            </li>
            
            <li>
                <h4>Instagram e Linkedin</h4>
                  
                <p>Nos encontre no Linkedin</p>
                <div>
                    <span><a href="#linkedin1">Linkedin 1</a></span>
                    <span><a href="#linkedin2">Linkedin 2</a></span>
                    <span><a href="#linkedin3">Linkedin 3</a></span>
                    <span><a href="#linkedin4">Linkedin 4</a></span>
                </div>

                <p>Nos encontre no instagram</p>
                <div>
                    <span><a href="#instagram1">Instagram 1</a></span>
                    <span><a href="#instagram2">Instagram 2</a></span>
                    <span><a href="#instagram3">Instagram 3</a></span>
                    <span><a href="#instagram4">Instagram 4</a></span>
                </div>
            </li>
        </ul>

        <div>
            <p>&copy; 2025 Marketplace, todos os direitos reservados </p>
        </div>
    </footer>
</body>
</html>