<?php 
    header('Content-Type: Application/json');

    include_once"../../../conexao.php";

    session_start();

    if(!isset($_SESSION['id']) || $_SESSION['admin'] != 'sim'){
        header("Location:../../../login");
    }

    if(!isset($_GET['id_produto']) || !isset($_GET['valor_promo']) || !isset($_GET['data_final'])){
        die("erro");
    }

    if(empty($_GET['id_produto']) || empty($_GET['valor_promo']) || empty($_GET['data_final'])){
        die(json_encode(['status' => 'variaveisVazias']));
    }

    date_default_timezone_set('America/Sao_Paulo');

    $dataAtual = new DateTime();
    $dataInicio =  $_GET['inicio_promo'];
    $idProduto = $_GET['id_produto'];
    $valorPromo = $_GET['valor_promo'];
    $finalPromo = $_GET['data_final'];

    $sqlPreco = $conexao->prepare("SELECT preco FROM produtos WHERE id = ?");
    $sqlPreco->bind_param('i', $idProduto);
    $sqlPreco->execute();
    $resultado = $sqlPreco->get_result();
    $dadosPreco = $resultado->fetch_assoc();

    if($valorPromo >= $dadosPreco['preco']){
        die(json_encode(['status' => 'promocaoMaior']));
    }

    $finalPromoData = new DateTime($finalPromo);

    if($finalPromoData < $dataAtual){
        die(json_encode(['status' => 'dataErro']));
    }

    $sql = $conexao->prepare("UPDATE produtos SET data_inicio_promocao = ?, data_final_promocao = ?, valor_promocao = ? WHERE id = ?");
    $sql->bind_param('ssdi', $dataInicio, $finalPromo, $valorPromo, $idProduto);

    if($sql->execute()){
        echo json_encode(['status' => 'sucesso']);
    }
?>