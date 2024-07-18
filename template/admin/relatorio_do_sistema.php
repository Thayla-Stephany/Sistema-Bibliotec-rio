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

<?php

//  Estas linhas definem a quantidade de relatórios a serem exibidos por página e calculam o início da consulta SQL com base na página atual.
$quantidade = 2;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

// Estas linhas definem a consulta SQL para buscar relatórios do banco de dados e executam a consulta.
$sql = "SELECT * FROM relatorio WHERE nome_livro IS NOT NULL 
OR nome_livro2 IS NOT NULL 
OR nome_livro3 IS NOT NULL
OR cod_spn IS NOT NULL 
OR cod_spn2 IS NOT NULL 
OR cod_spn3 IS NOT NULL ORDER BY nome_usuario ASC LIMIT $inicio,$quantidade";
$resp_query = mysqli_query($_SESSION['conexao'], $sql);

$sql = "SELECT * FROM relatorio_cad_livro";
$resp_query_livro = mysqli_query($_SESSION['conexao'], $sql);

echo '<br><center><h4 class="welcome-text">Relatório Diário</span></h4></center><br> 
<div class="col-lg-10 grid-margin stretch-card" style="margin: 0 auto;">

    <div class="card-body">
    <div class="row">
    <div class="col-lg-12">';

// Percorre cada linha do resultado da consulta SQL. Para cada relatório, ele verifica o status do relatório e gera um bloco HTML com as informações do relatório.    
while ($linha = mysqli_fetch_array($resp_query)) {

  if ($linha['status'] == 1 || $linha['status'] == 0) {

    $data = new DateTime($linha['data_f']);
    $dataAtual = new DateTime();


    if ($linha['status'] == 0) {

      $teste = '<label class="badge badge-success">Devolvido</label>';
    } elseif ($linha['status'] == 1) {

      $teste = '<label class="badge badge-warning">Emprestado</label>';
    }

    $emprestimo = $linha['emprestimo'];
    $id_opcao = $linha['id_opcao'];
    $nome_usuario = $linha['nome_usuario'];
    $nome_livro = $linha['nome_livro'];
    $nome_livro2 = $linha['nome_livro2'];
    $nome_livro3 = $linha['nome_livro3'];
    $cod_spn = $linha['cod_spn'];
    $cod_spn2 = $linha['cod_spn2'];
    $cod_spn3 = $linha['cod_spn3'];
    $email = $linha['email'];
    $data_e =  date('d-m-Y', strtotime($linha['data_e']));
    $data_f = date('d-m-Y', strtotime($linha['data_f']));


    echo '<h4>' . $emprestimo . '</h4>
    <br><b>Nome do Usuário:</b> ' . $nome_usuario . '
    <br><b>E-mail:</b> ' . $email . '';

    if ($linha['nome_livro']) {
      echo '<br><b> Livro 1: </b>' . $linha['nome_livro'] . '';
    }
    if ($linha['nome_livro2']) {
      echo '<br><b>Livro 2: </b>' . $linha['nome_livro2'] . '';
    }
    if ($linha['nome_livro3']) {
      echo '<br><b>Livro 3: </b>' . $linha['nome_livro3'] . '';
    }
    
    
    if ($linha['cod_spn']) {
      echo '<br><b style>SPN do Livro 1:</b> ' . $linha['cod_spn'] . '';
    }
    if ($linha['cod_spn2']) {
      echo '<br><b>SPN do Livro 2:</b> ' . $linha['cod_spn2'] . '';
    }
    if ($linha['cod_spn3']) {
      echo '<br><b>SPN do Livro 3:</b> ' . $linha['cod_spn3'] . '';
    }

    echo '<br><b>Empréstimo:</b> ' . $data_e . '
    <br><b>Devolução:</b> ' . $data_f . ' <br></p>
    <hr>';
  }
}

while ($linha = mysqli_fetch_assoc($resp_query_livro)) {

  $cad_livro = $linha['cad_livro'];
  $nome_livro = $linha['nome_livro'];
  $emissao = $linha['emissao'];
  $cod_barra = $linha['cod_barra'];
  $cod_spn = $linha['cod_spn'];
  $editora = $linha['editora'];
  $categoria = $linha['categoria'];
  $data_cad =  date('d-m-Y', strtotime($linha['data_cad']));

  echo '<h4>' . $cad_livro . '</h4>
  <br><b>Nome do Livro:</b> ' . $nome_livro . '
  <br><b>Data de emissão:</b> ' . $emissao . '
    <br><b>Código de barra:</b> ' . $cod_barra . '
    <br><b>Código ISBN:</b> ' . $cod_spn . '
    <br><b>Editora :</b> ' . $editora . '
    <br><b>Categoria:</b> ' . $categoria . '
    <br><b>Data de cadastro:</b> ' . $data_cad . '
    </p>
    <hr>
        ';
}


// Estas linhas calculam o número total de páginas de relatórios e os números das páginas anterior e posterior.

$sql_total = "SELECT id_opcao FROM relatorio";
$resp_total = mysqli_query($_SESSION['conexao'], $sql_total);
$num_total = mysqli_num_rows($resp_total);
$pagina_total = ceil($num_total / $quantidade);
$exibir = 3;
$anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$posterior = (($pagina + 1) >= $pagina_total) ? $pagina_total : $pagina + 1;

echo'

        </div>
      </div>
    </div>
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
</div>';
?>

<?php

// Inclui o arquivo de rodapé
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