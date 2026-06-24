<?php
$qtd_carrinho = isset($_SESSION['carrinho']) ? array_sum($_SESSION['carrinho']) : 0;
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>MixTudo Variedades</title><link rel="stylesheet" href="../../MixTudo.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></head>
<body>
<div class="menu-cliente">
  <div class="menu-cliente-inner">
    <a href="loja.php" class="menu-cliente-logo"><i class="fas fa-store"></i> MixTudo</a>
    <div class="menu-cliente-links">
      <a href="../../home.html"><i class="fas fa-home"></i> Início</a>
      <a href="loja.php"><i class="fas fa-tag"></i> Produtos</a>
    </div>
    <div style="display:flex;align-items:center;gap:16px;margin-left:auto">
      <span style="color:rgba(255,255,255,0.9);font-size:14px;font-weight:600"><i class="fas fa-user"></i> Olá, <?php echo htmlspecialchars($_SESSION['cliente_nome']) ?></span>
      <a href="carrinho.php" style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:rgba(255,255,255,0.15);border-radius:20px;color:#fff;text-decoration:none;font-weight:600;font-size:14px;transition:0.2s" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'"><i class="fas fa-shopping-cart"></i> Carrinho<?php if ($qtd_carrinho>0): ?><span style="background:#fff;color:#0d47a1;padding:1px 7px;border-radius:50%;font-size:11px;font-weight:700"><?php echo $qtd_carrinho ?></span><?php endif; ?></a>
      <a href="logout.php" style="color:rgba(255,255,255,0.7);font-size:14px;text-decoration:none;padding:6px 10px;border-radius:8px;transition:0.2s" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'"><i class="fas fa-sign-out-alt"></i></a>
    </div>
  </div>
</div>
