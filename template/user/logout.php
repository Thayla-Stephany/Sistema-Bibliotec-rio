<?php

// Inclui o arquivo de conexão com o banco de dados
include_once 'conexao.php';

// Responsável por realizar o logout do usuário no sistema e o redirecionar para a pagina de entrada
if (isset($_GET['logout']) == 'sair') {

    $_SESSION['nome_usuario'] = null;
    $_SESSION['senha'] = null;
    $_SESSION['conexao'] = null;
    session_destroy();

}
header('Location:../index');


?>