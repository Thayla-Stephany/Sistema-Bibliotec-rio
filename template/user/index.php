<?php

// Esta linha inicia a captura de saída. Isso é útil se você quiser usar funções de cabeçalho depois de enviar conteúdo para o navegador.
ob_start();

// Inclui os arquivos de conexão e do cabeçalho
include_once 'include/header.php';
include_once('include/funcao.php');

require 'email/lib/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);

?>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">

<?php
  if (isset($_SESSION['mensagem'])) {
    echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
    unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};

?>

  </div>
</div>

<!--Tabela da lista de livros -->
            
<?php

// Estas linhas definem a quantidade de livros a serem exibidos por página e calculam o início da consulta SQL com base na página atual.
$quantidade = 3;
$pagina = (isset($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

echo '
<div class="card">
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col">
        <h4 class="card-title">Livros</h4>
      </div>
      <div class="col-auto">
        <form method="post" action="index">
          <input type="hidden" name="id_usu" value="' . $_SESSION["login"] . '">
          <button type="submit" class="btn btn-outline-warning" name="enviar" value="enviar">Emprestar</button>
    </div>
  </div>
';

echo '
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Codigo SPN</th>
            <th>Código de barra</th>
            <th>Status</th>
            <th>Selecionar</th>
        </tr>
    </thead>
<tbody>
';


//  Estas linhas definem a consulta SQL para buscar livros do banco de dados e executam a consulta.
$sql = "SELECT * FROM cad_livro ORDER BY nome_livro ASC LIMIT $inicio,$quantidade";
$resp_sql = mysqli_query($_SESSION['conexao'], $sql);


// Percorre cada linha do resultado da consulta SQL. Para cada livro, ele verifica o status do livro e gera uma linha de tabela HTML com as informações do livro e uma caixa de seleção para selecionar o livro para empréstimo.
while ($linha = mysqli_fetch_assoc($resp_sql)) {
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
   
// Este bloco de código é executado se a página foi acessada por um formulário POST (ou seja, se o usuário enviou um empréstimo). Ele verifica quais livros foram selecionados e insere um novo empréstimo no banco de dados para cada livro selecionado.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['enviar'])) {
        // Verificar quais linhas de dados foram selecionadas
        $selecionados = isset($_POST['selecionados']) ? $_POST['selecionados'] : array();

      // Processar os valores selecionados
      if (!empty($selecionados)) {
        $id_usu = $_POST['id_usu'];
        $emprestimo = "Realizou empréstimo";
            
        $data_f = date('Y-m-d', strtotime("+21 days"));

        // Selecionar os dados selecionados da tabela A
        $query = "SELECT * FROM cad_livro WHERE id_livro IN (".implode(",", array_fill(0, count($selecionados), "?")).")";
        $stmt = mysqli_prepare($_SESSION['conexao'], $query);
        mysqli_stmt_bind_param($stmt, str_repeat("i", count($selecionados)), ...$selecionados);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $livrosEmprestados = array();

        // Inserir os dados selecionados na tabela B
        while ($linha = mysqli_fetch_assoc($resultado)) {
                    
        $livrosEmprestados[] = $linha['nome_livro'];
              
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
             // Inserir na tabela B
        $query2 = "INSERT INTO emprestimo (nome_livro, cod_spn, id_usu, nome_usuario, email, telefone, emprestimo, data_e, data_f) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $stmt2 = mysqli_prepare($_SESSION['conexao'], $query2);
        mysqli_stmt_bind_param($stmt2, "ssisssss", $linha['nome_livro'], $linha['cod_spn'], $id_usu, $nome, $email, $telefone, $emprestimo, $data_f);
        mysqli_stmt_execute($stmt2);


        //Faz uma atualização do status do livro na tabela cad_livro o tornando indisponivel na lista principal
        $sql_up = "UPDATE cad_livro SET status= 0 WHERE cod_spn='{$linha['cod_spn']}'";
        $resp_sql = mysqli_query($_SESSION['conexao'], $sql_up);          

        $_SESSION['mensagem'] = '<center><div class="p-2 mb-2 bg-warning text-dark">Livro emprestado com sucesso!</div></center>';

        header("Location: index");
              }
            }

        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '5a36883f01a7d1';
        $mail->Password = '2c78e07384b663';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 2525;
        $mail->setFrom('senai@gmail.com', 'Atendimento');
        $mail->addAddress($email);
        
        $mail->isHTML(true);                                 
        $mail->Subject = 'Comprovante de empréstimo';
        $message=" ";
        $data_e = date('d-m-Y H:i:s');
        // Verificar se pelo menos um livro foi emprestado
      if ($livrosEmprestados > 0) {
        $message .= "Data de empréstimo: $data_e  <br> Data de devolução: $data_f<br>";
      } else {
        $message .= "Nenhum livro foi emprestado.<br>";
      }$message = "Olá $nome, seu(s) livro(s) foi(foram) emprestado(s) com sucesso.<br><br>";

      if (!empty($livrosEmprestados)) {
        $message .= "Lista de livros emprestados:<br>";
      foreach ($livrosEmprestados as $livro) {
        $message .= "- $livro<br>";
     }
    } else {
      $message .= "Nenhum livro foi emprestado.<br>";
}

// Adicione mais informações relevantes para o e-mail

$mail->Body = $message;
        //$mail->AltBody = "";

        $mail->send();
  
        }
      }
   }
}
  
        // Fechar a conexão com o banco de dados

        $sql_total = 'SELECT id_livro FROM cad_livro';
        $resp_total = mysqli_query($_SESSION['conexao'], $sql_total);
        $num_total = mysqli_num_rows($resp_total);
        $pagina_total = ($num_total <= $quantidade) ? 1 : ceil($num_total / $quantidade);
        $exibir = 3;
        $anterior  = (($pagina - 1) == 0) ? 1 : $pagina - 1;
        $posterior = (($pagina + 1) >= $pagina_total) ? $pagina_total : $pagina + 1;
        
        
        echo '
          <br>
            <center>
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
  transition: 0.3s;
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

<script>
        // Atrasar a remoção da mensagem por 5 segundos
        setTimeout(function() {
            var mensagem = document.querySelector('.mensagem');
            if (mensagem) {
                mensagem.remove(); // Remover o elemento que contém a mensagem
            }
        }, 5000); // 5000 milissegundos = 5 segundos
    </script>






	