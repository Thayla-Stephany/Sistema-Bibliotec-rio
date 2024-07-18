<?php
ob_start();

// Inclusão do arquivo de conexão com o banco e do cabeçalho.
include_once('include/header.php');
include_once('include/conexao.php');
?>

<!-- Div para nao ficar bugado colado no header -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">   
            <div class="col-12 grid-margin stretch-card">
<!-- Fim div -->
                <div class="card">
                    <div class="card-body">

<?php

// Condição recebendo um GET com o valor da variável 

if(isset($_GET['id_usu'])){

    $id_usu = $_GET['id_usu'];

    // SQL para buscar os valores correspondentes no banco de dados.
    $sql = "SELECT * FROM cad_usu WHERE id_usu = ".$id_usu;
    $rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
    $resp_sql = mysqli_fetch_array($rodar_sql, MYSQLI_ASSOC);

}else{

    // Variáveis recebendo novos valores via POST
    $id_usu = $_POST['id_usu'];
    $nome_usuario = $_POST['nome_usuario'];
    $email = $_POST['email'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];


    // SQL para atualizar os dados do usuário no banco de dados.
    $sql = "UPDATE cad_usu SET nome_usuario = '$nome_usuario', email = '$email', endereco = '$endereco', telefone = '$telefone' WHERE id_usu = $id_usu";
    $rodar_sql = mysqli_query($_SESSION['conexao'], $sql);

        if($rodar_sql === TRUE){

            // Mensagem de feedback caso os dados sejam modificados com sucesso.
            $_SESSION['mensagem_aluno'] = '<br><center><label class="badge badge-success" style="font-size:medium;">Dados atualizados</label></center>';
    
            // Redireciona para a página "lista_de_usuarios.php"
            header('Location: lista_de_usuarios');

        }else{

            //Mensagem de feedback caso a ataulização dos dados falhe.
            $_SESSION['mensagem_aluno'] = '<br><center><label class="badge badge-danger" style="font-size:medium;">Erro ao atualizar</label></center>';
        }

    // SQL que recupera os dados e exibi o novo valor
    $sql = "SELECT * FROM cad_usu WHERE id_usu = ".$id_usu;
    $rodar_sql = mysqli_query($_SESSION['conexao'], $sql);
    $resp_sql = mysqli_fetch_array($rodar_sql, MYSQLI_ASSOC);

    // Variável usado no input do formulário 
    $id_usu = $resp_sql['id_usu'];

}

?>

<h4 class="card-title">Editar usuário</h4> 
<form class="forms-sample" action="editar_usuarios" method="POST">
    <input type="hidden" name="id_usu" value="<?php echo $id_usu; ?>">
    <div class="form-group">
        <label>Nome</label>
            <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
            <input type="text" class="form-control" name="nome_usuario" value="<?php echo $resp_sql['nome_usuario']; ?>">
        </div>
        <div class="form-group">
            <label>E-mail</label>
                <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
                <input type="text" class="form-control"  name="email" value="<?php echo $resp_sql['email']; ?>">
        </div>
        <div class="form-group">
            <label>Endereço</label>
                <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
                <input type="text" class="form-control"  name="endereco" value="<?php echo $resp_sql['endereco']; ?>">
        </div><div class="form-group">
            <label>Telefone</label>
                <!-- O input retorna o valor diretamente do banco de dados, o exibindo -->
                <input type="text" class="form-control"  name="telefone" value="<?php echo $resp_sql['telefone']; ?>">
        </div>
            <button type="submit" value="Enviar" name="Enviar" class="btn btn-primary mr-2">Editar</button>
            <button class="btn btn-dark"><a href="lista_de_usuarios">Cancelar</button>
</form>
        </div>
    </div>
</div>

<?php
ob_end_flush();
include_once 'include/footer.php';
?>