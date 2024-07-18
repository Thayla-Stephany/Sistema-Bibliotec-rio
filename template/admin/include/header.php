<?php
include_once 'include/conexao.php';

date_default_timezone_set('America/Sao_Paulo');

$count_sql = "SELECT COUNT(*) AS total FROM emprestimo WHERE status = 2";
$count_result = mysqli_query($_SESSION['conexao'], $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$totalLivrosAtrasados = $count_row['total'];

if(isset($_SESSION['login'])){
  $id_adm = $_SESSION['login_adm'];

  
$sql_inf = "SELECT nome_usuario FROM cad_usu WHERE id_usu = $id_adm";
$resp_inf = mysqli_query($_SESSION['conexao'], $sql_inf);
$count_inf = mysqli_fetch_assoc($resp_inf);


}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Biblioteca</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="../assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="../assets/images/favicon.png" />
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
          <a class="sidebar-brand brand-logo" href="index" style="color:#fff;">BIBLIOTECA</a>
          <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="../assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              <div class="profile-pic">
            
              </div>
            </div>
          </li>
          <li class="nav-item nav-category">
            <span class="nav-link">Menu</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="index">
              <span class="menu-icon">
                <i class="mdi mdi-home"></i>
              </span>
              <span class="menu-title">Principal</span>
            </a>
          </li>

          <li class="nav-item menu-items">
            <a class="nav-link" href="emprestimo_livro">
              <span class="menu-icon">
                <i class="mdi mdi-clipboard-check"></i>
              </span>
              <span class="menu-title">Empréstimo</span>
            </a>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-account-multiple-plus"></i>
              </span>
              <span class="menu-title">Cadastros</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="./cadastrar_livros">Cadastro de livros</a></li>
                <li class="nav-item"> <a class="nav-link" href="./cadastrar_usuarios">Cadastro de usuário</a></li>
              </ul>
            </div>
          </li>

          <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-format-list-bulleted  "></i>
              </span>
              <span class="menu-title">Listas</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic3">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="lista_de_usuarios">Lista de usuários</a></li>
                <li class="nav-item"> <a class="nav-link" href="lista_livro_emprestado">Livro emprestado</a></li>
              </ul>
            </div>
          </li>


          <li class="nav-item menu-items">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic4" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-icon">
                <i class="mdi mdi-folder-multiple-image"></i>
              </span>
              <span class="menu-title">Relatórios</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic4">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="relatorio_do_sistema">Relatório do Sistema</a></li>
                <li class="nav-item"> <a class="nav-link" href="relatorio_de_gerenciamento">Relatório de gerenciamento</a></li>
              </ul>
            </div>
          </li>


          <li class="nav-item nav-category">
            <span class="nav-link">Backup</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="backup">
              <span class="menu-icon">
                <i class="mdi mdi-sync"></i>
              </span>
              <span class="menu-title">Backup</span>
            </a>
          </li>

          <li class="nav-item nav-category">
            <span class="nav-link">Logout</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="logout?logout='sair'">
              <span class="menu-icon">
                <i class="mdi mdi-logout"></i>
              </span>
              <span class="menu-title">Logout</span>
            </a>
          </li>

          <li class="nav-item nav-category">
            <span class="nav-link">Ajuda</span>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="#">
              <span class="menu-icon">
                <i class="mdi mdi-phone"></i>
              </span>
              <span class="menu-title">Tel:</span>
            </a>
          </li>
          
        </ul>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar p-0 fixed-top d-flex flex-row">
          <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
            <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../assets/images/logo-mini.svg" alt="logo" /></a>
          </div>
          <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
              <span class="mdi mdi-menu"></span>
            </button>
            <ul class="navbar-nav w-100">
             
            </ul>
            <ul class="navbar-nav navbar-nav-right">
              


              

              <?php 
$sql = "SELECT * FROM emprestimo WHERE status = 2";
$result = mysqli_query($_SESSION['conexao'], $sql);
$totalLivrosAtrasados = mysqli_num_rows($result);

if ($totalLivrosAtrasados > 0) {
  echo '
  <li class="nav-item dropdown border-left">
    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
      <i class="mdi mdi-bell"></i>
      <span class="count bg-danger"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
      <h6 class="p-3 mb-0">Notificações</h6>
      <div class="dropdown-divider"></div>';

  // Display the notifications for each overdue book
  while ($row = mysqli_fetch_assoc($result)) {
    
  
$nomeLivro = $row['nome_livro'];

    echo '
    <a class="dropdown-item preview-item">
      <div class="preview-thumbnail">
        <div class="preview-icon bg-dark rounded-circle">
          <i class="mdi mdi-calendar text-danger"></i>
        </div>
      </div>
      <div class="preview-item-content">
        <p class="preview-subject mb-1">Livro atrasado</p>
        <p class="text-muted ellipsis mb-0">' . $row['nome_usuario'] . ' está com o livro "' . $nomeLivro . '" em atraso.</p>
      </div>
    </a>';
  }

  echo '</div></li>';
} else {
  echo '
  <li class="nav-item dropdown border-left">
    <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
      <i class="mdi mdi-bell"></i>
      <span class="count"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
      <h6 class="p-3 mb-0">Notificações</h6>
      <div class="dropdown-divider"></div>
      <a class="dropdown-item preview-item">
        <div class="preview-thumbnail">
          <div >
            <i class="fa fa-bell notification-icon"></i>
          </div>
        </div>
        <div class="preview-item-content">
          <p class="preview-subject mb-1">Sem notificações</p>
          <p class="text-muted ellipsis mb-0">Não há livros atrasados no momento.</p>
        </div>
      </a>
    </div>
  </li>';
}

                 ?>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                  <div class="navbar-profile">
           
                    <p class="mb-0 d-none d-sm-block navbar-profile-name"><?= $count_inf['nome_usuario']?></p>
                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
 
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item preview-item" href="logout?logout='sair'">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-dark rounded-circle">
                        <i class="mdi mdi-logout text-danger"></i>
                      </div>
                    </div>
                    <div class="preview-item-content">
                      <p class="preview-subject mb-1">Log out</p>
                    </div>
                  </a>
              
              </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
              <span class="mdi mdi-format-line-spacing"></span>
            </button>
          </div>
        </nav>
