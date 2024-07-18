<?php
// Inclusão do arquivo de conexão com o banco
include_once('include/conexao.php');

// Mensagem de feedback quando o livro for excluído.
$_SESSION['mensagem_aluno'] = '<br><center><label class="badge badge-warning" style="font-size:medium;">Aluno excluido! </label></center>';

if(isset($_GET['id_usu'])){
    $id_usu = $_GET['id_usu'];

    // SQL para deletar o usuário de forma definitiva do banco de dados.
    $sql="DELETE FROM cad_usu WHERE id_usu = $id_usu";
    $resp_query=mysqli_query($_SESSION['conexao'],$sql);

      if($resp_query){

       //Redireciona para a pagina index.php 
        header('location:lista_de_usuarios');

      }else{

        // Mensagem de feedback quando o livro for excluído.
        $_SESSION['mensagem'] ='<br><center><label class="badge badge-danger" style="font-size:medium;">Opss! Erro ao deletar dado!</label></center>';

    }  
}

?>


