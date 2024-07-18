<?php
ob_start();

// Inclusão do arquivo de conexão com o banco e do cabeçalho.
include_once('include/header.php');
include_once('include/conexao.php');

?>

<!-- Div para nao ficar colado no header -->
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-12 grid-margin stretch-card">
<!-- fim div -->
<div class="card">
    <div class="card-body">

<?php

// Condição recebendo um POST da variável "nome_usuario" para realizar a mudança dos dados.
if(isset($_POST['nome_usuario'])){

	//POST dos dados a serem modificados
    $id_opcao = $_POST['id_opcao'];
	$nome_usuario  = $_POST['nome_usuario'];
	$cod_spn  = $_POST['cod_spn'];
	$data_f = $_POST['data_f'];
    
    // SQL para alterar as informações salvas no banco
	$sql = "UPDATE emprestimo SET nome_usuario = '$nome_usuario',cod_spn = '$cod_spn', data_f = '$data_f' WHERE id_opcao = $id_opcao";
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	
	if($rodar_sql === TRUE){ 

        // Mensagem de feedback caso os dados sejam modificados com sucesso.
        $_SESSION['mensagem'] = '<br><center><label class="badge badge-success" style="font-size:medium;">Dados atualizados</label></center>';
        
        // Redireciona a ação para a página "lista_livro_emprestado.php".
        header('Location: lista_livro_emprestado');
    
    }else{
    $_SESSION['mensagem'] = '<br><center><label class="badge badge-danger" style="font-size:medium;">Erro ao atualizar</label></center>';
    }

        // SQL para conferir os dados
        $sql2 = "SELECT * FROM emprestimo WHERE id_opcao = ".$id_opcao;
	    $rodar_sql2 = mysqli_query($_SESSION['conexao'], $sql2);
	    $linha = mysqli_fetch_array($rodar_sql2, MYSQLI_ASSOC);

}else{
	
    // SQL para exibir os dados atualizados.
	$id_get = $_GET['id_opcao'];
	$sql3 = "SELECT * FROM emprestimo WHERE id_opcao = ".$id_get;
	$rodar_sql3 = mysqli_query($_SESSION['conexao'], $sql3);
	$linha = mysqli_fetch_array($rodar_sql3, MYSQLI_ASSOC);
	
}

?>

<h4 class="card-title">Editar empréstimo</h4> 
<form class="forms-sample" action="editar_emprestimo" method="POST">
<input type="hidden" name="id_opcao" value="<?php echo $id_get; ?>">

    <div class="form-group">
    <label>Usuário</label>
    <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
    <input type="text" class="form-control" name="nome_usuario" value="<?php echo $linha['nome_usuario']; ?>">
    </div>

    <div class="form-group">
    <label>Código SPN</label>
    <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
    <input type="text" class="form-control"  name="cod_spn" value="<?php echo $linha['cod_spn']; ?>">
    </div>
             

    <div class="form-group">
    <label>Data de devolução</label>
    <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
    <input type="date" class="form-control"  name="data_f" value="<?php echo $linha['data_f']; ?>">
    </div>  

    <button type="submit" value="Enviar" name="Enviar" class="btn btn-primary mr-2">Editar</button>
    <button class="btn btn-dark"><a href="lista_livro_emprestado">Cancelar</button>
</form>
</div>
</div>
</div>

<?php
ob_end_flush();

// Inclusão do arquivo de rodapé da página.
include_once 'include/footer.php';
?>