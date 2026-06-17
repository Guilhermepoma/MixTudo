<?php
session_start();
require_once '../conexao.php';

$r = mysqli_query($conn, "SELECT * FROM vw_relatorios");
$d = mysqli_fetch_assoc($r);

$rc = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categoria");
$dc = mysqli_fetch_assoc($rc);

include 'header.php';
?>

<h1>Dashboard</h1>

<div class="flex">
  <div class="cartao">
    <div class="linha">
      <strong>Produtos cadastrados:</strong>
      <?php echo $d['quant_produtos'] ?>
    </div>
    <div class="linha">
      <strong>Clientes cadastrados:</strong>
      <?php echo $d['quant_clientes'] ?>
    </div>
  </div>

  <div class="cartao">
    <div class="linha">
      <strong>Itens em estoque:</strong>
      <?php echo $d['quant_estoque'] ? $d['quant_estoque'] : 0 ?>
    </div>
    <div class="linha">
      <strong>Categorias:</strong>
      <?php echo $dc['total'] ?>
    </div>
  </div>
</div>

</body></html>
