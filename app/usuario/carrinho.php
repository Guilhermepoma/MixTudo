<?php
session_start();
if (!isset($_SESSION['cliente_id'])) { header('Location: login.php'); exit; }
require_once '../conexao.php';

$id = isset($_GET['add']) ? (int)$_GET['add'] : 0;
if ($id) {
  if (!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = array();
  $_SESSION['carrinho'][$id] = isset($_SESSION['carrinho'][$id]) ? $_SESSION['carrinho'][$id]+1 : 1;
  $_SESSION['carrinho_msg'] = 'Produto adicionado ao carrinho!';
  header('Location: loja.php'); exit;
}
if (isset($_GET['remove'])) { unset($_SESSION['carrinho'][(int)$_GET['remove']]); header('Location: carrinho.php'); exit; }
if (isset($_GET['limpar'])) { unset($_SESSION['carrinho']); header('Location: carrinho.php'); exit; }
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['qtd'])) {
  foreach ($_POST['qtd'] as $id=>$qtd) { $qtd=(int)$qtd; if ($qtd>0) $_SESSION['carrinho'][(int)$id]=$qtd; else unset($_SESSION['carrinho'][(int)$id]); }
  header('Location: carrinho.php'); exit;
}

$itens = array(); $total = 0;
if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho'])>0) {
  $ids = implode(',', array_keys($_SESSION['carrinho']));
  $res = mysqli_query($conn, "SELECT * FROM produtos WHERE id IN ($ids)");
  while ($p = mysqli_fetch_assoc($res)) { $qtd=$_SESSION['carrinho'][$p['id']]; $p['quantidade_carrinho']=$qtd; $p['subtotal']=$p['preco']*$qtd; $total+=$p['subtotal']; $itens[]=$p; }
}

include 'header_cliente.php';
?>
<div style="max-width:900px;margin:0 auto">
  <h1>Carrinho</h1>
  <?php if (count($itens)==0): ?>
    <div class="cartao" style="padding:60px 20px;text-align:center"><div style="font-size:48px;margin-bottom:16px"><i class="fas fa-shopping-cart"></i></div><p style="font-size:18px;color:#666;margin-bottom:16px">Seu carrinho está vazio.</p><a href="loja.php" class="mix-btn" style="text-decoration:none;display:inline-block"><i class="fas fa-store"></i> Ver produtos</a></div>
  <?php else: ?>
    <form method="POST">
      <?php foreach ($itens as $item): ?>
        <div class="cartao" style="display:flex;align-items:center;gap:16px;padding:16px;margin-bottom:12px">
          <div style="width:60px;height:60px;border-radius:12px;background:linear-gradient(135deg,#1f3b73,#2c5aa0);display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:20px;flex-shrink:0"><?php echo strtoupper(substr($item['nome'],0,2)) ?></div>
          <div style="flex:1"><strong style="font-size:16px"><?php echo htmlspecialchars($item['nome']) ?></strong><p style="font-size:13px;color:#777">R$ <?php echo number_format($item['preco'],2,',','.') ?></p></div>
          <div style="display:flex;align-items:center;gap:8px"><input type="number" name="qtd[<?php echo $item['id'] ?>]" value="<?php echo $item['quantidade_carrinho'] ?>" min="0" style="width:60px;text-align:center;border:2px solid #111"><span style="font-weight:700;color:#1f3b73;min-width:80px;text-align:right">R$ <?php echo number_format($item['subtotal'],2,',','.') ?></span><a href="carrinho.php?remove=<?php echo $item['id'] ?>" style="color:#c62828;font-weight:700;font-size:18px;text-decoration:none;padding:4px 8px"><i class="fas fa-times"></i></a></div>
        </div>
      <?php endforeach; ?>
      <div class="cartao" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-top:16px"><strong style="font-size:22px">Total: R$ <?php echo number_format($total,2,',','.') ?></strong><div style="display:flex;gap:8px"><button type="submit" class="btn"><i class="fas fa-sync-alt"></i> Atualizar</button><a href="carrinho.php?limpar=1" class="btn btn-perigo"><i class="fas fa-trash-alt"></i> Limpar</a><a href="checkout.php" class="btn btn-sucesso"><i class="fas fa-check-circle"></i> Finalizar compra</a></div></div>
    </form>
  <?php endif; ?>
  <p style="margin-top:16px"><a href="loja.php"><i class="fas fa-arrow-left"></i> Continuar comprando</a></p>
</div>
</body></html>
