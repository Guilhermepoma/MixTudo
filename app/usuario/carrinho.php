<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../conexao.php';

// Adicionar item
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = array();
    }
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]++;
    } else {
        $_SESSION['carrinho'][$id] = 1;
    }
    $_SESSION['carrinho_msg'] = 'Produto adicionado ao carrinho!';
    header('Location: loja.php');
    exit;
}

// Remover item
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['carrinho'][$id]);
    header('Location: carrinho.php');
    exit;
}

// Limpar carrinho
if (isset($_GET['limpar'])) {
    unset($_SESSION['carrinho']);
    header('Location: carrinho.php');
    exit;
}

// Atualizar quantidades
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qtd'])) {
    foreach ($_POST['qtd'] as $id => $qtd) {
        $qtd = (int)$qtd;
        if ($qtd > 0) {
            $_SESSION['carrinho'][(int)$id] = $qtd;
        } else {
            unset($_SESSION['carrinho'][(int)$id]);
        }
    }
    header('Location: carrinho.php');
    exit;
}

$itens = array();
$total = 0;

if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho']) > 0) {
    $ids = array_keys($_SESSION['carrinho']);
    $ids_str = implode(',', $ids);
    $res = mysqli_query($conn, "SELECT * FROM produtos WHERE id IN ($ids_str)");
    while ($p = mysqli_fetch_assoc($res)) {
        $qtd = $_SESSION['carrinho'][$p['id']];
        $p['quantidade_carrinho'] = $qtd;
        $p['subtotal'] = $p['preco'] * $qtd;
        $total += $p['subtotal'];
        $itens[] = $p;
    }
}

include 'header_cliente.php';
?>

<div style="max-width:900px;margin:0 auto">
  <h1>Carrinho</h1>

  <?php if (count($itens) == 0): ?>
    <div class="cartao">
      <p class="vazio">Seu carrinho está vazio.</p>
      <p style="text-align:center"><a href="loja.php" class="btn">Ver produtos</a></p>
    </div>
  <?php else: ?>
    <form method="POST">
      <table>
        <tr>
          <th>Produto</th>
          <th>Preço</th>
          <th>Quantidade</th>
          <th>Subtotal</th>
          <th>Ações</th>
        </tr>
        <?php foreach ($itens as $item): ?>
          <tr>
            <td><?php echo htmlspecialchars($item['nome']); ?></td>
            <td>R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></td>
            <td>
              <input type="number" name="qtd[<?php echo $item['id']; ?>]" value="<?php echo $item['quantidade_carrinho']; ?>" min="0" style="width:70px">
            </td>
            <td>R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?></td>
            <td>
              <a href="carrinho.php?remove=<?php echo $item['id']; ?>" class="btn btn-perigo btn-peq">Remover</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>

      <div class="cartao" style="margin-top:20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap">
        <div>
          <strong style="font-size:22px">Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
        </div>
        <div style="display:flex;gap:8px">
          <button type="submit" class="btn">Atualizar</button>
          <a href="carrinho.php?limpar=1" class="btn btn-perigo">Limpar</a>
          <a href="checkout.php" class="btn btn-sucesso">Finalizar compra</a>
        </div>
      </div>
    </form>
  <?php endif; ?>

  <p style="margin-top:16px"><a href="loja.php">&larr; Continuar comprando</a></p>
</div>

</body></html>
