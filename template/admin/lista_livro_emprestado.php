<?php

// Inclui os arquivos de conexão e do cabeçalho
include_once('include/header.php');
include_once('include/conexao.php');

?>
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

<!-- Div para nao ficar colado no header -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row justify-content-center">
      <div class="col-6 grid-margin stretch-card">
        <div class="card">
<!-- fim div -->

<?php

//  Este bloco de código verifica se há uma mensagem para o aluno armazenada na sessão. Se houver, ele exibe a mensagem e a remove da sessão.

 if (isset($_SESSION['mensagem_aluno'])) {
  echo '<div class="mensagem">' . $_SESSION['mensagem_aluno'] . '</div>';
  unset($_SESSION['mensagem_aluno']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};

//  Estas linhas definem a quantidade de empréstimos a serem exibidos por página e calculam o início da consulta SQL com base na página atual.
$quantidade = 4;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

//  Estas linhas definem a consulta SQL para buscar empréstimos do banco de dados e executam a consulta.
$sql = "SELECT * FROM emprestimo WHERE status=1 OR status=2 ORDER BY data_e DESC LIMIT $inicio,$quantidade";
$resp_sql = mysqli_query($_SESSION['conexao'], $sql);


echo '
<br><center><h4 class="welcome-text">Livros Emprestados<span class="text-primary fw-bold"> </span></h4></center><br>
<div class="col-md-7 grid-margin stretch-card " style="margin: 0 auto;">

  <div class="card-body">
<div class="row">
<div class="col-lg-25">';

// Percorre cada linha do resultado da consulta SQL. Para cada empréstimo, ele verifica o status do empréstimo e atualiza o status no banco de dados se o empréstimo estiver atrasado. Em seguida, ele gera um bloco HTML com as informações do empréstimo e botões para editar o empréstimo e devolver o livro.

while ($linha = mysqli_fetch_array($resp_sql)) {
  $cod_spn = $linha['cod_spn'];
  $data = new DateTime($linha['data_f']);
  $dataAtual = new DateTime();


  if ($dataAtual == $data) {

    $teste = '<label class="badge badge-primary">Devolvido</label>';

  } elseif ($dataAtual < $data) {

    $teste = '<label class="badge badge-warning">Livro Emprestado</label>';

  } elseif ($dataAtual > $data) {

    $data_insert = date_format($data, "Y-m-d H:i:s");
    $teste = '<label class="badge badge-danger">Atrasado</label>';
    $sql_up1 = "UPDATE emprestimo SET status= 2 WHERE cod_spn =  '$cod_spn'";
    $resp_sql1 = mysqli_query($_SESSION['conexao'], $sql_up1);

  }

  if ($linha['nome_livro']) {

    echo '<h6>' . $linha['nome_livro'] . '</h6>';

  }

  echo '
     <br><b>Usuário:</b> ' . $linha['nome_usuario'] . '
     <br><b>Empréstimo:</b> ' . date('d-m-Y', strtotime($linha['data_e'])) . '
     <br><b>Devolução:</b> ' . date('d-m-Y', strtotime($linha['data_f'])) . '';

  if ($linha['cod_spn']) {

    echo '<br><b style>SPN do Livro :</b> ' . $linha['cod_spn'] . '';

  }
 
  echo '<br><br><b ' . $linha['status'] . '>Status: </b>' . $teste . '</p>';

  if ($linha['cod_spn'] ) {
    echo '
      <button type="button" class="btn btn-primary btn-rounded btn-fw"><a id="link" href="editar_emprestimo?id_opcao=' . $linha['id_opcao'] . '">Editar</a></button><br><br>
      <button type="button" class="btn btn-success btn-rounded btn-fw"><a id="link" href="devolucao_emprestimo?id_opcao=' . $linha['id_opcao'] . '&cod_spn=' . $linha['cod_spn'] . '">Devolver</a></button>
     ';
  }
  echo '<hr>';
}

//  Estas linhas calculam o número total de páginas de empréstimos e os números das páginas anterior e posterior.
$sql_total = "SELECT id_opcao FROM emprestimo";
$resp_total = mysqli_query($_SESSION['conexao'], $sql_total);
$num_total = mysqli_num_rows($resp_total);
$pagina_total = ceil($num_total / $quantidade);
$exibir = 3;
$anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$posterior = (($pagina + 1) >= $pagina_total) ? $pagina_total : $pagina + 1;

?>
<br><br>

  <?php
echo'
      </div>
    </div>
  </div>
</div>


<br><center>
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
';
  ?>

<style>
hr{
  border-color: #fff;
}

b{
  font-size: 15px;
}

.pagination {
  display: flex;
  justify-content: center;
  list-style-type: none;
  margin: 20px 0;
  padding: 0;
}

 /* Estilo CSS para centralizar a div */
.row.justify-content-center {
  display: flex;
  justify-content: center;
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
<?php

include_once 'include/footer.php';

?>