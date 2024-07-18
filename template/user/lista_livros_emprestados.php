<?php

// Inclui os arquivos de conexão e do cabeçalho
include_once 'include/header.php';

// Esta linha recupera o ID do usuário da sessão. Este ID é usado para buscar os empréstimos do usuário no banco de dados.
$id_usu = $_SESSION['login'];
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

<div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">

<?php
 if (isset($_SESSION['mensagem_aluno'])) {
  echo '<div class="mensagem">' . $_SESSION['mensagem_aluno'] . '</div>';
  unset($_SESSION['mensagem_aluno']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};

// Estas linhas definem a quantidade de empréstimos a serem exibidos por página e calculam o início da consulta SQL com base na página atual.
$quantidade = 4;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

// Estas linhas definem a consulta SQL para buscar os empréstimos do usuário no banco de dados e executam a consulta.
$sql = "SELECT * FROM emprestimo WHERE id_usu = $id_usu ORDER BY data_e DESC LIMIT $inicio,$quantidade";
$resp_sql = mysqli_query($_SESSION['conexao'], $sql);

echo '
<div class="card">
  <div class="card-body">
    <div class="info-item">
      </div>
        <h4 class="card-title">Meus livros </h4>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Nome</th>
                <th>Empréstimo</th>
                <th>Devolução</th>
                <th>Status</th>
              </tr>
            </thead>
          <tbody> 
';

// Percorre cada linha do resultado da consulta SQL. Para cada empréstimo, ele verifica o status do empréstimo e gera uma linha de tabela HTML com as informações do empréstimo.
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
  }

  if ($linha['nome_livro']) {
    echo '<td>' . $linha['nome_livro'] . '</td>';
  }
 
  echo '
    <td>' .  date('d-m-Y', strtotime($linha['data_e'])) . ' </td>
    <td>' . date('d-m-Y', strtotime($linha['data_f'])) . ' </td>
    <td class="' . $linha['status'] .'">' . $teste . '</td>
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

// Inclui o rodapé do arquivo
include_once 'include/footer.php';
?>
