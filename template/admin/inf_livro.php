<?php

// Inclui os arquivos de conexão e do cabeçalho
include_once 'include/header.php';
include_once 'include/conexao.php';

// Este bloco de código é executado se a variável id_livro estiver definida na URL (ou seja, se a página foi acessada por um link GET). Ele busca as informações do livro com o id_livro especificado e armazena o resultado em $resp_sql.

if(isset($_GET['id_livro'])){

	$id_livro = $_GET['id_livro'];
	
	$sql = "SELECT * FROM cad_livro WHERE id_livro = ".$id_livro;
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	$resp_sql = mysqli_fetch_array($rodar_sql, MYSQLI_ASSOC);


// Este bloco de código é executado se a variável id_livro estiver definida na URL (ou seja, se a página foi acessada por um link GET). Ele busca as informações do livro com o id_livro especificado e armazena o resultado em $resp_sql.

}else{

  $id_livro = $_POST['id_livro'];
	$cod_spn  = $_POST['cod_spn'];
	$cod_barra = $_POST['cod_barra'];
	$nome_livro = $_POST['nome_livro'];
  $nome_autor = $_POST['nome_autor'];
  $emissao = $_POST['emissao'];
	$editora = $_POST['editora'];	
  $categoria = $_POST['categoria'];


	$sql = "UPDATE cad_livro SET cod_spn = '$cod_spn', cod_barra = $cod_barra, nome_livro = '$nome_livro', nome_autor = '$nome_autor', emissao = '$emissao', editora = '$editora', categoria = '$categoria' WHERE id_livro = $id_livro";
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	
  if($rodar_sql === TRUE){ // IGUAL A LIKE
    echo '<br><center><label class="badge badge-success" style="font-size:medium;">Dados atualizados</label></center>';
  }else{
    echo '<br><center><label class="badge badge-danger" style="font-size:medium;">Erro ao atualizar</label></center>';
  }
	
	$sql = "SELECT * FROM cad_livro WHERE id_livro = ".$id_livro;
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	$resp_sql = mysqli_fetch_array($rodar_sql, MYSQLI_ASSOC);
	
	$id_livro = $resp_sql['id_livro'];
	
}

?>

<!-- Gera um formulário HTML preenchido com as informações atuais do livro. O usuário pode alterar essas informações e clicar no botão “Editar” para atualizar as informações do livro no banco de dados. -->

<!-- Div para nao ficar colado no header -->
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-12 grid-margin stretch-card">
<!-- fim div -->
              
<div class="card">
    <div class="card-body">
    <div class="space">
    <div class="info-item"><br>
    <h4 class="card-title">Livro</h4>
    </div>
    <div class="info-item">
    <a class="test3" href="deletar_livro?id_livro=<?= $resp_sql['id_livro'] ?>"> Deletar o livro</a>
    </div>
    </div>

    <form class="forms-sample" type="Submit" action="inf_livro">
        <div class="form-group">
        <label>Nome do livro</label>
        <input type="text" class="form-control" name="nome_livro" value="<?php echo $resp_sql['nome_livro']; ?>">
        </div>
        <div class="form-group">
        <label>Autor</label>
        <input type="text" class="form-control"  name="nome_autor" value="<?php echo $resp_sql['nome_autor']; ?>">
        </div>
        <div class="form-group">
        <label>Data de emissão</label>
        <input type="date" class="form-control"  name="emissao" value="<?php echo $resp_sql['emissao']; ?>">
        </div>
        <div class="form-group">
        <label>Categoria</label>
        <select class="form-control" name="categoria">
              <option <?php if ($resp_sql['categoria'] == "Folheto") echo "selected";?>value="Folheto">Folheto</option>
              <option <?php if ($resp_sql['categoria'] == "Revista") echo "selected";?> value="Revista">Revista</option>
              <option <?php if ($resp_sql['categoria'] == "Livro Técnico") echo "selected";?>value="Livro Técnico">Livro Técnico</option>
              <option <?php if ($resp_sql['categoria'] == "Outros") echo "selected";?> value="Outros">Outros</option>
            </select>
        </div>
        <div class="form-group">
        <label>Editora</label>
        <input type="text" class="form-control"  name="editora" value="<?php echo $resp_sql['editora']; ?>">
        </div>
        <div class="form-group">
        <label>Código de barra</label>
        <input type="text" class="form-control" name="cod_barra" value="<?php echo $resp_sql['cod_barra']; ?>">
        </div>
        <div class="form-group">
        <label>Código SPN</label>
        <input type="text" class="form-control"  name="cod_spn" value="<?php echo $resp_sql['cod_spn']; ?>">
        </div>
      
        <button type="submit" class="btn btn-primary mr-2">Editar</button>
        <button class="btn btn-dark"><a href="index">Cancel</a></button>
    </form>
    </div>
</div>
</div>

                 
<?php
include_once 'include/footer.php';
?>

<style>

.info-item {
  display: inline-block;
}

.test3 {
  justify-content: space-between;
  display: inline-block;
}

.space{
  display: flex;
  justify-content: space-between;
  align-items: center;
}

</style>