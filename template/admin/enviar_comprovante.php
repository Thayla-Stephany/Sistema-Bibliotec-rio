<?php

include_once 'include/header.php';


?>

<!-- Div para nao ficar colado no header -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
<!-- fim div -->
<div class="card">

<?php

// Utilizando o PHPMailer para enviar o comprovante de empréstimo via email
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// A condição verifica se a variavel recebeu algum valor via POST
if(isset($_POST['email'])){

  // A condição usando "srtlen" verifica se a variável  "$email" possui algum caractere 
  if(strlen($_POST['email']) == 0){

    // Exibe uma mensagem pedindo para preencher o campo email
    echo '<br><center><label class="badge badge-danger" style="font-size:medium;">Preencha seu email!</label></center>';
               
  }else{
 
  // Recebe valores via POST da data    
  $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

  // A concição verifica se a variável "$data" esta vazia
  if(!empty($data['Enviar'])){

  // Esta linha está pegando o arquivo enviado pelo usuário através de um formulário HTML e armazenando-o na variável $attachment
  $attachment = $_FILES['attachment'];

  //Estas linhas estão pegando os dados enviados pelo usuário através de um formulário HTML (o endereço de e-mail e o conteúdo do e-mail) e armazenando-os nas variáveis $email e $content, respectivamente
  $email = $_POST['email'];
  $content = $_POST['content'];

  //Esta linha está incluindo o arquivo autoload.php que carrega automaticamente todas as classes da biblioteca PHPMailer.
  require 'email/lib/vendor/autoload.php';


$mail = new PHPMailer(true);

    // O bloco try/catch está sendo usado para capturar e lidar com qualquer exceção que possa ocorrer durante o processo de envio do e-mail. Se o e-mail for enviado com sucesso, uma mensagem de sucesso é exibida. Se ocorrer um erro, uma mensagem de erro é exibida.
    try {

      // As próximas linhas estão configurando o PHPMailer para usar o SMTP para enviar o e-mail. Elas definem o conjunto de caracteres, o host SMTP, as credenciais de autenticação, o tipo de criptografia e a porta.
      $mail->CharSet = 'UTF-8';
      $mail->isSMTP();
      $mail->Host = 'sandbox.smtp.mailtrap.io';
      $mail->SMTPAuth = true;
      
      // Para testar a funcionalidade do envio do email, crie uma conta no MailTrap e use o username e o password gerados la
      $mail->Username = '';
      $mail->Password = '';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 2525;

      // Estas linhas estão definindo o endereço de e-mail do remetente e adicionando o endereço de e-mail do destinatário.
      $mail->setFrom('senai@gmail.com', 'Atendimento');
      $mail->addAddress($email);
    
      // O bloco if verifica se um arquivo foi enviado. Se sim, ele é anexado ao e-mail. Se não, uma mensagem é exibida pedindo ao usuário para inserir um anexo.
      if(isset($attachment['name']) AND !empty($attachment['name'])){

        $mail->addAttachment($attachment['tmp_name'], $attachment['name']);

      }else{
        echo '<br><center><label class="badge badge-danger" style="font-size:medium;">Insira um anexo!!</label></center>';
      }

      // Esta linha está configurando o PHPMailer para enviar um e-mail HTML.
      $mail->isHTML(true);                        
      
      // Estas linhas estão definindo o assunto e o corpo do e-mail.
      $mail->Subject = 'Titulo do E-mail';
      $mail->Body = $content;
      $mail->AltBody = "Olá Aluno, Seu emprestimo de livro foi um sucesso.\nTexto da segunda linha.";

      // Esta linha está enviando o e-mail.
      $mail->send();
    
      // Exibe uma mensagem de sucesso.
      echo '<br><center><label class="badge badge-success" style="font-size:medium;">Comprovante enviado com sucesso!</label></center>';
        
        } catch (Exception $e) {

      // Caso haja algum erro ou excessão exibe uma mensagemd e erro.
      echo '<br><center><label class="badge badge-danger" style="font-size:medium;"> Erro ao enviar comprovante!</label></center>. Error PHPMailer: {$mail->ErrorInfo}';
    //echo "Erro: E-mail não enviado com sucesso.<br>";
    }
    }
}
        }
        ?>
   
<div class="card-body">
  <h4 class="card-title">Enviar comprovante</h4>

  <!-- Este é o início do formulário. O método POST é usado para enviar os dados do formulário e enviar_comprovante.php é o arquivo PHP que processará os dados do formulário. enctype="multipart/form-data" é necessário quando o formulário inclui qualquer controle <input type="file">, que é usado para o upload de arquivos. -->
  
    <form class="forms-sample" method="post" action="enviar_comprovante" enctype="multipart/form-data">
    
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="Email">
      </div>
      <div class="form-group">
        <label>Conteúdo</label>
        <input name="content" id="content" class="form-control" placeholder="Conteúdo" value="<?php echo isset($_POST['content']) ? $_POST['content'] : ''; ?>">
      </div>
          
      <div class="form-group">

      <!-- Estes são o rótulo e o campo de entrada para o anexo do e-mail. O tipo de entrada é file, o que significa que o navegador permitirá que o usuário selecione um arquivo. -->
        <label>Upload de arquivo</label>
        <input type="file" name="attachment" class="file-upload-default" id="attachment" onchange="updateFileName()">
      <div class="input-group col-xs-12">
        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload do arquivo" id="file-name">
          <span class="input-group-append">
            <button class="file-upload-browse btn btn-primary" type="button" onclick="document.getElementById('attachment').click()">Upload</button>
          </span>
      </div>
    </div>
          <button type="submit" name="Enviar" value="Enviar" class="btn btn-primary mr-2">Enviar</button>
          <button class="btn btn-dark">Cancelar</button>
    </form>
      </div>
    </div>
  </div>
</div>


  <?php
  include_once 'include/footer.php';
?>

<script>

  // A função updateFileName() é usada para atualizar o nome do arquivo que o usuário selecionou para upload
  function updateFileName() {

    // Esta linha obtém o elemento de entrada do arquivo com o id ‘attachment’ e armazena-o na variável fileInput.
    var fileInput = document.getElementById('attachment');
    // Esta linha obtém o elemento de entrada de texto com o id ‘file-name’ e armazena-o na variável fileNameInput.
    var fileNameInput = document.getElementById('file-name');
    // Esta linha define o valor do elemento de entrada de texto para o nome do arquivo que o usuário selecionou para upload. fileInput.files[0] representa o primeiro (e, neste caso, único) arquivo selecionado pelo usuário.
    fileNameInput.value = fileInput.files[0].name;
  }
</script>

  <!-- Define a aparencia dos links na página -->
  <style>
    a {
      text-decoration: none;
      color: white;
    }

    a:hover {
      color: white;
    }
  </style>


