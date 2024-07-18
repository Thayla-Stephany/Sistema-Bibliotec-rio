<?php

// Inclui os arquivos de conexão e do cabeçalho
include_once 'include/header.php';
include_once 'include/conexao.php';

?>

<!-- Div para nao ficar bugado colado no header -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row justify-content-center">
      <div class="col-7 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
<!-- fim div -->

<head>
<script>
// Atrasar a remoção da mensagem por 5 segundos
  setTimeout(function() {
    var mensagem = document.querySelector('.mensagem');
      if (mensagem) {
      mensagem.remove(); // Remover o elemento que contém a mensagem
    }
  }, 5000); // 5000 milissegundos = 5 segundos
</script>
</head>

<?php

// Este bloco de código verifica se há uma mensagem para o aluno armazenada na sessão. Se houver, ele exibe a mensagem e a remove da sessão.
 if (isset($_SESSION['mensagem_aluno'])) {
  echo '<div class="mensagem">' . $_SESSION['mensagem_aluno'] . '</div>';
  unset($_SESSION['mensagem_aluno']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};


// Este bloco de código é executado se a página foi acessada por um formulário POST (ou seja, se o usuário enviou um relatório). Ele verifica se já existe um relatório com o mesmo texto no banco de dados. Se não existir, ele insere o novo relatório no banco de dados e exibe uma mensagem de sucesso. Se existir, ele exibe uma mensagem de erro.
if (isset($_POST['texto'])) {

    $texto = mysqli_real_escape_string($_SESSION['conexao'], $_POST['texto']);
  
    $sqli_total = "SELECT id_tex FROM relatorio_texto WHERE texto = '$texto'";
    $respi_total = mysqli_query($_SESSION['conexao'], $sqli_total);
    $nume_total = mysqli_num_rows($respi_total);
  
    if ($nume_total > 0) {
  
        $_SESSION['mensagem_aluno']= '<center><label style="font-size:medium;" class="badge badge-warning">Dados iguais não podem ser inseridos</label></center>';
    } else {
      $sql_in = "INSERT INTO relatorio_texto (texto,data_r) VALUES ('$texto',NOW())";
      $resp_sql_in = mysqli_query($_SESSION['conexao'], $sql_in);
  
      if ($resp_sql_in) {
        $_SESSION['mensagem_aluno']= '<center><label style="font-size:medium;" class="badge badge-success">Relatório inserido com sucesso</label></center>';
      } else {
        $_SESSION['mensagem_aluno']='<center><label style="font-size:medium;" class="badge badge-warning">Erro ao inserir</label></center>';
      }
    }
  }
  
?>

<h3 class="welcome-text" style= "color:'#000';text-align:center;">Relatório Semanal</h3>
<div class="col-10 grid-margin stretch-card mx-auto">
  <div class="card">
    <div class="card-body"><br><br>
      <form class="forms-sample" action="relatorio_de_gerenciamento" method="post">
       <center><textarea name="texto" id="" class="col-12" cols="100" rows="10" placeholder="Escreva aqui seu relatório..." maxlength="100000000" ></textarea><br><br>
        <button type="submit" class="btn btn-primary me-5" value="Enviar" style="position:relative;left:5%;">Enviar</button></center>
      </form>
    </div>
  </div>
</div>

<?php
// Estas linhas definem a quantidade de relatórios a serem exibidos por página e calculam o início da consulta SQL com base na página atual.
$quantidade = 4;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

// Estas linhas definem a consulta SQL para buscar relatórios do banco de dados e executam a consulta.
$sql = "SELECT * FROM relatorio_texto ORDER BY data_r DESC LIMIT $inicio,$quantidade";
$resp_sql = mysqli_query($_SESSION['conexao'], $sql);
$dados = mysqli_num_rows($resp_sql);

echo '<br><div class="col-10 grid-margin mx-auto">
<div class="card">
  <div class="card-body">';
if ($dados > 0){

  // Percorre cada linha do resultado da consulta SQL. Para cada relatório, ele gera um bloco HTML com as informações do relatório e botões para editar e deletar o relatório.
  while ($linha = mysqli_fetch_array($resp_sql)) {
    echo '' . $linha['texto'] .'<br><br>
    <b style="display:none;">'.$linha['id_tex'].'</b>
    <b>Data e Hora:</b> ' . date('d-m-Y H:m:s', strtotime($linha['data_r'])) .'<br>

    <p class="template-demo">
    <button type="button" class="btn btn-info btn-rounded btn-sm"><a id="link" href="editar_relatorio?id_tex=' . $linha['id_tex'] . '">Editar</a></button>
    <button type="button" class="btn btn-warning btn-rounded btn-sm"><a id="link" href="deletar_relatorio?id_tex=' . $linha['id_tex'] . '">Deletar</a></button>
    </p><hr>';
    
  }

}else{
  echo '<center><p class="text-danger">Nenhum relatório postado</p></center>';
}

// Estas linhas calculam o número total de páginas de relatórios e os números das páginas anterior e posterior.
$sql_total = "SELECT id_tex FROM relatorio_texto";
$resp_total = mysqli_query($_SESSION['conexao'], $sql_total);
$num_total = mysqli_num_rows($resp_total);
$pagina_total = ceil($num_total / $quantidade);
$exibir = 3;
$anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$posterior = (($pagina + 1) >= $pagina_total) ? $pagina_total : $pagina + 1;

echo'

  </div>
    </div>
      <br>
        <center>
          <ul class="pagination">
            <li><a href ="?pagina=1"><<</a></li>';
              echo '<a href ="?pagina=' . $anterior . '"><</a></li>';
  
               for ($i = $pagina - $exibir; $i <= $pagina - 1; $i++) {
                if ($i > 0)
                  echo '<li><a href ="?pagina=' . $i . '">' . $i . '</a></li>';
              }
  
            echo '<li><a href="?pagina=' . $pagina . '"><strong>' . $pagina . '</strong></a></li>';
  
              for ($i = $pagina + 1; $i < $pagina + $exibir; $i++) {
                if ($i <= $pagina_total)
                   echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
              }

           echo '<li><a  id="link" href="?pagina=' . $posterior . '">></a></li>';
           echo '<li><a href="?pagina=' . $pagina_total . '">>></a></li> 
        </ul>
     </center>    
    </div>
</div>
      </div>
    </div>
  </div>
</div>';
?>
    
<?php
include_once 'include/footer.php';
?>

<style>

hr{
  border-color: #fff;
}

     /* Estilo CSS para centralizar a div */
.row.justify-content-center {
  display: flex;
  justify-content: center;
}

.pagination {
  display: flex;
  justify-content: center;
  list-style-type: none;
  margin: 20px 0;
  padding: 0;
}

.pagination li {
  margin-right: 5px;
}

.pagination a {
  color: #fff;
  display: inline-block;
  padding: 8px 16px;
  text-decoration: none;
  transition: background-color 0.3s;
  border-radius: 50px;
}

.pagination a.active {
  background-color: #4CAF50;
  color: white;
}

.pagination a:hover:not(.active) {
  background-color: #000;
  border-radius: 50px;
}

#link{
  text-decoration: none;
  color: white;
}

#link:hover{
  color: white;
}

</style>