<?php

// Inclusão do arquivo de conexão com o banco e do cabeçalho.
include_once('include/header.php');
include_once('include/conexao.php');

?>
<!-- Div do card -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row justify-content-center">
      <div class="col-7 grid-margin stretch-card">
<!-- fim div -->
<div class="card">
  <div class="card-body">

<head>
    
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

</head>

<?php

// Mensagem de sessão
 if (isset($_SESSION['mensagem_aluno'])) {
  echo '<div class="mensagem">' . $_SESSION['mensagem_aluno'] . '</div>';
  unset($_SESSION['mensagem_aluno']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
};

// Condição que recupera o valor da variável "id_text" via GET 
if(isset($_GET['id_tex'])){

	$id_tex = $_GET['id_tex'];

  // SQL responsável por buscar os valores de id_text no banco 
	$sql = "SELECT * FROM relatorio_texto WHERE id_tex = ".$id_tex;
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	$resp_sql = mysqli_fetch_array($rodar_sql, MYSQLI_ASSOC);

}else{

  // Variáveis recuperando o novo valor inserido via POST
  $id_tex = $_POST['id_tex'];
  $texto = $_POST['texto'];
	
  // SQL que faz a alteração dos dados na tabela "relatório_texto"
	$sql = "UPDATE relatorio_texto SET texto = '$texto' WHERE id_tex = $id_tex";
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	
  if($rodar_sql === TRUE){ 

        // Mensagem de feedback caso os dados sejam modificados com sucesso.
    $_SESSION['mensagem'] =  '<br><center><label class="badge badge-success" style="font-size:medium;">Dados atualizados</label></center>';
  }else{

        // Mensagem de feedback caso a atualização dos dados falhe.
    $_SESSION['mensagem'] =  '<br><center><label class="badge badge-danger" style="font-size:medium;">Erro ao atualizar</label></center>';
  }
	

  // SQL que recupera os dados e exibi o novo valor
	$sql = "SELECT * FROM relatorio_texto WHERE id_tex = ".$id_tex;
	$rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
	$resp_sql = mysqli_fetch_array($rodar_sql, MYSQLI_ASSOC);
	
  // Variável usado no input do formulário 
	$id_tex = $resp_sql['id_tex'];
	
}

?>


<br><br>
<!--<center><h3 class="welcome-text" style="color: #a8b3ab;">Editar <span class="text-primary fw-bold">Relatório</span></h3></center>-->

<h3 class="welcome-text" style= "color:'#000';text-align:center;">Relatório Semanal</h3>
    <div class="col-10 grid-margin stretch-card mx-auto">
      <div class="card">
        <div class="card-body"><br><br>
      <form class="forms-sample" action="editar_relatorio" method="post">
          <input type="hidden" name="id_tex" value="<?php echo $id_tex; ?>">
            <!-- Retorna dentro de uma caixa de texto os valores inseridos no banco -->
            <center><textarea name="texto" id="" class="col-12" cols="100" rows="10" placeholder="Escreva aqui seu relatório..." maxlength="100000000"> <?php echo $resp_sql['texto'];?></textarea><br><br>
          <button type="submit" class="btn btn-primary me-5" value="Enviar" style="position:relative;left:5%;">Atualizar</button></center>
      </form>
         </div>
        </div>
      </div>
     </div>
    </div>
  </div>
</div>       

  <style>
    a {
      text-decoration: none;
      color: white;
    }

    a:hover {
      color: white;
    }
  </style>

<?php

// Inclusão do arquivo de rodapé da página.
include_once 'include/footer.php';

?>
