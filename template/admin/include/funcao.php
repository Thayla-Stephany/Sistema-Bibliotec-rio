<?php

function livroTotal(){
    //TOTAL
    $sql="SELECT COUNT(id_livro) FROM cad_livro";
    $roda_sql=mysqli_query($_SESSION['conexao'],$sql);
    $sql_total=mysqli_fetch_array($roda_sql,MYSQLI_ASSOC);
    return $sql_total['COUNT(id_livro)'];
  }

  function livroempr(){
    //TOTAL
    $sql="SELECT COUNT(id_opcao) FROM emprestimo WHERE status = 1";
    $roda_sql=mysqli_query($_SESSION['conexao'],$sql);
    $sql_total=mysqli_fetch_array($roda_sql,MYSQLI_ASSOC);
    return $sql_total['COUNT(id_opcao)'];
  }

  function livroatra(){
    //TOTAL
    $sql="SELECT COUNT(id_opcao) FROM emprestimo WHERE status = 2";
    $roda_sql=mysqli_query($_SESSION['conexao'],$sql);
    $sql_total=mysqli_fetch_array($roda_sql,MYSQLI_ASSOC);
    return $sql_total['COUNT(id_opcao)'];
  }

  function usutotal(){
    //TOTAL
    $sql="SELECT COUNT(id_usu) FROM cad_usu";
    $roda_sql=mysqli_query($_SESSION['conexao'],$sql);
    $sql_total=mysqli_fetch_array($roda_sql,MYSQLI_ASSOC);
    return $sql_total['COUNT(id_usu)'];
  }
  

?>