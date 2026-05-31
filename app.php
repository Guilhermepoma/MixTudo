<?php
session_start();

if (!isset($_SESSION['categorias'])) $_SESSION['categorias'] = [];
if (!isset($_SESSION['produtos'])) $_SESSION['produtos'] = [];
if (!isset($_SESSION['clientes'])) $_SESSION['clientes'] = [];
if (!isset($_SESSION['next'])) $_SESSION['next'] = 1;

$pagina = $_GET['pagina'] ?? 'inicio';

$mensagem = $_SESSION['mensagem'] ?? null;
$tipo_msg = $_SESSION['tipo_msg'] ?? 'sucesso';
unset($_SESSION['mensagem']);
unset($_SESSION['tipo_msg']);

// CATEGORIAS
if (isset($_POST['cat_nome'])) {
    $nome = trim($_POST['cat_nome']);
    $id = (int)($_POST['cat_id'] ?? 0);
    if ($nome == '') {
        $_SESSION['mensagem'] = 'O campo nome da categoria é obrigatório.';
        $_SESSION['tipo_msg'] = 'erro';
    } else {
        if ($id > 0) {
            foreach ($_SESSION['categorias'] as $k => $v) {
                if ($v['id'] == $id) {
                    $_SESSION['categorias'][$k]['nome'] = $nome;
                }
            }
            $_SESSION['mensagem'] = 'Categoria atualizada com sucesso!';
        } else {
            $_SESSION['categorias'][] = ['id' => $_SESSION['next'], 'nome' => $nome];
            $_SESSION['next']++;
            $_SESSION['mensagem'] = 'Categoria cadastrada com sucesso!';
        }
    }
    header('Location: app.php?pagina=categorias');
    exit;
}

if (isset($_GET['excluir_cat'])) {
    $id = (int)$_GET['excluir_cat'];
    foreach ($_SESSION['categorias'] as $k => $v) {
        if ($v['id'] == $id) {
            unset($_SESSION['categorias'][$k]);
        }
    }
    $_SESSION['categorias'] = array_values($_SESSION['categorias']);
    $_SESSION['mensagem'] = 'Categoria excluída!';
    header('Location: app.php?pagina=categorias');
    exit;
}

// PRODUTOS
if (isset($_POST['prod_nome'])) {
    $id = (int)($_POST['prod_id'] ?? 0);
    $nome = trim($_POST['prod_nome']);
    $preco = str_replace(',', '.', $_POST['prod_preco'] ?? '0');
    $preco = (float)$preco;
    $cat = $_POST['prod_cat'] ?? '';
    $est = (int)($_POST['prod_estoque'] ?? 0);

    $erros = [];
    if ($nome == '') $erros[] = 'Nome';
    if ($preco <= 0) $erros[] = 'Preço';

    if (count($erros) > 0) {
        $_SESSION['mensagem'] = 'Preencha corretamente: ' . implode(', ', $erros) . '.';
        $_SESSION['tipo_msg'] = 'erro';
    } else {
        if ($id > 0) {
            foreach ($_SESSION['produtos'] as $k => $v) {
                if ($v['id'] == $id) {
                    $_SESSION['produtos'][$k]['nome'] = $nome;
                    $_SESSION['produtos'][$k]['preco'] = $preco;
                    $_SESSION['produtos'][$k]['categoria'] = $cat;
                    $_SESSION['produtos'][$k]['estoque'] = $est;
                }
            }
            $_SESSION['mensagem'] = 'Produto atualizado!';
        } else {
            $_SESSION['produtos'][] = [
                'id' => $_SESSION['next'],
                'nome' => $nome,
                'preco' => $preco,
                'categoria' => $cat,
                'estoque' => $est
            ];
            $_SESSION['next']++;
            $_SESSION['mensagem'] = 'Produto cadastrado!';
        }
    }
    header('Location: app.php?pagina=produtos');
    exit;
}

if (isset($_GET['excluir_prod'])) {
    $id = (int)$_GET['excluir_prod'];
    foreach ($_SESSION['produtos'] as $k => $v) {
        if ($v['id'] == $id) unset($_SESSION['produtos'][$k]);
    }
    $_SESSION['produtos'] = array_values($_SESSION['produtos']);
    $_SESSION['mensagem'] = 'Produto excluído!';
    header('Location: app.php?pagina=produtos');
    exit;
}

// CLIENTES
if (isset($_POST['cli_nome'])) {
    $id = (int)($_POST['cli_id'] ?? 0);
    $nome = trim($_POST['cli_nome']);
    $fone = trim($_POST['cli_telefone'] ?? '');

    if ($nome == '') {
        $_SESSION['mensagem'] = 'O campo nome do cliente é obrigatório.';
        $_SESSION['tipo_msg'] = 'erro';
    } else {
        if ($id > 0) {
            foreach ($_SESSION['clientes'] as $k => $v) {
                if ($v['id'] == $id) {
                    $_SESSION['clientes'][$k]['nome'] = $nome;
                    $_SESSION['clientes'][$k]['telefone'] = $fone;
                }
            }
            $_SESSION['mensagem'] = 'Cliente atualizado!';
        } else {
            $_SESSION['clientes'][] = ['id' => $_SESSION['next'], 'nome' => $nome, 'telefone' => $fone];
            $_SESSION['next']++;
            $_SESSION['mensagem'] = 'Cliente cadastrado!';
        }
    }
    header('Location: app.php?pagina=clientes');
    exit;
}

if (isset($_GET['excluir_cli'])) {
    $id = (int)$_GET['excluir_cli'];
    foreach ($_SESSION['clientes'] as $k => $v) {
        if ($v['id'] == $id) unset($_SESSION['clientes'][$k]);
    }
    $_SESSION['clientes'] = array_values($_SESSION['clientes']);
    $_SESSION['mensagem'] = 'Cliente excluído!';
    header('Location: app.php?pagina=clientes');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>MixTudo</title>
  <link rel="stylesheet" href="MixTudo.css">
</head>
<body>

<div class="menu">
  <a href="app.php" class="<?php if ($pagina == 'inicio') echo 'ativo' ?>">Início</a>
  <a href="app.php?pagina=categorias" class="<?php if ($pagina == 'categorias') echo 'ativo' ?>">Categorias</a>
  <a href="app.php?pagina=produtos" class="<?php if ($pagina == 'produtos') echo 'ativo' ?>">Produtos</a>
  <a href="app.php?pagina=clientes" class="<?php if ($pagina == 'clientes') echo 'ativo' ?>">Clientes</a>
  <a href="index.html">Sair</a>
</div>

<?php if ($mensagem != '' && $mensagem != null) { ?>
  <div class="msg <?php echo $tipo_msg ?>"><?php echo $mensagem ?></div>
<?php } ?>

<?php
if ($pagina == 'categorias') {
    include 'paginas/categorias.php';
} elseif ($pagina == 'produtos') {
    include 'paginas/produtos.php';
} elseif ($pagina == 'clientes') {
    include 'paginas/clientes.php';
} else {
    include 'paginas/dashboard.php';
}
?>

</body>
</html>
