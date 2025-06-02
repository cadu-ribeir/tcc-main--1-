<?php 
    header('Content-Type: Application/json');

    include_once"../../../conexao.php";

    session_start();

    if(!isset($_SESSION['id']) || $_SESSION['admin'] != 'sim'){
        header("Location:../../../login");
    }

    if(!isset($_GET['id_produto'])){
        die("Erro variaveis");
    }

    if(empty($_GET['id_produto'])){
        die("Erro variaveis vazias");
    }

    date_default_timezone_set('America/Sao_Paulo');

    $data = '0000-00-00';
    $idProduto = $_GET['id_produto'];
    $valorPromo = 0.00;

    $sql = $conexao->prepare("UPDATE produtos SET data_inicio_promocao = ?, data_final_promocao = ?, valor_promocao = ? WHERE id = ?");
    $sql->bind_param('ssdi', $data, $data, $valorPromo, $idProduto);

    if($sql->execute()){
        echo json_encode(['status' => 'sucesso']);
    }

?>