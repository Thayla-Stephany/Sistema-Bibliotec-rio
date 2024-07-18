<?php 
// Inclusão do arquivo de conexão com o banco
include_once('include/conexao.php');

// Mensagem de feedback quando o livro for excluído.
$_SESSION['mensagem'] = '<br><center><label class="badge badge-warning" style="font-size:medium;">Livro excluido! </label></center>';

if(isset($_GET['id_livro'])){
    $id_livro = $_GET['id_livro'];

    // SQL para deletar o livro de forma definitiva do banco de dados.
    $sql="DELETE FROM cad_livro WHERE id_livro = $id_livro";
    $resp_query=mysqli_query($_SESSION['conexao'],$sql);

    if($resp_query){

       // Redireciona para a pagina index.php 
       header('location:index');

    }else{

        // Mensagem de feedback quando o livro for excluído.
        $_SESSION['mensagem'] ='<br><center><label class="badge badge-danger" style="font-size:medium;">Opss! Erro ao deletar dado!</label></center>';

    }
}

?>


