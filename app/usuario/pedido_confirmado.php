<?php
session_start();
if (!isset($_SESSION['cliente_id'])) { header('Location: login.php'); exit; }
require_once '../conexao.php';

$id_venda = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$id_cliente = (int)$_SESSION['cliente_id'];
$v = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM vendas WHERE id_venda=$id_venda AND id_clientes=$id_cliente"));
if (!$v) { header('Location: loja.php'); exit; }
$itens = mysqli_query($conn, "SELECT iv.*, p.nome FROM itens_venda iv JOIN produtos p ON p.id=iv.id_produto WHERE iv.id_venda=$id_venda");
$cliente = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM clientes WHERE id_clientes=$id_cliente"));

include 'header_cliente.php';
?>
<div style="max-width:800px;margin:0 auto;text-align:center">
  <div class="cartao" style="border:3px solid #2e7d32;padding:40px">
    <div style="font-size:48px;margin-bottom:16px"><i class="fas fa-check-circle" style="color:#2e7d32"></i></div>
    <h1 style="color:#2e7d32;font-size:28px">Pedido Confirmado!</h1>
    <p style="font-size:18px;margin:16px 0">Obrigado por comprar no MixTudo!</p>
    <p style="font-size:14px;color:#666">Nº: <strong>#<?php echo $id_venda ?></strong> &middot; Data: <?php echo date('d/m/Y', strtotime($v['data_venda'])) ?></p>
  </div>
  <div class="cartao" style="text-align:left;margin-top:16px">
    <h3>Detalhes do pedido</h3>
    <div class="linha"><strong>Cliente:</strong> <?php echo htmlspecialchars($cliente['nome']) ?></div>
    <div class="linha"><strong>Endereço:</strong> <?php echo htmlspecialchars($cliente['endereco']) ?></div>
    <div class="linha"><strong>Pagamento:</strong> <?php echo htmlspecialchars($cliente['forma_pagamento']) ?></div>
  </div>
  <div class="cartao" style="text-align:left">
    <h3>Itens</h3>
    <table><tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr>
    <?php while ($item = mysqli_fetch_assoc($itens)): ?><tr><td><?php echo htmlspecialchars($item['nome']) ?></td><td><?php echo (int)$item['quantidade'] ?></td><td>R$ <?php echo number_format($item['preco_unitario'],2,',','.') ?></td><td>R$ <?php echo number_format($item['preco_unitario']*$item['quantidade'],2,',','.') ?></td></tr><?php endwhile; ?>
    </table>
    <p style="text-align:right;font-size:20px;font-weight:700;margin-top:16px">Total: R$ <?php echo number_format($v['total'],2,',','.') ?></p>
  </div>
  <p style="margin-top:24px"><a href="loja.php" class="btn">Continuar comprando</a></p>
</div>
</body></html>
