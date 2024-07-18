<?php
//inclui os arquivos de coneção e cabeçalho
include_once('include/header.php');
include_once('include/conexao.php');
?>

<!-- Div para nao ficar bugado colado no header -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
<!-- fim div -->
<div class="card">
    <div class="card-body">
       

<?php

if (isset($_POST['Enviar'])) {

  //Váriaveis recebidas via POST
  $cad_livro = "Realizou cadastro de um livro";
  $cod_spn = $_POST['cod_spn'];
  $cod_barra = $_POST['cod_barra'];
  $nome_livro = $_POST['nome_livro'];
  $nome_autor = $_POST['nome_autor'];
  $emissao = $_POST['emissao'];
  $editora = $_POST['editora'];
  $categoria = $_POST['categoria'];

  //SQL para procurar o código SPN do livro e saber se ele ja está cadastro no BD.
  $livro = "SELECT * FROM cad_livro WHERE cod_spn = '$cod_spn'";
  $roda = $_SESSION['conexao']->query($livro);

  //Condição onde se o número de linhas encontradas forem maior que "0" exibie uma mensagem de feedback.
  if (mysqli_num_rows($roda) > 0) {

    $_SESSION['mensagem'] = '<center><div class="badge badge-warning" style="font-size:medium;">O livro com o código spn ' . $cod_spn . ' já existe</div></center>';

  } else {

    //SQL para inserir os dados do livro no banco de dados.
    $sql = "INSERT INTO cad_livro (cod_spn,cod_barra,nome_livro,nome_autor,emissao,editora,categoria,data_cad, cad_livro) VALUES ('$cod_spn',$cod_barra,'$nome_livro','$nome_autor', '$emissao', '$editora', '$categoria', NOW(), '$cad_livro')";
    $resp_sql = mysqli_query($_SESSION['conexao'], $sql);
    
    if ($resp_sql === TRUE) { 

      //Insere o que foi feito diretamente na aba relatório
      $sql_rel = "INSERT INTO relatorio_cad_livro (cod_spn,cod_barra,nome_livro,nome_autor,emissao,editora,categoria,data_cad, cad_livro) VALUES ('$cod_spn',$cod_barra,	'$nome_livro', '$nome_autor', '$emissao', '$editora', '$categoria', NOW(), '$cad_livro')";
      $resp_sql_rel = mysqli_query($_SESSION['conexao'], $sql_rel);

      // Mensagem de sessão caso a ação seja efetuada corretamente
      $_SESSION['mensagem'] = '<br><center><label  class="badge badge-success" style="font-size:medium;">Cadastro realizado</label></center>';
    } else {
      
      // Mensagem de sessão caso ocorra algum erro durante a execução
      $_SESSION['mensagem'] ='<br><center><label class="badge badge-danger" style="font-size:medium;">Erro ao cadastrar</label></center>';
    }
  }
}

// Mensagem de sessão
if (isset($_SESSION['mensagem'])) {
  echo "<br>";
  echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
  unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};

?>
 <h4 class="card-title">Cadastro de livros</h4> 

    <form class="forms-sample" action="cadastrar_livros" method="POST">
        <div class="form-group">
        <label>Nome do livro</label>
        <input type="text" class="form-control" name="nome_livro" >
        </div>
        <div class="form-group">
        <label>Autor</label>
        <input type="text" class="form-control"  name="nome_autor">
        </div>
        <div class="form-group">
        <label>Data de emissão</label>
        <input type="date" class="form-control"  name="emissao">
        </div>
        <div class="form-group">
        <label>Categoria</label>
        <select class="form-control" name="categoria">
              <option value="Folheto">Folheto</option>
              <option  value="Revista">Revista</option>
              <option value="Livro Técnico">Livro Técnico</option>
              <option  value="Outros">Outros</option>
            </select>
        </div>
        <div class="form-group">
        <label>Editora</label>
        <input type="text" class="form-control" name="editora">
        </div>
        <div class="form-group">
        <label>Código de barra</label>
        <input type="text" class="form-control" name="cod_barra">
        </div>
        <div class="form-group">
        <label>Código SPN</label>
        <input type="text" class="form-control" name="cod_spn">
        </div>
        <button type="submit" value="Enviar" name="Enviar" class="btn btn-primary mr-2">Cadastrar</button>
        <button class="btn btn-dark"><a href="index">Cancel</button>
    </form>
      </div>
    </div>
  </div>


<?php
include_once('include/footer.php');
?>

<!-- Script para controlar o tempo de exibição da mensagem de sessão -->

<script>
      // Atrasar a remoção da mensagem por 5 segundos
      setTimeout(function() {
            var mensagem = document.querySelector('.mensagem');
            if (mensagem) {
                mensagem.remove(); // Remover o elemento que contém a mensagem
            }
        }, 5000); // 5000 milissegundos = 5 segundos
</script>