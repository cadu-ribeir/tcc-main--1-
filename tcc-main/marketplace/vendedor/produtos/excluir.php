<?php
    header('Content-Type: Application/json');
    include_once"../../conexao.php";
    session_start();

    if(!$_SESSION['id'] || $_SESSION['admin'] !== 'sim'){
        header("Location: ../../login");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(!isset($_POST['id_produto']) || empty($_POST['id_produto']) || !isset($_SESSION['id']) || empty($_SESSION['id'])){
            die(json_encode(['status' => "erroIds"]));
        }

        $id_produto = $_POST['id_produto'];
        $id_vendedor = $_SESSION['id'];

        $sqlProduto = $conexao->prepare("SELECT foto_1, foto_2, foto_3 FROM produtos WHERE id = ?");
        $sqlProduto->bind_param('i', $id_produto);
        $sqlProduto->execute();
        $resultado = $sqlProduto->get_result();
        $dados = $resultado->fetch_assoc();
        $sqlProduto->close();
        
        $sqlDeletar = $conexao->prepare("DELETE FROM produtos WHERE id = ?");
        $sqlDeletar->bind_param('i', $id_produto);
        
        $sqlVendedor = $conexao->prepare(" UPDATE
                                            vendedores
                                            SET itens_a_venda = 
                                            CASE
                                                WHEN itens_a_venda > 0 THEN itens_a_venda - 1
                                            END
                                            WHERE id_vendedor = ?"
                                        );
        $sqlVendedor->bind_param('i', $id_vendedor);
        
        if($sqlDeletar->execute() && $sqlVendedor->execute()){
            echo json_encode(['status' => 'sucesso']);
        }else{
            die(json_encode(['status' => 'erroDeletar']));
        }

        foreach($dados as $imagem){
            if(!empty($imagem)){
                $imagemLimpa = str_replace('http://localhost/marketplace/', "", $imagem);
     
                $caminho = '../../'.$imagemLimpa;
                $caminhoLimpo = str_replace(" ", "", $caminho);
        
                if(file_exists($caminhoLimpo)){
                    
                    if(!unlink($caminhoLimpo)){
                        die(json_encode(['status' => 'erroFoto']));
                    }

                }else{
                    die(json_encode(['status' => 'erroFoto']));
                }
    
            }else{
                die(json_encode(['status' => 'erroFoto']));
            } 
        }
    }
?>