<?php
$qtd_carrinho = 0;
if (isset($_SESSION['carrinho'])) {
    $qtd_carrinho = array_sum($_SESSION['carrinho']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MixTudo Variedades</title>
  <link rel="stylesheet" href="../../MixTudo.css">
</head>
<body>

<div class="menu-cliente">
  <div class="menu-cliente-inner">
    <a href="loja.php" class="menu-cliente-logo">MixTudo</a>
    <div class="menu-cliente-links">
      <a href="loja.php">Produtos</a>
      <a href="carrinho.php" class="carrinho-link">
        Carrinho
        <?php if ($qtd_carrinho > 0): ?>
          <span class="carrinho-badge"><?php echo $qtd_carrinho; ?></span>
        <?php endif; ?>
      </a>
      <span class="menu-cliente-user">Olá, <?php echo htmlspecialchars($_SESSION['cliente_nome']); ?></span>
      <a href="logout.php">Sair</a>
    </div>
  </div>
</div>
