<?php

// Inclui os arquivos de conexão e do cabeçalho
include_once('include/header.php');
include_once('include/conexao.php');

?>

<!-- Inicío da div -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card">
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

// Estas linhas definem a quantidade de usuários a serem exibidos por página e calculam o início da consulta SQL com base na página atual.

$quantidade = 4;
$pagina  = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

// Estas linhas definem a consulta SQL para buscar usuários do banco de dados e executam a consulta.

$sql = "SELECT * FROM cad_usu ORDER BY nome_usuario LIMIT $inicio,$quantidade";
$resp_sql = mysqli_query($_SESSION['conexao'],$sql);

 if (isset($_SESSION['mensagem_aluno'])) {
  echo '<div class="mensagem">' . $_SESSION['mensagem_aluno'] . '</div>';
  unset($_SESSION['mensagem_aluno']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
  };

  // Este bloco de código é executado se a página foi acessada normalmente (ou seja, não por um formulário POST). Ele gera uma tabela HTML com os usuários buscados.

  if ($_SERVER['REQUEST_METHOD'] != 'POST' ) {   
    echo'
      <div class="card-body">
        <div class="space">
          <div class="info-item"><br>
            <h4 class="card-title">Usuário</h4>
      </div>
        <form method="post" action="lista_de_usuarios">
          <input class="form-control" type="search" placeholder="Pesquisar usuário..." name="busca" id="busca">
        </form>
      </div>
    <div class="table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Endereço</th>
            <th>Telefone</th>
            <th>Opçõe</th>
          </tr>
        </thead>';

    while($linha= mysqli_fetch_array($resp_sql)){

      echo'  <tbody>
        <tr>
          <td>' . $linha['nome_usuario'] . '</td>
          <td >' . $linha['email'] .'</td>                                                       
          <td >' . $linha['endereco'] .'</td>                                   
          <td >' . $linha['telefone'] .'</td>                                   
          <td><button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="location.href=\'editar_usuarios?id_usu='.$linha['id_usu']. '\'" title="Editar"><i class="mdi mdi-pencil-outline"></i></button> -    
          <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="if(confirm(\'Tem certeza que deseja excluir?\')) location.href=\'deletar_usuarios?id_usu='.$linha['id_usu']. '\'" title="Excluir"><i class="mdi mdi-delete-outline"></i></button></td>
        </div>
       </tr>';
    }
               
?>
<br><br>

 <?php
 
 echo'

 </tbody>
  </table>
  </div>
</div>';

//  Este bloco de código é executado se a página foi acessada por um formulário POST (ou seja, se o usuário pesquisou um usuário). Ele define a consulta SQL para buscar usuários cujo nome contém a string de busca, prepara e executa a consulta, e gera uma tabela HTML com os usuários encontrados.

}else{
  $busca = $_POST['busca'];
  $_SESSION['busca'] = $busca;

  echo'
    <div class="card-body">
      <div class="space">
        <div class="info-item"><br>
          <h4 class="card-title">Usuários</h4>
    </div>
  
      <form method="post" action="lista_de_usuarios">
        <input class="form-control" type="search" placeholder="Pesquisar usuário..." name="busca" id="busca">
      </form>
    </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Endereço</th>
              <th>Telefone</th>
              <th>Opçoes</th>
            </tr>
          </thead>';

  $inicio = 0;       //defina a variável $inicio aqui
  $quantidade = 12;  //defina a variável $quantidade aqui

  $sql = "SELECT * FROM cad_usu WHERE nome_usuario LIKE ? ORDER BY nome_usuario LIMIT ?, ?";
  $stmt = mysqli_prepare($_SESSION['conexao'], $sql);
  mysqli_stmt_bind_param($stmt, 'sii', $busca_sql, $inicio_sql, $quantidade_sql);
  $busca_sql = "%$busca%";
  
 
  $quantidade_sql = $quantidade;
  mysqli_stmt_execute($stmt);
  $resp_sql = mysqli_stmt_get_result($stmt);
  $num = mysqli_num_rows($resp_sql);
  
  if ($num > 0) {
      while ($linha = mysqli_fetch_array($resp_sql)) {

          echo'
            <tbody>
              <tr>
                <td>' . $linha['nome_usuario'] . '</td>
                <td>' . $linha['email'] .'</td>                                                       
                <td>' . $linha['endereco'] .'</td>                                   
                <td>' . $linha['telefone'] .'</td>                                   
                <td><button type="button" class="btn btn-primary btn-rounded btn-icon" onclick="location.href=\'editar_usuarios?id_usu='.$linha['id_usu']. '\'" title="Editar"><i class="mdi mdi-pencil-outline"></i></button> -    
                <button type="button" class="btn btn-danger btn-rounded btn-icon" onclick="if(confirm(\'Tem certeza que deseja excluir?\')) location.href=\'deletar_usuarios?id_usu='.$linha['id_usu']. '\'" title="Excluir"><i class="mdi mdi-delete-outline"></i></button></td>
                </div>
              </tr>';
        }
}else{
  echo '<center><p class="text4">Nenhum resultado encontrado</center></p>';
}

 echo'
    </tbody>
  </table>
</div>
';
}

// O restante do código é responsavel por gerar a paginação para os resultados da consulta SQL


$sql_total = 'SELECT id_usu FROM cad_usu';
$resp_total= mysqli_query($_SESSION['conexao'],$sql_total);
$num_total = mysqli_num_rows($resp_total);
$pagina_total = ceil($num_total/$quantidade);
$exibir = 3;
$anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$posterior = (($pagina+1) >= $pagina_total) ? $pagina_total : $pagina+1;

echo '
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
</div>';
 ?>

<!--  Estilo para a paginação  -->

<style>

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

.info-item {
  display: inline-block;
}

.test3 {
  justify-content: space-between;
  display: inline-block;
}
.text4{
  color: #EB1616;
}

.space{
  display: flex;
  justify-content: space-between;
  align-items: center;
}

</style>

<?php

// Inclúi o arquivo de rodapé
include_once 'include/footer.php';

?>