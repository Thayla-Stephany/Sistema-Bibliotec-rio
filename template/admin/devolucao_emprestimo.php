<?php
// Inclusão do arquivo de conexão com o banco
include_once('include/conexao.php');

// Usa a biblioteca PHPMailer para fazer o envio do Email referente a confirmação da devolução de um livro.
require 'email/lib/vendor/autoload';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer(true);

// Condição para alterar a data de devolução
if (isset($_GET['id_opcao'])) {
  $id_opcao = $_GET['id_opcao'];

  //Altera a data de devolução para a data em que o livro foi devolvido para ser impresso no PDF de devolução
  $sql_data = "UPDATE emprestimo SET data_f = NOW() WHERE id_opcao = $id_opcao";
  $resp_query_data = mysqli_query($_SESSION['conexao'], $sql_data);

  $select = "SELECT * FROM emprestimo WHERE id_opcao = $id_opcao";
  $resp_select = mysqli_query($_SESSION['conexao'], $select);
  $linha = mysqli_fetch_array($resp_select);


    // Enviar o email de confirmação do empréstimo do livro para o usuário
    //Configuração das instâncias do PHPMailer
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'sandbox.smtp.mailtrap.io';
    $mail->SMTPAuth = true;

    // Para testar a funcionalidade do envio do email, crie uma conta no MailTrap e use o username e o password gerados la
    $mail->Username = '';
    $mail->Password = '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 2525;
    $mail->setFrom('senai@gmail.com', 'Atendimento');
    $mail->addAddress($linha['email']);
    $mail->isHTML(true);                                 
    $mail->Subject = 'Comprovante de empréstimo';

    // Corpo do e-mail
    $message = "Olá {$linha['nome_usuario']}, seu empréstimo de livro foi um sucesso.<br>";

    // Verificar quantos livros foram emprestados
    $livrosEmprestados = 0;

  // A variável mensagem no email recebe o nome do livro devolvido

    if (!empty($linha['nome_livro'])) {
      $livrosEmprestados++;
      $message .= "Título do livro 1: {$linha['nome_livro']}<br>";
    }


    // Verificar se pelo menos um livro foi emprestado
    if ($livrosEmprestados > 0) {
      $message .= "Data de empréstimo:  {$linha['data_e']}  <br> Data de devolução: {$linha['data_f']}<br>";
    } else {
      $message .= "Nenhum livro foi emprestado.<br>";
    }

    // Adicione mais informações relevantes para o e-mail
    $mail->Body = $message;
          //$mail->AltBody = "";

    $mail->send();

    // Mensagem de feedback caso haja sucesso na devolução do livro.
    $_SESSION['mensagem'] = "<br><center><label class=\"badge badge-success\" style=\"font-size:medium;\">Devolução realizado com sucesso para {$linha['nome_usuario']}</label></center>";
}

// Condição que recebe a variavel "id_opção via GET
if (isset($_GET['id_opcao'])) {
    $id_opcao = $_GET['id_opcao'];
    $cod_spn = $_GET['cod_spn'];

    //Realiza uma atualização do status do empréstimo para zero o tornando "deletado" nos registros
    $sql = "DELETE FROM emprestimo WHERE id_opcao = $id_opcao";
    $resp_query = mysqli_query($_SESSION['conexao'], $sql);

    if ($resp_query) {

      //Realiza um update no status na tabela cad_livro tornando o livro disponivel na lista principal
      $sql = "UPDATE cad_livro SET status = 1 WHERE cod_spn = $cod_spn";
      $resp_query = mysqli_query($_SESSION['conexao'], $sql);

      // Redireciona para a pagina "index.php"
      header('Location: index');

  }
}

?>

<style>
  a {
    text-decoration: none;
    color: white;
  }

  a:hover {
    color: white;
  }
</style>