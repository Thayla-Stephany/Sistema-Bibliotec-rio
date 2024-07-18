
<?php

// Inclui os arquivos de conexão, cabeçalho e funções

include_once 'include/header.php';

$pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

// Este bloco de código verifica se a página foi acessada por um formulário GET (ou seja, se o usuário pesquisou um livro). Se foi, ele define a consulta SQL para buscar livros que correspondem ao termo de pesquisa. Se não foi, ele redireciona o usuário para a página index.
if (!isset($_GET['pesquisar'])) {
  header("Location: index");
} else {
  $valor_pesquisar = $_GET['pesquisar'];


  //Selecionar todos os livros da tabela
  $livros = "SELECT * FROM cad_livro WHERE nome_livro LIKE '%$valor_pesquisar%' OR cod_spn LIKE '%$valor_pesquisar%'";
  // print_r($livros);
  // exit();
  $query_livro = $_SESSION['conexao']->query($livros);


  //Contar o total de livros
  $num_total = mysqli_num_rows($query_livro);

  //Seta a quantidade de livros por pagina
  $quantidade = 2;

  //calcular o número de pagina necessárias para apresentar os livros
  $pagina_total = ceil($num_total / $quantidade);

  //Calcular o inicio da visualização
  $inicio = ($quantidade * $pagina) - $quantidade;

  //Selecionar os livros a serem apresentado na página
  $sql_total = "SELECT * FROM cad_livro WHERE nome_livro LIKE '%$valor_pesquisar%' OR cod_spn LIKE '%$valor_pesquisar%' LIMIT $inicio, $quantidade"; //cod --> identificação= aaaa ou bbbb
  $resp_total = mysqli_query($_SESSION['conexao'], $sql_total);
  $num_total = mysqli_num_rows($resp_total);

  echo '   <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-12 grid-margin stretch-card">
  
  <div class="card">
    <div class="card-body">
      <div class="info-item">
        <h4 class="card-title">Livros</h4>
          </div>
          <form method="post" action="index">
            <input type="hidden" name="id_usu" value="' . $_SESSION["login"] . '">
              <center>
                <br>
                  <button type="submit" class="btn btn-outline-warning m-2" name="enviar" value="enviar">Emprestimo</button></center>
<table class="table table-hover">
  <thead>
      <tr>
          <th>Nome</th>
          <th>Codigo SPN</th>
          <th>Código de barra</th>
          <th>Status</th>
          <th>Informações</th>
          <th>Selecionar</th>
      </tr>
  </thead>
<tbody>
';

  if ($num_total > 0) {
    while ($linha = mysqli_fetch_assoc($resp_total)) {
          if ($linha['status'] == 1) {
              $teste = '<label class="badge badge-success">Disponível</label>';
          } else {
              $teste = '<label class="badge badge-danger">Indisponível</label>';
          }
      
          echo '
          <tr>
              <td>' . $linha['nome_livro'] . '</td>
              <td>' . $linha['cod_spn'] . ' </td>
              <td>' . $linha['cod_barra'] . ' </td>
              <td class="' . $linha['status'] .'">' . $teste . '</td>
              <td><a href="inf_livro?id_livro=' . $linha['id_livro'] . '">Informações do livro</a></td>
              <td>
                  <div class="form-check">
                      <label class="form-check-label">
                          <input type="checkbox" class="form-check-input" name="selecionados[]" value="' . $linha['id_livro'] . '"> <i class="input-helper"></i>
                      </label>
                  </div>
              </td>
          </tr>
          ';
      }
      
      echo '
          </tbody>
      </table>
      </form>
      </div>
      ';
      // Recupera o ID do usuário da $_SESSION
      $id_usuario = $_SESSION['login'];
      
      // Consulta SQL para recuperar os dados do usuário
      $query_usuario = "SELECT nome_usuario, email, telefone FROM cad_usu WHERE id_usu = ?";
      $stmt_usuario = mysqli_prepare($_SESSION['conexao'], $query_usuario);
      mysqli_stmt_bind_param($stmt_usuario, "i", $id_usuario);
      mysqli_stmt_execute($stmt_usuario);
      $resultado_usuario = mysqli_stmt_get_result($stmt_usuario);
      
      // Verifica se encontrou o usuário
      if ($linha_usuario = mysqli_fetch_assoc($resultado_usuario)) {
          $nome = $linha_usuario['nome_usuario'];
          $email = $linha_usuario['email'];
          $telefone = $linha_usuario['telefone'];
      
          // Agora você pode usar as variáveis $nome, $email, $telefone para inserir na tabela emprestimo
         
      
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          if (isset($_POST['enviar'])) {
              // Verificar quais linhas de dados foram selecionadas
              $selecionados = isset($_POST['selecionados']) ? $_POST['selecionados'] : array();
      
              // Processar os valores selecionados
              if (!empty($selecionados)) {
                  $id_usu = $_POST['id_usu'];
                  
                  $data_f = date('Y-m-d', strtotime("+21 days"));
      
                  // Selecionar os dados selecionados da tabela A
                  $query = "SELECT * FROM cad_livro WHERE id_livro IN (".implode(",", array_fill(0, count($selecionados), "?")).")";
                  $stmt = mysqli_prepare($_SESSION['conexao'], $query);
                  mysqli_stmt_bind_param($stmt, str_repeat("i", count($selecionados)), ...$selecionados);
                  mysqli_stmt_execute($stmt);
      
                  $resultado = mysqli_stmt_get_result($stmt);
      
                  // Inserir os dados selecionados na tabela B
                  while ($linha = mysqli_fetch_assoc($resultado)) {
                               
       $livro_query = "SELECT * FROM cad_livro WHERE cod_spn = '{$linha['cod_spn']}'";
       $roda_livro = $_SESSION['conexao']->query($livro_query);
      
       //Checa se a pessoa está cadastrada
       $usuario_query = "SELECT * FROM cad_usu WHERE email= '$email'";
       $roda_usu = $_SESSION['conexao']->query($usuario_query);
      
      
       // Verifica se o livro já está emprestado  
       $emp_query = "SELECT * FROM emprestimo WHERE cod_spn='{$linha['cod_spn']}' AND status = 1";
       $roda_emp = $_SESSION['conexao']->query($emp_query);
      
       if (mysqli_num_rows($roda_emp) > 0) {
         $_SESSION['mensagem']= '<br><center><label class="badge badge-danger" style="font-size:medium;">OPS! Esse livro já foi emprestado</label></center>';
      
       }else{
                      // Inserir na tabela B
                      $query2 = "INSERT INTO emprestimo (nome_livro, cod_spn, id_usu, nome_usuario, email, telefone, data_e, data_f) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
                      $stmt2 = mysqli_prepare($_SESSION['conexao'], $query2);
                      mysqli_stmt_bind_param($stmt2, "ssissss", $linha['nome_livro'], $linha['cod_spn'], $id_usu, $nome, $email, $telefone, $data_f);
                      mysqli_stmt_execute($stmt2);
      
      
                          //Faz uma atualização do status do livro na tabela cad_livro o tornando indisponivel na lista principal
            $sql_up = "UPDATE cad_livro SET status= 0 WHERE cod_spn='{$linha['cod_spn']}'";
            $resp_sql = mysqli_query($_SESSION['conexao'], $sql_up);          
            $_SESSION['mensagem'] = '<center><div class="p-2 mb-2 bg-warning text-dark">Livro emprestado com sucesso!</div></center>';
      
            header("Location: index");
                    }
                  }
              }
            }
         }
    }
  }else {
    echo '<center><p class="text-danger">Nenhum resultado encontrado</p></center>';
  }
}

