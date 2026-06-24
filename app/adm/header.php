<?php
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : null;
$tipo_msg = isset($_SESSION['tipo_msg']) ? $_SESSION['tipo_msg'] : 'sucesso';
unset($_SESSION['mensagem'], $_SESSION['tipo_msg']);

$pa = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>MixTudo Variedades</title><link rel="stylesheet" href="../../MixTudo.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></head>
<body>
<div class="menu">
  <a href="../../home.html" style="background:#072a61"><i class="fas fa-store"></i> MixTudo</a>
  <a href="dashboard.php" class="<?php if ($pa=='dashboard.php') echo 'ativo' ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
  <a href="categorias.php" class="<?php if ($pa=='categorias.php') echo 'ativo' ?>"><i class="fas fa-tags"></i> Categorias</a>
  <a href="produtos.php" class="<?php if ($pa=='produtos.php') echo 'ativo' ?>"><i class="fas fa-box"></i> Produtos</a>
  <a href="clientes.php" class="<?php if ($pa=='clientes.php') echo 'ativo' ?>"><i class="fas fa-users"></i> Clientes</a>
  <a href="../../home.html" style="margin-left:auto"><i class="fas fa-external-link-alt"></i> Ir para Loja</a>
</div>
<?php if ($mensagem != '' && $mensagem != null) { ?><div class="msg <?php echo $tipo_msg ?>"><?php echo $mensagem ?></div><?php } ?>
