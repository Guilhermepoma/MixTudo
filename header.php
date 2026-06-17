<?php
// pega mensagem da sessao e ja apaga
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null;
$tipo_msg = isset($_SESSION['tipo_msg']) ? $_SESSION['tipo_msg'] : 'sucesso';
unset($_SESSION['mensagem']);
unset($_SESSION['tipo_msg']);

$pagina_atual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>MixTudo Variedades</title>
  <link rel="stylesheet" href="MixTudo.css">
</head>
<body>

<div class="menu">
  <a href="dashboard.php" class="<?php if ($pagina_atual == 'dashboard.php') echo 'ativo' ?>">Início</a>
  <a href="categorias.php" class="<?php if ($pagina_atual == 'categorias.php') echo 'ativo' ?>">Categorias</a>
  <a href="produtos.php" class="<?php if ($pagina_atual == 'produtos.php') echo 'ativo' ?>">Produtos</a>
  <a href="clientes.php" class="<?php if ($pagina_atual == 'clientes.php') echo 'ativo' ?>">Clientes</a>
  <a href="index.html">Sair</a>
</div>

<?php if ($mensagem != '' && $mensagem != null) { ?>
  <div class="msg <?php echo $tipo_msg ?>"><?php echo $mensagem ?></div>
<?php } ?>
