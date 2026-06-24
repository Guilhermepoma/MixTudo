<?php
session_start();
require_once '../conexao.php';

$d = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vw_relatorios"));
$dc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categoria"));

include 'header.php';
?>
<h1>Dashboard</h1>
<div class="flex">
  <div class="cartao">
    <div class="linha"><i class="fas fa-box"></i> <strong>Produtos cadastrados:</strong> <?php echo $d['quant_produtos'] ?></div>
    <div class="linha"><i class="fas fa-users"></i> <strong>Clientes cadastrados:</strong> <?php echo $d['quant_clientes'] ?></div>
  </div>
  <div class="cartao">
    <div class="linha"><i class="fas fa-cubes"></i> <strong>Itens em estoque:</strong> <?php echo $d['quant_estoque'] ? $d['quant_estoque'] : 0 ?></div>
    <div class="linha"><i class="fas fa-tags"></i> <strong>Categorias:</strong> <?php echo $dc['total'] ?></div>
  </div>
</div>
</body></html>
