<?php 
    include_once"../../../conexao.php";
    session_start();

    if(!isset($_SESSION['id']) || $_SESSION['admin'] != 'sim'){
        header("Location:../../../login");
    }

    if(!isset($_GET['id_produto']) || empty($_GET['id_produto'])){
        die('ERROR');
    }

    $id_produto = $_GET['id_produto'];

    $sql = $conexao->prepare("SELECT produto_nome, preco, data_inicio_promocao, data_final_promocao, valor_promocao  FROM produtos WHERE id = ?");
    $sql->bind_param('i', $id_produto);
    $sql->execute();
    $resultado = $sql->get_result();

    if($resultado ->num_rows === 0 ){
        die("Produto não encontrado");
    }


    $dados = $resultado->fetch_assoc();

    $fimPromo = date('Y-m-d', strtotime($dados['data_final_promocao']));

    date_default_timezone_set('America/Sao_Paulo');

    $dataAtual = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Editar Promoção</title>
</head>
<body>
  <div class="container">
    <h2>Editar Promoção</h2>
    <form id="formulario">
      <label for="valorAtual">Produto:</label>
      <input type="text" id="produto" name="produto" value="<?=$dados['produto_nome']?>" readonly>

      <label for="valorAtual">Valor Atual:</label>
      <input type="text" id="valorAtual" name="valorAtual" value="<?='R$'.$dados['preco']?>" readonly>

      <label for="novoValor">Valor da Promoção:</label>
      <input type="number" id="novoValor" name="novoValor" value="<?=$dados['valor_promocao']?>" step="0.01" min="0" >

      <label for="dataFinal">Data Final da Promoção:</label>
      <input type="date" id="dataFinal" value="<?=$fimPromo?>" name="dataFinal" min="<?= $dataAtual?>">

      <p id="erroMsgm"></p>

      <div class="buttonsContainer">
        <button type="button" id="removerPromo">Remover Promoção</button>
        <button type="button" id="btnVoltar" onclick="window.location.href='../editarForm.php?id_produto=<?=$id_produto?>'">Voltar</button>
      </div>

      <button type="submit">Editar Promoção</button>
    </form>
  </div>

  <div id="cadastroAviso" class="hidden">
    <h3>Promoção editada</h3>
  </div>

  <script>
    document.querySelector('#formulario').addEventListener('submit', function(event){
        event.preventDefault();
        editarPromo(this);
    })

    function editarPromo(item){
        const idProduto = <?=$id_produto?>;
        const inicioPromo = <?=$dados['data_inicio_promocao']?>;
        const valorPromo = document.querySelector('#novoValor').value;
        const dataFinal = document.querySelector('#dataFinal').value;  
        const errorMsgm = document.querySelector('#erroMsgm');
        const inputs = document.querySelectorAll('input');

        fetch(`editar.php?id_produto=${idProduto}&valor_promo=${valorPromo}&inicio_promo=${inicioPromo}&data_final=${dataFinal}`)
        .then(response => response.json())
        .then(data => {
          if(data.status === 'variaveisVazias'){
            inputs.forEach(input => {
              if(input.value.trim() === ''){
                input.style.borderColor = 'red';
              }
            })
            errorMsgm.style.display = 'block';
            errorMsgm.textContent = 'Preencha todos os campos corretamente';

          }else if(data.status === 'promocaoMaior'){
            inputs[2].style.borderColor = 'red';

            errorMsgm.style.display = 'block';
            errorMsgm.textContent = 'A promoção não pode ser maior ou igual ao valor original';

          }else if(data.status === 'dataErro'){
            inputs[3].style.borderColor = 'red';

            errorMsgm.style.display = 'block';
            errorMsgm.textContent = 'A data final da promoção deve ser maior ou igual a data de hoje';

          }else if(data.status === 'sucesso'){
            document.querySelector('#cadastroAviso').classList.remove('hidden');
            document.querySelector('#cadastroAviso').classList.add('show');

            inputs.forEach(input => {
              if(input.style.borderColor == 'red'){
                input.style.borderColor = '#ddd';
              }
            })

            errorMsgm.style.display = 'none';

            setTimeout(() => {
              document.querySelector('#cadastroAviso').classList.remove('show');
              document.querySelector('#cadastroAviso').classList.add('hidden');
            }, 1000);
          }
        })
        .catch(error => console.log('erro', error));
    }

    document.querySelector('#removerPromo').addEventListener('click', removerPromo);

    function removerPromo(){
      const confirmation = confirm("Deseja mesmo remover a promoção");

      if(confirmation){   
        const idProduto = <?=$id_produto?>;
        fetch(`removerPromo.php?id_produto=${idProduto}`)
        .then(response => response.json())
        .then(data => {
            if(data.status === 'sucesso'){
              document.querySelector('#cadastroAviso').classList.remove('hidden');
              document.querySelector('#cadastroAviso').classList.add('show');
              document.querySelector('#cadastroAviso').textContent = 'Promoção removida';

              setTimeout(() => {
                  window.location.href="../editarForm.php?id+produto=<?=$id_produto?>";
              }, 1000);
            }
        })
        .catch(error => console.log('erro', error));
      }
    }
  </script>
</body>
</html>
