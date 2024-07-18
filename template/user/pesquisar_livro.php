<?php

// Inclui os arquivos de conexão, cabeçalho e funções
include_once 'include/header.php';
include_once 'include/conexao.php';
include_once 'include/funcao.php';

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
<!-- partial -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin stretch-card">

                <?php

                // Este bloco de código verifica se há uma mensagem armazenada na sessão. Se houver, ele exibe a mensagem e a remove da sessão.
                if (isset($_SESSION['mensagem'])) {
                    echo '<div class="mensagem">' . $_SESSION['mensagem'] . '</div>';
                    echo "<br><br><br>";
                    unset($_SESSION['mensagem']); // Limpa a variável da sessão para que a mensagem não seja exibida novamente na próxima vez que a página for carregada.
                };
                ?>
            </div>
        </div>

<!--Tabela da lista de livros -->
<?php

//  Estas linhas definem a quantidade de livros a serem exibidos por página e calculam o início da consulta SQL com base na página atual.
$quantidade = 3;
$pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
$inicio = ($quantidade * $pagina) - $quantidade;

echo '

<div class="card">
            <div class="card-body">
                <div class="space">
                    <div class="info-item">
                        <h4 class="card-title">Livros</h4>
                    </div>
                    <form method="post" action="pesquisar_livro">
                        <input type="hidden" name="id_usu" value="' . $_SESSION["login"] . '">
                        <center><br><button type="submit" class="btn btn-outline-warning m-2" name="enviar" value="enviar">Emprestimo</button></center>
                        <div class="form-group">
                            <label for="termo_pesquisa">Pesquisar:</label>
                            <input type="text" class="form-control" id="termo_pesquisa" name="termo_pesquisa">
                        </div>
                        <button type="submit" class="btn btn-primary" name="pesquisar">Pesquisar</button>
                    </form>
                </div>
';

echo '
<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Codigo SPN</th>
            <th>Código de barra</th>
            <th>Status</th>
            <th>Informações</th>
            <th>Selecionar</th>
        </tr>
    </thead>
    <tbody>
';

        // Este bloco de código verifica se a página foi acessada por um formulário POST (ou seja, se o usuário pesquisou um livro). Se foi, ele define a consulta SQL para buscar livros que correspondem ao termo de pesquisa. Se não foi, ele define a consulta SQL para buscar todos os livros. Em seguida, ele executa a consulta.

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pesquisar'])) {
            $termo_pesquisa = $_POST['termo_pesquisa'];

            // Consulta SQL para buscar livros que correspondem ao termo de pesquisa
            $sql = "SELECT * FROM cad_livro WHERE nome_livro LIKE '%$termo_pesquisa%' ORDER BY nome_livro ASC LIMIT $inicio,$quantidade";
        } else {
            // Consulta SQL padrão para exibir todos os livros
            $sql = "SELECT * FROM cad_livro ORDER BY nome_livro ASC LIMIT $inicio,$quantidade";
        }

        $resp_sql = mysqli_query($_SESSION['conexao'], $sql);

        //  Percorre cada linha do resultado da consulta SQL. Para cada livro, ele verifica o status do livro e gera uma linha de tabela HTML com as informações do livro e uma caixa de seleção para selecionar o livro.
        while ($linha = mysqli_fetch_assoc($resp_sql)) {
            if ($linha['status'] == 1) {
                $teste = '<label class="badge badge-success">Disponível</label>';
            } else {
                $teste = '<label class="badge badge-danger">Indisponível</label>';
            }

            echo '
    <tr>
        <td>' . $linha['nome_livro'] . '</td>
        <td>' . $linha['cod_spn'] . ' </td>
        <td>' . $linha['cod_barra'] . ' </td>
        <td class="' . $linha['status'] . '">' . $teste . '</td>
        <td><a href="inf_livro?id_livro=' . $linha['id_livro'] . '">Informações do livro</a></td>
        <td>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="selecionados[]" value="' . $linha['id_livro'] . '"> <i class="input-helper"></i>
                </label>
            </div>
        </td>
    </tr>
    ';
        }

        echo '
    </tbody>
</table>
</form>
</div>
';