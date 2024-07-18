<?php
//Inicia a conexão com uma sessão
session_start();
$_SESSION['conexao'] = mysqli_connect("localhost","root","","biblioteca_senai");

// Testa erros de conexão
if (mysqli_connect_errno()) {
  echo "Erro ao conectar ao banco: " . mysqli_connect_error();
  exit();
}
?>