$sql_total = 'SELECT id_livro FROM cad_livro';
$resp_total = mysqli_query($_SESSION['conexao'], $sql_total);
$num_total = mysqli_num_rows($resp_total);
$pagina_total = ($num_total <= $quantidade) ? 1 : ceil($num_total / $quantidade);
$exibir = 3;
$anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
$posterior = (($pagina + 1) >= $pagina_total) ? $pagina_total : $pagina + 1;

  echo '
  <br><center>
  <ul class="pagination">
    <li><a href="?pagina=1"><<</a></li>';
  
  echo '<li><a href="?pagina=' . $anterior . '"><</a></li>';
  
  for ($i = $pagina - $exibir; $i <= $pagina - 1; $i++) {
    if ($i > 0)
      echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
  }
  
  echo '<li><a href="?pagina=' . $pagina . '"><strong>' . $pagina . '</strong></a></li>';
  
  for ($i = $pagina + 1; $i <= $pagina + $exibir; $i++) {
    if ($i <= $pagina_total)
      echo '<li><a href="?pagina=' . $i . '">' . $i . '</a></li>';
  }

  echo '<li><a href="?pagina=' . $posterior . '">></a></li>';
  echo '<li><a href="?pagina=' . $pagina_total . '">>></a></li> 
  </ul>
  </center>
  </div>
  </div>
  ';
  
  
  ?>               
               <!-- Fim tabela da lista de livros -->
  
  <?php
  ob_end_flush();
  include_once 'include/footer.php';
  ?>
  
  
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
    transition:  0.3s;
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