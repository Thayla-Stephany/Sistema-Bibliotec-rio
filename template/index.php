<?php
ob_start();

include_once 'conexao.php';

if (isset($_POST['enviar'])) {
    // Obter as informações de login do formulário
    $nome_usuario = $_POST['nome_usuario'];
    $senha = $_POST['senha'];

    // Preparar a consulta SQL com prepared statements
    $query = "SELECT * FROM cad_usu WHERE nome_usuario = ? AND senha = ?";
    $stmt = mysqli_prepare($_SESSION['conexao'], $query);
    mysqli_stmt_bind_param($stmt, 'ss', $nome_usuario, $senha);
    mysqli_stmt_execute($stmt);

    // Obter o resultado da consulta
    $result = mysqli_stmt_get_result($stmt);

    // Verificar se as informações de login estão corretas
    if (mysqli_num_rows($result) == 1) {
        $usuario = mysqli_fetch_assoc($result);

        // Verificar o tipo de conta do usuário e redirecionar para a página apropriada
        if ($usuario['tipo_usuario'] == 'usuario') {
            $_SESSION['login'] = $usuario['id_usu'];
            header('Location: user/index');
            exit;
        } else if ($usuario['tipo_usuario'] == 'administrador') {
            $_SESSION['login_adm'] = $usuario['id_usu'];
            header('Location: admin/index');
            exit;
        }
    } else {
        // As informações de login estão incorretas
        $_SESSION['mensagem'] = '<center><div class="p-2 mb-2 bg-primary text-white">Nome de usuário ou senha incorretos.</div></center>';
    }

    // Fechar a conexão com o banco de dados
    mysqli_close($_SESSION['conexao']);
}

// Mensagem de sessão
if (isset($_SESSION['mensagem'])) {
    echo "<br>";
    echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
    unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
  };

ob_end_flush();
?>

<!-- Login -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corona Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper d-flex align-items-center auth">
        <div class="row w-100 m-0">
          <!--<div class="content-wrapper full-page-wrapper d-flex align-items-center auth ">-->
            <div class="card col-lg-4 mx-auto">
              <div class="card-body px-5 py-5">
                <h3 class="card-title text-left mb-3">Login</h3>
                <form action="index" method="post">
                  <div class="form-group">
                    <label>Nome </label>
                    <input type="text" class="form-control p_input" name="nome_usuario">
                  </div>
                  <div class="form-group">
                    <label>Senha </label>
                    <input type="password" class="form-control p_input" name="senha">
                  </div>
                  <div class="text-center">
                    <button type="submit" name="enviar" value="enviar" class="btn btn-primary btn-block enter-btn">Login</button>
                  </div>
               
                </form>
              </div>
            </div>
         <!--  </div>
          content-wrapper ends -->
        </div>
        <!-- row ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="../../assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="../../assets/js/off-canvas.js"></script>
    <script src="../../assets/js/hoverable-collapse.js"></script>
    <script src="../../assets/js/misc.js"></script>
    <script src="../../assets/js/settings.js"></script>
    <script src="../../assets/js/todolist.js"></script>
    <!-- endinject -->
  </body>
</html>
<!-- Fim login -->

<!-- Estilo da imagem de fundo -->
<style>
  .container-scroller {
  background-image: url('Login.jpg');
  background-size: cover;
  background-position: center;
}

</style>