<?php 
    include_once"../conexao.php";

    $pesquisa = "%".$_GET['pesquisa']."%";

    $sql = $conexao->prepare("SELECT produtos.*, vendedores.nome_vendedor
                              FROM produtos 
                              INNER JOIN vendedores ON vendedores.id_vendedor = produtos.id_vendedor
                              WHERE produto_nome LIKE ? OR categoria LIKE ? OR descricao LIKE ?");
    $sql->bind_param('sss', $pesquisa,$pesquisa,$pesquisa);
    $sql->execute();
    $resultado = $sql->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="script.js" defer></script>
    <script src="../script.js" defer></script>
    <title>Marketeplace</title>
</head>
<body>
    <header>
        <span onclick="window.history.back()">Marketplace</span>
        <div id="searchBox">
            <input type="text" placeholder="Pesquise seu item" value="<?php echo htmlspecialchars(str_replace("%", "", $pesquisa));?>" id="searchInput">

            <i class="bi bi-search" id="searchIcon"></i>
            <i class="bi bi-x-lg" id="clearSearch"></i>
        </div>
    </header>

    <div id="container">
        <div id="painel">
            <div class="itensQnt">
                <strong><?php echo htmlspecialchars(ucfirst(str_replace("%", "", $pesquisa)));?></strong>
                <p><?php echo htmlspecialchars($resultado->num_rows);?> Produtos encontrados</p>
            </div>
            

            <?php 
                if($resultado->num_rows > 0){
                    echo '
                    <div class="produtoCondicao">
                        <!-- <p>
                            <input type="radio" name="condicao" id="" checked>
                            <label for="">Todos</label>
                        </p> -->

                        <p>
                            <input type="radio" name="" class="condicaoInput"  checked>
                            <label for="">Ofertas do dia</label>
                        </p>

                        <p>
                            <input type="radio" name="" class="condicaoInput"  checked>
                            <label for="">Masculino</label>
                        </p>

                        <p>
                            <input type="radio" name="" class="condicaoInput"  checked>
                            <label for="">Feminino</label>
                        </p>

                        <p>
                            <input type="radio" name="" class="condicaoInput"  checked>
                            <label for="">Infantil</label>
                        </p>

                        <p>
                            <input type="radio" name="" class="condicaoInput" checked>
                            <label for="">Acessorios</label>
                        </p>

                        <p>
                            <input type="radio" name="" class="condicaoInput"  checked>
                            <label for="">Calçados</label>
                        </p>
                    </div>';
                }
            ?>
        </div>
       
        <?php 
            if($resultado->num_rows > 0){
                $date = new DateTime();
                echo '
                    <div id="produtos">
                        <ul>';
                
                            while($dados = $resultado->fetch_assoc()){
                                echo '
                                    <li onclick="window.location.href=\'../venda/index.php?id_produto='.htmlspecialchars($dados['id']).'&categoria='.htmlspecialchars($dados['categoria']).'\'">
                                        <img src="'.htmlspecialchars($dados['foto_1']).'" alt="">
                                        <div class="ProdutoText">
                                ';
                                    if($dados['data_inicio_promocao'] == $date->format('Y-m-d')){
                                        echo '<div class="promocao">Oferta do dia</div>';
                                    }
                                echo '
                                            <strong>'.htmlspecialchars($dados['produto_nome']).'</strong>
                                            <p>'.htmlspecialchars($dados['descricao']).'</p>
                                            <p>Vendedor: '.htmlspecialchars($dados['nome_vendedor']).'</p>
                                            <div class="precoBox">
                                ';
                                            if($dados['valor_promocao'] > 0){
                                                echo '
                                                    <span class="precoAnterior">R$'.htmlspecialchars(number_format($dados['preco'], 2, ",", ",")).'</span>
                                                    <p>R$'.htmlspecialchars(number_format($dados['valor_promocao'], 2, ",", ".")).'</p>
                                                ';
                                            }else{
                                                echo '
                                                    <p>R$'.htmlspecialchars(number_format($dados['preco'], 2, ",", ".")).'</p>
                                                ';
                                            }
                                echo '
                                            </div>
                                            <p>Frete
                                        
                                ';
                                        
                                        if($dados['frete'] <= 0){
                                            echo '
                                                <span class="green">Grátis</span>
                                            ';
                                        }else{
                                            echo '
                                                R$<span>'.htmlspecialchars(number_format($dados['frete'], 2, ",", ".")).'</span>
                                            ';
                                        }

                                echo '
                                            </p>
                                        </div>
                                    </li>
                                ';
                            }

                echo '
                        </ul>
                    </div>
                    ';
            }else{
                echo "
                    <div id=\"noSearch\">
                        <span>
                            <i class=\"bi bi-clipboard-x\"></i>
                        </span>
                        <div>
                            <p>Não encontramos resultados para sua busca.</p>
                            <ul>
                                <li>Tente revisar sua pesquisa</li>
                                <li>Talvez seja melhor usar menos palavras</li>
                                <li>A <a href=\"../index.php\">Tela inicial</a> pode ter algo para você</li>
                            </ul>
                        </div>
                    </div>
                ";
            }
        ?>
    </div>
</body>
</html>

<?php 
    $sql->close();
    $conexao->close();
?>