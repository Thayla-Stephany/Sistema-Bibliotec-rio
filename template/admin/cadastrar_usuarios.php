<?php
// Incluí os arquivos de conexão e cabeçalho
include_once('include/header.php');
include_once('include/conexao.php');

?>

<!-- Div para não ficar colado no header -->
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-12 grid-margin stretch-card">
<!-- fim div -->
<div class="card">
    <div class="card-body">
       

<?php
// Condição para saber se o botão enviar veio com um valor dirente de vazio.
if (isset($_POST['Enviar']) && $_POST['Enviar'] != '') {

// Condição para saber se os campos usuario, email e senha vieram preenchidos.

  if(empty($_POST['nome_usuario']) || empty($_POST['email']) || empty($_POST['senha'])){
    
    $_SESSION['mensagem'] = '<br><center><label class="badge badge-warning" style="font-size:medium;">Preencha os campos!</label></center>';

  }else{

  // Váriaveis recebidas via POST
  $nome_usuario = $_POST['nome_usuario'];
  $email = $_POST['email'];
  $senha = $_POST['senha'];
  $endereco = $_POST['endereco'];
  $telefone = $_POST['telefone'];

    // SQL para verificar se o email ja está cadastrado no banco de dados.
    $sql = "SELECT * FROM cad_usu WHERE email = '$email'";
    $resp = mysqli_query($_SESSION['conexao'], $sql);
    $dados = mysqli_num_rows($resp);

    if ($dados > 0) {

      // Mensagem de feedback caso o email ja exista no banco.
      $_SESSION['mensagem']= '<br><center><label class="badge badge-warning" style="font-size:medium;">O e-mail ' . $email . ' já exite, tente novamente</label></center>';
    
    } else {

      //SQL para inserir os dados do novo usário no banco de dados.
      $sql = "INSERT INTO cad_usu (nome_usuario,email,senha, endereco, telefone) VALUES ('$nome_usuario','$email', '$senha', '$endereco', '$telefone')";
      $resp_sql = mysqli_query($_SESSION['conexao'], $sql);

      if ($resp_sql === TRUE) {

        // Mensagem de feedback caso a ação seja executada.
        $_SESSION['mensagem']='<br><center><label class="badge badge-success" style="font-size:medium;">Cadastro realizado</label></center>';
      
      } else {

        // Mensagem de feedback caso haja um erro de execução.
        $_SESSION['mensagem']='<br><center><label class="badge badge-danger" style="font-size:medium;">Erro ao cadastrar</label></center>';
      
      }
    }
  } 
}

// Mensagem de sessão
if (isset($_SESSION['mensagem'])) {
    echo "<br>";
    echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
    unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
  };

?>

<h4 class="card-title">Cadastro de usuário</h4> 

<form class="forms-sample" action="cadastrar_usuarios" method="POST">
    <div class="form-group">
    <label>Nome</label>
    <input type="text" class="form-control" name="nome_usuario" >
    </div>
    <div class="form-group">
    <label>E-mail</label>
    <input type="text" class="form-control"  name="email">
    </div>
    <div class="form-group">
    <label>Senha</label>
    <input type="password" class="form-control"  name="senha">
    </div><div class="form-group">
    <label>Endereço</label>
    <input type="text" class="form-control"  name="endereco">
    </div><div class="form-group">
    <label>Telefone</label>
    <input type="text" class="form-control"  name="telefone">
    </div>
    <button type="submit" value="Enviar" name="Enviar" class="btn btn-primary mr-2">Cadastrar</button>
    <button class="btn btn-dark"><a href="index">Cancel</button>
</form>
</div>
</div>
</div>


<?php
include_once 'include/footer.php';
?>

<!-- Script para controlar o tempo de exibição da mensagem de sessão -->

<script>
      // Atrasar a remoção da mensagem por 5 segundos
      setTimeout(function() {
            var mensagem = document.querySelector('.mensagem');
            if (mensagem) {
                mensagem.remove(); // Remover o elemento que contém a mensagem
            }
        }, 5000); // 5000 milissegundos = 5 segundos
</script>