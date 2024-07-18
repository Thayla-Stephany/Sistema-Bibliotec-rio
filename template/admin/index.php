<?php
include_once 'include/header.php';
include_once 'include/conexao.php';
include_once('include/funcao.php');


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
        <!-- partial -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-12 grid-margin stretch-card">

                <?php
                if (isset($_SESSION['mensagem'])) {
                  echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
                  unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
                };?>

              </div>
            </div>
            <div class="row">
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo livroempr(); ?></h3>
                  
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success ">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Emprestados</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo livrototal(); ?></h3>
                    
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de livros</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo livroatra(); ?></h3>
              
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-danger">
                          <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Atrasados</h6>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-9">
                        <div class="d-flex align-items-center align-self-start">
                          <h3 class="mb-0"><?php echo usutotal(); ?></h3>
                      
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="icon icon-box-success ">
                          <span class="mdi mdi-arrow-top-right icon-item"></span>
                        </div>
                      </div>
                    </div>
                    <h6 class="text-muted font-weight-normal">Total de usuários</h6>
                  </div>
                </div>
              </div>
            </div>

            <!--Tabela da lista de livros -->
            <?php    

$quantidade = 3;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;


    if ($_SERVER['REQUEST_METHOD'] != 'POST' ) {   
     echo'<div class="card">
          <div class="card-body">
            <div class="space">
              <div class="info-item"><br>
                <h4 class="card-title">Livros</h4>
            </div>
        
            <form method="post" action="index">
              <input class="form-control" type="search" placeholder="Pesquisar livro..." name="busca" id="busca">
            </form>
          </div>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Codigo SPN</th>
                    <th>Código de barra</th>
                    <th>Status</th>
                    <th>Informações</th>
                  </tr>
                </thead>';

            
      $sql = "SELECT * FROM cad_livro ORDER BY nome_livro ASC LIMIT $inicio,$quantidade";
      $resp_sql = mysqli_query($_SESSION['conexao'], $sql);
                   
      while ($linha = mysqli_fetch_assoc($resp_sql)) {

      if ($linha['status'] == 1) {
        $teste = '<label class="badge badge-success">Disponível</label>';
      } else {
       $teste = '<label class="badge badge-danger">Indisponível</label>';
      }
        echo'  <tbody>
            <tr>
              <td>' . $linha['nome_livro'] . '</td>
              <td>' . $linha['cod_spn'] . ' </td>
              <td>' . $linha['cod_barra'] . ' </td>
              <td class="' . $linha['status'] .'">'.$teste.'</td>
          <td><a href="inf_livro?id_livro='.$linha['id_livro'].'">Informações do livro</a></td>
            </tr>';
            
      }
        

        echo'
           
          </tbody>
          </table>
            
        </div>';
          
    }else{
      $busca = $_POST['busca'];
      $_SESSION['busca'] = $busca;

      echo'<div class="card">
      <div class="card-body">
        <div class="space">
         <div class="info-item"><br>
          <h4 class="card-title">Livros</h4>
      </div>

            <form method="post" action="index">
              <input class="form-control" type="search" placeholder="Pesquisar livro..." name="busca" id="busca">
          </form>
  </div>
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>Codigo SPN</th>
                    <th>Código de barra</th>
                    <th>Status</th>
                    <th>Informações</th>
                  </tr>
                </thead>';

    $inicio = 0;       //defina a variável $inicio aqui
    $quantidade = 12;  //defina a variável $quantidade aqui

    // Esta linha define a consulta SQL que será preparada e executada. Ela seleciona todos os campos de todos os registros na tabela cad_livro onde o nome_livro ou cod_spn correspondem a um padrão de busca. Os resultados são ordenados pelo nome_livro e limitados a um certo número de registros a partir de um ponto de início específico.
    $sql = "SELECT * FROM cad_livro WHERE nome_livro LIKE ? OR cod_spn LIKE ? ORDER BY nome_livro LIMIT ?, ?";
    $stmt = mysqli_prepare($_SESSION['conexao'], $sql);

    // Esta linha associa as variáveis $busca_sql, $busca_sql, $inicio_sql e $quantidade_sql aos parâmetros da consulta SQL. O argumento 'ssii' especifica os tipos de dados dos parâmetros: ‘s’ para strings e ‘i’ para inteiros.
    mysqli_stmt_bind_param($stmt, 'ssii', $busca_sql, $busca_sql, $inicio_sql, $quantidade_sql);
    $busca_sql = "%$busca%";
    $quantidade_sql = $quantidade;
    mysqli_stmt_execute($stmt);
    $resp_sql = mysqli_stmt_get_result($stmt);
    $num = mysqli_num_rows($resp_sql);

    // O bloco if ($num > 0) {...} verifica se há algum resultado para a consulta SQL. Se houver, ele percorre cada linha do resultado e gera uma tabela HTML com os detalhes do livro. Se o status do livro for 1, ele é marcado como ‘Disponível’, caso contrário, é marcado como ‘Indisponível’.
    
    if ($num > 0) {
        while ($linha = mysqli_fetch_array($resp_sql)) {
  
          if ($linha['status'] == 1) {
            $teste = '<label class="badge badge-success">Disponível</label>';
            } else {
            $teste = '<label class="badge badge-danger">Indisponível</label>';
            }
            echo'  <tbody>
                <tr>
                  <td>' . $linha['nome_livro'] . '</td>
                  <td>' . $linha['cod_spn'] . ' </td>
                  <td>' . $linha['cod_barra'] . ' </td>
                  <td class="' . $linha['status'] .'">'.$teste.'</td>
              <td><a href="inf_livro?id_livro='.$linha['id_livro'].'">Informações do livro</a></td>
                </tr>';
                
              }
            }else{
              echo '<center><p class="text4">Nenhum resultado encontrado</center></p>';
            
          }
          echo'
     
          </tbody>
          </table>
        </div>';
        }

        // O restante do código é responsavel por gerar a paginação para os resultados da consulta SQL

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

include_once 'include/footer.php';
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