<?php
    include_once"../../conexao.php";

    session_start();

    if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== 'sim'){
        die(header('location: ../../login/'));
    }

    if(empty($_GET['id_produto']) || !isset($_GET['id_produto'])){
        die('Não encontramos o produto');
    }

    $id = $_GET['id_produto'];

    $sql = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
    $sql->bind_param('i', $id);
    $sql -> execute();
    $resultado = $sql->get_result();

    if($resultado -> num_rows === 0){
        die('Não encontramos o produto');
    }

    $dados = $resultado->fetch_assoc();

    $finalPromo = date('d/m/Y', strtotime($dados['data_final_promocao']));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <input type="hidden" id="tmanhosInput" value="<?=$dados['tamanhos_disponiveis']?>">
    <input type="hidden" id="coresInput" value="<?=$dados['cores_disponiveis']?>">

    <div class="container">
        <h2>Editar <?=$dados['produto_nome']?></h2>
        <form id="formularioEditado">
            <input type="hidden" name="id_produto" value="<?=$dados['id']?>">
            <div class="infosContainer">
                <p>
                    <label for="nome">Nome do Produto:</label>
                    <input type="text" id="nome" name="nome" class="avisoErro" value="<?=$dados['produto_nome']?>">
                </p>
                
                <p>
                    <label for="descricao">Descrição:</label>
                    <textarea id="descricao" name="descricao" class="avisoErro"><?=$dados['descricao']?></textarea>
                </p>     

                <div class="group">
                    <p>     
                        <label for="categoria">Categoria:</label>
                        <select id="categoria" name="categoria">
                            <option value="camisetas" <?= $dados['categoria'] == 'camisetas' ? 'selected' : '' ?>>Camiseta</option>
                            <option value="calcas" <?= $dados['categoria'] == 'calcas' ? 'selected' : '' ?>>Calça</option>
                            <option value="shorts" <?= $dados['categoria'] == 'shorts' ? 'selected' : '' ?>>Short</option>
                            <option value="calcados" <?= $dados['categoria'] == 'calcados' ? 'selected' : '' ?>>Calçado</option>
                            <!-- <option value="meias">Meia</option> -->
                            <option value="roupas_intimas" <?= $dados['categoria'] == 'roupas_intimas' ? 'selected' : '' ?>>Roupa Íntima</option>
                            <option value="acessorios" <?= $dados['categoria'] == 'acessorios' ? 'selected' : '' ?>>Acessorio</option>
                            <!-- <option value="colares">Colar</option> -->
                            <!-- <option value="aneis">Anéis</option> -->
                            <!-- <option value="oculos">Óculos</option> -->
                            <!-- <option value="relogios">Relógios</option> -->
                            <!-- <option value="pulseiras">Pulseiras</option> -->
                            <!-- <option value="bolsas">Bolsas</option> -->
                            <!-- <option value="roupas_infantis">Roupas Infantis</option>  -->
                        </select>
                    </p>

                    <p>
                        <label for="genero">Gênero:</label>
                        <select id="genero" name="genero">
                            <option value="masculino" <?= $dados['genero'] == 'masculino' ? 'selected' : '' ?>>Masculino</option>
                            <option value="feminino" <?= $dados['genero'] == 'feminino' ? 'selected' : '' ?>>Feminino</option>
                            <option value="unissex" <?= $dados['genero'] == 'unissex' ? 'selected' : '' ?>>Unissex</option>
                        </select>
                    </p>
                </div>
                
                <p>
                    <label>Tamanhos Disponíveis:</label>
                    <div class="opcoes-container" id="tamanhos-container" class="avisoErro">
                        
                    </div>
                </p>
                
                <p>
                    <label>Cores Disponíveis:</label>
                    <div class="opcoes-container" id="cores-container">
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Preto">Preto</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Azul">Azul</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Vermelho">Vermelho</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Branco">Branco</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Cinza">Cinza</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Verde">Verde</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Amarelo">Amarelo</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Rosa">Rosa</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Bege">Bege</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Laranja">Laranja</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Roxo">Roxo</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Marrom">Marrom</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Turquesa">Turquesa</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Ouro">Ouro</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Prata">Prata</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Coral">Coral</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Lilás">Lilás</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Azeite">Azeite</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Caramelo">Caramelo</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Menta">Menta</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Creme">Creme</div>
                        <div class="opcao" class="avisoErro"><input type="checkbox" name="cores" value="Esmeralda">Esmeralda</div>
                    </div>
                </p>             
                
                <div class="group">
                    <p>
                        <?php 
                            if($dados['valor_promocao'] > 0){
                                echo '
                                    <label for="valor">Valor do Produto (R$):</label>
                                    <input type="number" id="valor" class="avisoErro" name="valor" value="'.$dados['preco'].'" step="0.01" min="0" readonly>

                                    <label for="promoInput">valor da promoção</label>
                                    <input type="number" id="promoInput" value="'.$dados['valor_promocao'].'" step="0.01" min="0" readonly>
                                    <span id="fimPromoSpan">Acaba em '.$finalPromo.'</span>
                                    <button type="button" id="editarPromo" onclick="window.location.href=\'./promocao/editarForm.php?id_produto='.$id.'\'" >Editar promoção</button>
                                ';
                            }else{
                                echo '
                                    <label for="valor">Valor do Produto (R$):</label>
                                    <input type="number" id="valor" class="avisoErro" name="valor" value="'.$dados['preco'].'" step="0.01" min="0">

                                    <button type="button" id="promoBtn" onclick="window.location.href=\'promocao/index.php?id_produto='.$dados['id'].'\'">Adicionar promoção</button>
                                ';
                            }
                        ?>
                    </p>
                    
                    <p>
                        <label for="condicao">Condição do Produto:</label>
                        <select id="condicao" name="condicao">
                            <option value="novo" <?=($dados['condicao'] == 'novo' ? 'selected' : '')?>>Novo</option>
                            <option value="seminovo" <?=($dados['condicao'] == 'seminovo' ? 'selected' : '')?>>Seminovo</option>
                            <option value="usado" <?=($dados['condicao'] == 'usado' ? 'selected' : '')?>>Usado</option>
                        </select>
                    </p>
                </div>

                <div class="group">
                    <p>
                        <label for="frete">Valor do Frete (R$):</label>
                        <input type="number" id="frete" class="avisoErro" name="frete" step="0.01" value="<?=$dados['frete']?>" min="0">
                    </p>    
                    
                    <p>
                        <label for="dataMaxima">prazo máximo de entrega (Dias):</label>
                        <input type="number" id="data" class="avisoErro" name="dataMaxima" value="<?=$dados['prazo_entrega']?>" min="0">
                    </p>
                </div>   
            </div>

            <div class="image-preview">
                <div class="imageContainer">
                    <p>imagem 1</p>
                    <input type="file" name="imagem-principal" id="imagem-principal" class="image-input" accept="image/*" onchange="previewImage(event, 'preview')">
                    <label for="imagem-principal"><img id="preview" class="imgAviso" src="<?=$dados['foto_1']?>" alt="Imagem Principal"></label>
                </div>
                
                <div class="imageContainer">
                    <p>imagem 2</p>
                    <input type="file" name="imagem-secundaria-1" id="imagem-secundaria-1" class="image-input" accept="image/*" onchange="previewImage(event, 'preview1')">
                    <label for="imagem-secundaria-1"><img id="preview1" class="imgAviso" src="<?=$dados['foto_2']?>" alt="Imagem Secundária 1"></label>
                </div>

                <div class="imageContainer">
                    <p>imagem 3</p>
                    <input type="file" name="imagem-secundaria-2" id="imagem-secundaria-2" class="image-input" accept="image/*" onchange="previewImage(event, 'preview2')">
                    <label for="imagem-secundaria-2"><img id="preview2" class="imgAviso" src="<?=$dados['foto_3']?>" alt="Imagem Secundária 2"></label>
                </div>
            </div>

            <span id="erroMsg">Erro</span>

            <div class="groupButton">
                <button type="button" onclick="window.location.href='../index.php?nome=<?=$_SESSION['url']?>'">Voltar</button>
                <button type="submit">Salvar Alterações</button>
                <button type="button" id="excluirBtn">Excluir Produto</button>
            </div>
        </form>
    </div>

    <div id="cadastroAviso" class="hidden">
        <h3>Alterações salvas</h3>
    </div>

    <script>
        const observer = new MutationObserver(() => {
            const opcoesDiv = document.querySelectorAll('.opcao');
            const tamanhosDisponiveis = document.querySelector('#tmanhosInput').value.split(',');
            const coresDisponiveis = document.querySelector('#coresInput').value.split(',');

            opcoesDiv.forEach((opcao, index) => {
                const opcoesInput = opcao.querySelector('input');
                if(opcoesInput){
                
                    if(tamanhosDisponiveis.includes(opcoesInput.value) || coresDisponiveis.includes(opcoesInput.value)){
                        opcao.classList.add('selected');
                        opcoesInput.checked = true;
                    }else{
                        opcao.classList.remove('selected');
                        opcoesInput.checked = false;
                    }
                }
            })
            
            inputs();
        })

        observer.observe(document.body, {childList: true, subtree: true});

        function inputs(){
            const inputschecados = document.querySelectorAll('input[type="checkbox"]:checked');

            const colorsInput = document.querySelectorAll('input[name="cores"]:checked');
            const tamanhosInput = document.querySelectorAll('input[name="tamanhos"]:checked');
        }

        const containerMsgm = document.querySelector('#cadastroAviso');

        document.querySelector('#formularioEditado').addEventListener('submit', function(event){
            event.preventDefault();

            const colorsInput = document.querySelectorAll('input[name="cores"]:checked');
            const tamanhosInput = document.querySelectorAll('input[name="tamanhos"]:checked');

            let cores = [];
            let tamanhos = [];

            colorsInput.forEach(cor =>{
                cores.push(cor.value);
            })

            tamanhosInput.forEach(tamanho => {
                tamanhos.push(tamanho.value);
            })

            const form = new FormData(this);

            form.append('coresDisponiveis', cores);
            form.append('tamanhosDisponiveis', tamanhos);

            const erroMsg = document.querySelector('#erroMsg');
            const inputs = document.querySelectorAll('.avisoErro');
            const imagensBoxs = document.querySelectorAll('.imgAviso[src="imgPadrao.png"]');

            fetch('./editar.php', {
                method: 'POST',
                body: form  
            })
            .then(response => response.json())
            .then(data => {
                console.log(data.status);
                if(data.status === 'erroVazil'){
                    erroInputs('Preencha todos os campos');
                }else if(data.status === 'erroImagem'){
                    erroInputs('Algo de errado com as imagens');
                    console.log(data.error)
                }else if(data.status === 'imgemGrande'){
                    erroInputs('Imagens muito pesadas');
                }else if(data.status === 'erroExtencao'){
                    erroInputs('Não aceitamos este tipo de arquivo');
                }else if(data.status === 'erroMovimentacaoArquivo'){
                    erroInputs('Erro ao mover as imagens');
                }else if(data.status === 'erroAlterar'){
                    erroInputs('Erro ao cadastrar o produto');
                }else if(data.status === 'alterado'){
                    containerMsgm.classList.remove('hidden');
                    containerMsgm.classList.add('show');

                    setTimeout(() => {
                        document.querySelector('#cadastroAviso').classList.remove('show');
                        document.querySelector('#cadastroAviso').classList.add('hidden');
                    }, 2000)
                }
            })
            .catch(error => console.log('erro', error))
        })

        const excluirBtn = document.querySelector('#excluirBtn');
            
        excluirBtn.addEventListener('click', () => {
            const alerta = confirm("Dseja mesmo excluir este item?");

            if(alerta){
                let form = new FormData();
                const idProduto = <?=htmlspecialchars($dados['id'])?>;

                form.append('id_produto', idProduto);

                fetch('./excluir.php', {
                    method: 'POST',
                    body: form
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    if(data.status =='sucesso'){
                        containerMsgm.classList.remove('hidden');
                        containerMsgm.classList.add('show');
                        containerMsgm.querySelector('h3').textContent = 'Produto Excluido';

                        setTimeout(() => {
                            window.location.href="../index.php?nome=<?=$_SESSION['url']?>";
                        },1000)
                    }else{
                        erroInputs('Algo de errado na exclusão');
                    }
                })
                .catch(error => console.log('erro', error));
            }
        });

        function erroInputs(erroText){
            inputs.forEach((input, i) => {
                if(input.value == ''){
                    input.style.border = "1px solid red";
                }
                if(imagensBoxs[i]){
                    imagensBoxs[i].style.border = "1px solid red";
                } 
            })

            erroMsg.style.display = 'block';
            erroMsg.textContent = erroText;
        }

        // const editarPromoBtn = document.querySelector('#editarPromo');

        // if(editarPromoBtn){
        //     editarPromoBtn.addEventListener('click', function(){
        //         const alerta = window.confirm("Deseja mesmo remover a promoção?");

        //         if(alerta){
        //             editarPromo(<?=$dados['id']?>);
        //         }
        //     });
        // }

        // function editarPromo(id){
        //     const idProduto = id;  
        //     fetch(`promocao/editarForm.php?id_produto=${idProduto}`)
        //     .then(response => response.text())
        //     .then(data => {
        //         console.log(data);
        //     })
        //     .catch(error => console.log('erro', error));
        // }
    </script>
</body>
</html>
