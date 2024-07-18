<?php
ob_start();
include_once('include/header.php');
include_once('include/conexao.php');

?>

<!-- Div para nao ficar bugado colado no header -->
<div class="main-panel">
<div class="content-wrapper">
<div class="row">
<div class="col-12 grid-margin stretch-card">
<!-- fim div -->
<div class="card">
    <div class="card-body">
    
<?php

$id_usuario = $_SESSION['login'];
if(isset($_POST['Enviar'])) {

  $emprestimo = "Realizou empréstimo";
  $email = $_POST['email'];
  $cod_spn = $_POST['cod_spn'];
  $cod_spn2 = $_POST['cod_spn2'];
  $cod_spn3 = $_POST['cod_spn3'];
  $data_f = date('Y-m-d', strtotime("+21 days"));

  //Checa se o livro existe no banco
  $livro_query = "SELECT * FROM cad_livro WHERE cod_spn = '$cod_spn'";
  $roda_livro = $_SESSION['conexao']->query($livro_query);

  //Checa se a pessoa está cadastrada
  $usuario_query = "SELECT * FROM cad_usu WHERE email= '$email'";
  $roda_usu = $_SESSION['conexao']->query($usuario_query);


  // Verifica se o livro já está emprestado  
  $emp_query = "SELECT * FROM emprestimo WHERE cod_spn='$cod_spn' AND status = 1";
  $roda_emp = $_SESSION['conexao']->query($emp_query);

  if (mysqli_num_rows($roda_livro) == 0 && mysqli_num_rows($roda_usu) == 0) {
    $_SESSION['mensagem']='<br><center><label class="badge badge-warning" style="font-size:medium;"> O ' .$resp_sql_spn['nome_livro']. ' não existe ou o nome está incorreto</label></center>';
  
  } elseif (mysqli_num_rows($roda_usu) == 0) {
    $_SESSION['mensagem']= '<br><center><label class="badge badge-warning" style="font-size:medium;">O email está incorreto</label></center>';

  } elseif (mysqli_num_rows($roda_emp) > 0) {
    $_SESSION['mensagem']= '<br><center><label class="badge badge-danger" style="font-size:medium;">OPS! Esse livro já foi emprestado</label></center>';

  } 

  else {

 $sql_aluno = "SELECT nome_usuario, email, id_usu FROM cad_usu WHERE email = '$email'";
 $resp_aluno = mysqli_query($_SESSION['conexao'], $sql_aluno);
 $resp_sql_aluno = mysqli_fetch_array($resp_aluno, MYSQLI_ASSOC);

 $sql_spn = "SELECT nome_livro FROM cad_livro WHERE cod_spn= '$cod_spn' ";
 $resp_spn = mysqli_query($_SESSION['conexao'], $sql_spn);

 $sql_spn2 = "SELECT nome_livro FROM cad_livro WHERE cod_spn= '$cod_spn2'";
 $resp_spn2 = mysqli_query($_SESSION['conexao'], $sql_spn2);

 $sql_spn3 = "SELECT nome_livro FROM cad_livro WHERE cod_spn= '$cod_spn3'";
 $resp_spn3 = mysqli_query($_SESSION['conexao'], $sql_spn3);

    //Insere os dados do form na tabela de empréstimo


    if ($resp_spn && mysqli_num_rows($resp_spn) > 0) {
      $resp_sql_spn = mysqli_fetch_array($resp_spn, MYSQLI_ASSOC);
      // Restante do código relacionado a $resp_sql_spn
      $nome_livro1 = $resp_sql_spn['nome_livro'];
  }
  
  if ($resp_spn2 && mysqli_num_rows($resp_spn2) > 0) {
      $resp_sql_spn2 = mysqli_fetch_array($resp_spn2, MYSQLI_ASSOC);
      // Restante do código relacionado a $resp_sql_spn2
      $nome_livro2 = $resp_sql_spn2['nome_livro'];
      
  }else{
    $nome_livro2 = '';
  }
  
  if ($resp_spn3 && mysqli_num_rows($resp_spn3) > 0) {
      $resp_sql_spn3 = mysqli_fetch_array($resp_spn3, MYSQLI_ASSOC);
      // Restante do código relacionado a $resp_sql_spn3
      $nome_livro3 = $resp_sql_spn3['nome_livro'];

  }else{
    $nome_livro3 = '';
  }

    $insert = "INSERT INTO emprestimo( id_usu, nome_usuario,email,nome_livro,nome_livro2,nome_livro3,cod_spn, cod_spn2, cod_spn3, emprestimo,data_e,data_f) VALUES 
    ({$resp_sql_aluno['id_usu']}, '{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}',' $nome_livro1' ,' $nome_livro2',' $nome_livro3','$cod_spn','$cod_spn2','$cod_spn3','$emprestimo', NOW(),'$data_f')";
    $resp_sql = mysqli_query($_SESSION['conexao'], $insert);

    if ($resp_sql === TRUE) {

   // Insere os dados do form na tabela de relatório
      $insert2 = "INSERT INTO relatorio( nome_usuario,email,nome_livro,nome_livro2,nome_livro3,cod_spn, cod_spn2, cod_spn3, emprestimo,data_e,data_f) VALUES 
      ('{$resp_sql_aluno['nome_usuario']}', '{$resp_sql_aluno['email']}', '$nome_livro1','$nome_livro2','$nome_livro3','$cod_spn','$cod_spn2','$cod_spn3','$emprestimo', NOW(),'$data_f')";
      $resp_sql2 = mysqli_query($_SESSION['conexao'], $insert2);



        //Faz uma atualização do status do livro na tabela cad_livro o tornando indisponivel na lista principal
      $sql_up = "UPDATE cad_livro SET status= 0 WHERE cod_spn='$cod_spn'";
      $resp_sql = mysqli_query($_SESSION['conexao'], $sql_up);

        //Faz uma atualização do status do livro na tabela cad_livro o tornando indisponivel na lista principal
      $sql_up2 = "UPDATE cad_livro SET status= 0 WHERE cod_spn='$cod_spn2'";
      $resp_sql22 = mysqli_query($_SESSION['conexao'], $sql_up2);

        //Faz uma atualização do status do livro na tabela cad_livro o tornando indisponivel na lista principal
      $sql_up3 = "UPDATE cad_livro SET status= 0 WHERE cod_spn='$cod_spn3'";
      $resp_sql3 = mysqli_query($_SESSION['conexao'], $sql_up3);

      $_SESSION['mensagem'] = "Empréstimo realizado com sucesso! " . $nome_livro1;
      header('Location: confirmacao');
  
    } else {
      $_SESSION['mensagem']= '<br><center><label class="badge badge-danger" style="font-size:medium;">OPS! Houve um erro ao realizar o empréstimo!</label></center>';
    }
  }
}

if (isset($_SESSION['mensagem'])) {
  echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
  echo "<br><br><br>";
  unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};

ob_end_flush();
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

    <button type="submit" value="Enviar" name="Enviar" class="btn btn-primary mr-2">Cadastrar</button>
    <button class="btn btn-dark"><a href="index">Cancelar</button>
</form>
</div>
</div>
</div>

<?php
include_once('include/footer.php');
?>

<style>
  a{
    text-decoration: none;
    color: #fff
  }
</style>