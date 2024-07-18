<?php
ob_start();

// Inclusão do arquivo de conexão com o banco e com o cabeçalho
include_once('include/header.php');
include_once('include/conexao.php');

// Usa a biblioteca PHPMailer para fazer o envio do Email referente a confirmação da devolução de um livro.
require 'email/lib/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);

?>

<!-- Div para nao ficar colado no header -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
<!-- fim div -->
<div class="card">
  <div class="card-body">

<?php

// Condição para receber os dados inseridos no formuário via POST
if(isset($_POST['Enviar'])) {

  // Variáveis vindas pelo POST
  $emprestimo = "Realizou empréstimo";
  $email = $_POST['email'];
  $cod_spn = $_POST['cod_spn'];
  $cod_spn2 = $_POST['cod_spn2'];
  $cod_spn3 = $_POST['cod_spn3'];
  $data_f = date('Y-m-d', strtotime("+21 days"));

  // Checa se o livro existe no banco
  $livro_query = "SELECT * FROM cad_livro WHERE cod_spn = '$cod_spn'";
  $roda_livro = $_SESSION['conexao']->query($livro_query);

  // Checa se a pessoa está cadastrada
  $usuario_query = "SELECT * FROM cad_usu WHERE email= '$email'";
  $roda_usu = $_SESSION['conexao']->query($usuario_query);

  // Verifica se o livro já está emprestado  
  $emp_query = "SELECT * FROM emprestimo WHERE cod_spn='$cod_spn' AND status = 1";
  $roda_emp = $_SESSION['conexao']->query($emp_query);

  // Verifica se o nome do livro está correto ou se ele existe no banco de dados.
  if (mysqli_num_rows($roda_livro) == 0 && mysqli_num_rows($roda_usu) == 0) {
    // Exibe uma mensagem com o nome do livro inserido dizendo que ele não existe ou está escrito de forma incorreta
    $_SESSION['mensagem']='<br><center><label class="badge badge-warning" style="font-size:medium;"> O ' .$resp_sql_spn['nome_livro']. ' não existe ou o nome está incorreto</label></center>';
  
    //Verifica se o email usado está correto
  } elseif (mysqli_num_rows($roda_usu) == 0) {

    // Exibe uma mensagem dizendo que o email está incorreto
    $_SESSION['mensagem']= '<br><center><label class="badge badge-warning" style="font-size:medium;">O email está incorreto</label></center>';

    // Verifica se o livro que está sendo emprestado esta disponivel na biblioteca
  } elseif (mysqli_num_rows($roda_emp) > 0) {

    // Exibe uma mensagem caso esteja indisponível
    $_SESSION['mensagem']= '<br><center><label class="badge badge-danger" style="font-size:medium;">OPS! Esse livro já foi emprestado</label></center>';

  } 

  else {

      // Podem ser emprestados até 3 livros em uma única ação do administrador

      // Faz uma verificação no banco de dados onde o email inserido for igual o encontrado para saber os outros dados do usário
      $sql_aluno = "SELECT nome_usuario, email, id_usu FROM cad_usu WHERE email = '$email'";
      $resp_aluno = mysqli_query($_SESSION['conexao'], $sql_aluno);
      $resp_sql_aluno = mysqli_fetch_array($resp_aluno, MYSQLI_ASSOC);

      // Faz uma busca no banco de dados pelo código SPN inserido do livro, buscando outras informações sobre o mesmo
      $sql_spn = "SELECT nome_livro FROM cad_livro WHERE cod_spn= '$cod_spn' ";
      $resp_spn = mysqli_query($_SESSION['conexao'], $sql_spn);

      // Faz uma busca no banco de dados pelo código SPN inserido do livro, buscando outras informações sobre o mesmo
      $sql_spn2 = "SELECT nome_livro FROM cad_livro WHERE cod_spn= '$cod_spn2'";
      $resp_spn2 = mysqli_query($_SESSION['conexao'], $sql_spn2);

      // Faz uma busca no banco de dados pelo código SPN inserido do livro, buscando outras informações sobre o mesmo
      $sql_spn3 = "SELECT nome_livro FROM cad_livro WHERE cod_spn= '$cod_spn3'";
      $resp_spn3 = mysqli_query($_SESSION['conexao'], $sql_spn3);

    //Insere os dados do formulário na tabela de empréstimo
    if ($resp_spn && mysqli_num_rows($resp_spn) > 0) {

      // Trás os dados do livro correspondente ao codigo SPN
      $resp_sql_spn = mysqli_fetch_array($resp_spn, MYSQLI_ASSOC);

      // Restante do código relacionado a $resp_sql_spn
      $nome_livro1 = $resp_sql_spn['nome_livro'];
    }
  
    if ($resp_spn2 && mysqli_num_rows($resp_spn2) > 0) {

      // Trás os dados do livro correspondente ao codigo SPN2
      $resp_sql_spn2 = mysqli_fetch_array($resp_spn2, MYSQLI_ASSOC);

      // Restante do código relacionado a $resp_sql_spn2
      $nome_livro2 = $resp_sql_spn2['nome_livro'];
      
    }else{
      // Caso nenhum valor seja adicionado ao input ele receberá vazio
      $nome_livro2 = '';
    }
  
    if ($resp_spn3 && mysqli_num_rows($resp_spn3) > 0) {

      // Trás os dados do livro correspondente ao codigo SPN3
      $resp_sql_spn3 = mysqli_fetch_array($resp_spn3, MYSQLI_ASSOC);

      // Restante do código relacionado a $resp_sql_spn3
      $nome_livro3 = $resp_sql_spn3['nome_livro'];

    }else{

      // Caso nenhum valor seja adicionado ao input ele receberá vazio
      $nome_livro3 = '';
    }


  // Faz a inserção dos dados na tabela de empréstimo, quando apenas o Primeiro campo de código SPN for preenchido
  $insert = "INSERT INTO emprestimo (id_usu, nome_usuario, email, nome_livro, cod_spn, emprestimo, data_e, data_f) VALUES 
  ({$resp_sql_aluno['id_usu']}, '{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro1', '$cod_spn', '$emprestimo', NOW(), '$data_f')";
  $resp_sql = mysqli_query($_SESSION['conexao'], $insert);

    // Condição para quando o segundo campo de código SPN for preenchido
    if ($cod_spn2 != "") {

      // Faz a inserção dos dados na tabela de empréstimo, quando o Segundo campo de código SPN for preenchido, 
      $insert2 = "INSERT INTO emprestimo (id_usu, nome_usuario, email, nome_livro, cod_spn, emprestimo, data_e, data_f) VALUES 
      ({$resp_sql_aluno['id_usu']}, '{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro2', '$cod_spn2', '$emprestimo', NOW(), '$data_f')";

      $resp_sql2 = mysqli_query($_SESSION['conexao'], $insert2);
    }

  }

    // Condição para quando o terceiro campo de código SPN for preenchido
    if ($cod_spn3 != "") {

      // Faz a inserção dos dados na tabela de empréstimo, quando o Terceiro campo de código SPN for preenchido, 
      $insert3 = "INSERT INTO emprestimo (id_usu, nome_usuario, email, nome_livro, cod_spn, emprestimo, data_e, data_f) VALUES 
      ({$resp_sql_aluno['id_usu']}, '{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro3', '$cod_spn3', '$emprestimo', NOW(), '$data_f')";

      $resp_sql3 = mysqli_query($_SESSION['conexao'], $insert3);
  }

    // Quando for verdadeira a SQL dos dados do banco, são criados dados repetidos na tabela de relatório
    if ($resp_sql === TRUE) {

      // São inseridos dados na tabela de relatório quando um livro é emprestado
      $insert2 = "INSERT INTO relatorio(nome_usuario, email, nome_livro, cod_spn, emprestimo, data_e, data_f) VALUES 
      ('{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro1', '$cod_spn', '$emprestimo', NOW(), '$data_f')";
      $resp_sql2 = mysqli_query($_SESSION['conexao'], $insert2);
    
      // São inseridos dados sobre o segundo livro emprestado
      if ($cod_spn2 != '') {

      // São inseridos dados na tabela de relatório quando o segundo livro é emprestado
        $insert2 = "INSERT INTO relatorio(nome_usuario, email, nome_livro, cod_spn, emprestimo, data_e, data_f) VALUES 
        ('{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro2', '$cod_spn2', '$emprestimo', NOW(), '$data_f')";
        $resp_sql2 = mysqli_query($_SESSION['conexao'], $insert2);
      }
    
      // São inseridos dados sobre o terceiro livro emprestado
      if ($cod_spn3 != '') {
         
        // São inseridos dados na tabela de relatório quando o segundo livro é emprestado
        $insert3 = "INSERT INTO relatorio(nome_usuario, email, nome_livro, cod_spn, emprestimo, data_e, data_f) VALUES 
        ('{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro3', '$cod_spn3', '$emprestimo', NOW(), '$data_f')";
        $resp_sql3 = mysqli_query($_SESSION['conexao'], $insert3);
      }
    
    }

      // Realiza a mudança do status do livro para 0 na tabela do livro, assim ficando com o status de "emprestado" na tela do usuário
      $sql_up = "UPDATE cad_livro SET status = 0 WHERE cod_spn = '$cod_spn'";
      $resp_sql_up = mysqli_query($_SESSION['conexao'], $sql_up);
    
      // Condição que chega caso o campo SPN2 venha preenchido
      if ($cod_spn2 != "") {
        
        // Realiza a mudança do status do livro para 0 na tabela do livro, assim ficando com o status de "emprestado" na tela do usuário
        $sql_up2 = "UPDATE cad_livro SET status = 0 WHERE cod_spn = '$cod_spn2'";
        $resp_sql_up2 = mysqli_query($_SESSION['conexao'], $sql_up2);
      }

      // Condição que chega caso o campo SPN3 venha preenchido
      if ($cod_spn3 != "") {
        
        // Realiza a mudança do status do livro para 0 na tabela do livro, assim ficando com o status de "emprestado" na tela do usuário
        $sql_up3 = "UPDATE cad_livro SET status = 0 WHERE cod_spn = '$cod_spn3'";
        $resp_sql_up3 = mysqli_query($_SESSION['conexao'], $sql_up3);
      }
    
      // Contador de livros 
      if (!empty($nome_livro1)) {
        $livrosEmprestados++;
      }
      
      if (!empty($nome_livro2)) {
        $livrosEmprestados++;
      }
      
      if (!empty($nome_livro3)) {
        $livrosEmprestados++;
      }

    // Enviar o email de confirmação do empréstimo do livro para o usuário
    //Configuração das instâncias do PHPMailer
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;
    $mail->Username = '5a36883f01a7d1';
    $mail->Password = '2c78e07384b663';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;

    $mail->setFrom('senai@gmail.com', 'Atendimento');
    $mail->addAddress($resp_sql_aluno['email']);
    
    $mail->isHTML(true);                                 
    $mail->Subject = 'Comprovante de empréstimo';

    // Corpo do e-mail
    $message = "Olá {$resp_sql_aluno['nome_usuario']}, seu empréstimo de livro foi um sucesso.<br>";

    // Verificar quantos livros foram emprestados
    $livrosEmprestados = 0;

  // A variável mensagem no email recebe o nome do livro emprestado
  if (!empty($nome_livro1)) {
    $livrosEmprestados++;
  $message .= "Título do livro 1: $nome_livro1<br>";
  }

  // A variável mensagem no email recebe o nome do livro emprestado
  if (!empty($nome_livro2)) {
    $livrosEmprestados++;
    $message .= "Título do livro 2: $nome_livro2<br>";
  }

  // A variável mensagem no email recebe o nome do livro emprestado
  if (!empty($nome_livro3)) {
    $livrosEmprestados++;
    $message .= "Título do livro 3: $nome_livro3<br>";
  }

$data_e = date('d-m-Y H:i:s');

// Verificar se pelo menos um livro foi emprestado
if ($livrosEmprestados > 0) {
  $message .= "Data de empréstimo: $data_e  <br> Data de devolução: $data_f<br>";
} else {
  $message .= "Nenhum livro foi emprestado.<br>";
}

  // Adicione mais informações relevantes para o e-mail
  $mail->Body = $message;

  $mail->send();
    
  // Mensagem de feedback como o nome de quem emprestou o livro.
  $_SESSION['mensagem'] = "<br><center><label class=\"badge badge-success\" style=\"font-size:medium;\">Empréstimo realizado com sucesso para {$resp_sql_aluno['nome_usuario']}</label></center>";
  
  // Redireciona para a página "index".
  header('Location: index');

} 
 
ob_end_flush();
?>
<head>

</head>
<h4 class="card-title">Realizar empréstimo</h4> 

<form class="forms-sample" action="emprestimo_livro" method="POST">
    <div class="form-group">
      <label>Email</label>
      <input type="text" class="form-control" name="email" >
    </div>
    <div class="form-group">
      <label>Código SPN</label>
      <input type="text" class="form-control"  name="cod_spn">
    </div>
 
    <div class="form-group">
      <label>Código SPN 2</label>
      <input type="text" class="form-control"  name="cod_spn2">
    </div> 

    <div class="form-group">
      <label>Código SPN 3</label>
      <input type="text" class="form-control"  name="cod_spn3">
    </div>

    <button type="submit" value="Enviar" name="Enviar" class="btn btn-primary mr-2">Emprestar</button>
    <button class="btn btn-dark"><a href="index">Cancelar</button>
</form>
    </div>
  </div>
</div>

<?php

// Inclui o rodapé do arquivo
include_once('include/footer.php');
?>

<style>
  a{
    text-decoration: none;
    color: #fff
  }
</style>