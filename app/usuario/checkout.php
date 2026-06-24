<?php
session_start();
if (!isset($_SESSION['cliente_id'])) { header('Location: login.php'); exit; }
require_once '../conexao.php';

$id_cliente = (int)$_SESSION['cliente_id'];
$cliente = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM clientes WHERE id_clientes = $id_cliente"));
$itens = array(); $total = 0; $carrinho_valido = false;
if (isset($_SESSION['carrinho']) && count($_SESSION['carrinho'])>0) {
  $ids = implode(',', array_keys($_SESSION['carrinho']));
  $res = mysqli_query($conn, "SELECT p.*, e.quantidade AS estoque_atual FROM produtos p LEFT JOIN estoque e ON e.id_produto = p.id WHERE p.id IN ($ids)");
  while ($p = mysqli_fetch_assoc($res)) { $qtd = $_SESSION['carrinho'][$p['id']]; $p['qtd']=$qtd; $p['subtotal']=$p['preco']*$qtd; $total+=$p['subtotal']; $itens[]=$p; }
  $carrinho_valido = true;
}

$pedido_feito = false; $id_venda = 0;
if ($_SERVER['REQUEST_METHOD']==='POST' && $carrinho_valido) {
  foreach ($itens as $item) { if ($item['qtd'] > $item['estoque_atual']) { $erro_msg='Estoque insuficiente: '.$item['nome']; break; } }
  if (!isset($erro_msg)) {
    $nome = mysqli_real_escape_string($conn, trim($_POST['cli_nome']));
    $fone = mysqli_real_escape_string($conn, trim($_POST['cli_telefone']));
    $end = mysqli_real_escape_string($conn, trim($_POST['cli_endereco']));
    $pag = mysqli_real_escape_string($conn, trim($_POST['cli_pagamento']));
    if ($nome!='') {
      $s=mysqli_prepare($conn,"UPDATE clientes SET nome=?,telefone=?,endereco=?,forma_pagamento=? WHERE id_clientes=?");
      mysqli_stmt_bind_param($s,'ssssi',$nome,$fone,$end,$pag,$id_cliente); mysqli_stmt_execute($s);
      $cliente['nome']=$nome; $cliente['telefone']=$fone; $cliente['endereco']=$end; $cliente['forma_pagamento']=$pag;
    }
    $total_db = number_format($total,2,'.','');
    if (mysqli_query($conn, "INSERT INTO vendas (data_venda,total,id_clientes) VALUES ('".date('Y-m-d')."',$total_db,$id_cliente)")) {
      $id_venda = mysqli_insert_id($conn);
      foreach ($itens as $item) {
        $p_id=(int)$item['id']; $qtd=(int)$item['qtd']; $preco=number_format($item['preco'],2,'.','');
        if (!mysqli_query($conn,"INSERT INTO itens_venda (quantidade,preco_unitario,id_venda,id_produto) VALUES ($qtd,$preco,$id_venda,$p_id)")) { $erro=true; break; }
        mysqli_query($conn,"UPDATE estoque SET quantidade=quantidade-$qtd WHERE id_produto=$p_id AND quantidade>=$qtd");
      }
      if (isset($erro)) { mysqli_query($conn,"DELETE FROM itens_venda WHERE id_venda=$id_venda"); mysqli_query($conn,"DELETE FROM vendas WHERE id_venda=$id_venda"); $erro_msg='Erro ao processar pedido.'; }
      else { unset($_SESSION['carrinho']); $pedido_feito=true; }
    } else $erro_msg='Erro ao criar pedido.';
  }
}

if ($pedido_feito) { header("Location: pedido_confirmado.php?id=$id_venda"); exit; }

include 'header_cliente.php';
?>
<div style="max-width:900px;margin:0 auto">
  <h1>Finalizar Compra</h1>
  <?php if (!empty($erro_msg)): ?><div class="msg erro"><?php echo $erro_msg ?></div><?php endif; ?>
  <?php if (!$carrinho_valido): ?>
    <div class="cartao"><p class="vazio">Carrinho vazio.</p><p style="text-align:center"><a href="loja.php" class="btn">Ver produtos</a></p></div>
  <?php else: ?>
    <div class="flex">
      <div class="cartao">
        <h3>Seus dados</h3>
        <div class="linha"><strong>Email:</strong> <?php echo htmlspecialchars($cliente['usuario']) ?></div>
        <div class="linha"><strong>Nome:</strong> <?php echo htmlspecialchars($cliente['nome'] ? $cliente['nome'] : '(não informado)') ?></div>
        <div class="linha"><strong>Telefone:</strong> <?php echo htmlspecialchars($cliente['telefone'] ? $cliente['telefone'] : '-') ?></div>
        <div class="linha"><strong>Endereço:</strong> <?php echo htmlspecialchars($cliente['endereco'] ? $cliente['endereco'] : '(não informado)') ?></div>
        <div class="linha"><strong>Pagamento:</strong> <?php echo htmlspecialchars($cliente['forma_pagamento'] ? $cliente['forma_pagamento'] : '(não informado)') ?></div>
      </div>
      <div class="cartao">
        <h3>Resumo do pedido</h3>
        <table><tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr>
        <?php foreach ($itens as $item): ?><tr><td><?php echo htmlspecialchars($item['nome']) ?></td><td><?php echo $item['qtd'] ?></td><td>R$ <?php echo number_format($item['preco'],2,',','.') ?></td><td>R$ <?php echo number_format($item['subtotal'],2,',','.') ?></td></tr><?php endforeach; ?>
        </table>
        <p style="text-align:right;font-size:20px;font-weight:700;margin-top:16px">Total: R$ <?php echo number_format($total,2,',','.') ?></p>
      </div>
    </div>
    <form method="POST" class="cartao" style="margin-top:16px">
      <h3 style="margin-bottom:12px">Dados para entrega</h3>
      <div style="display:flex;flex-wrap:wrap;gap:12px">
        <div style="flex:1;min-width:200px"><label>Nome *</label><input type="text" name="cli_nome" value="<?php echo htmlspecialchars($cliente['nome']) ?>" style="width:100%;border:2px solid #111" required></div>
        <div style="flex:1;min-width:200px"><label>Telefone</label><input type="text" name="cli_telefone" value="<?php echo htmlspecialchars($cliente['telefone']) ?>" style="width:100%;border:2px solid #111"></div>
        <div style="flex:1;min-width:200px"><label>Endereço *</label><input type="text" name="cli_endereco" value="<?php echo htmlspecialchars($cliente['endereco']) ?>" style="width:100%;border:2px solid #111" required></div>
        <div style="flex:1;min-width:200px"><label>Pagamento *</label><select name="cli_pagamento" style="width:100%;border:2px solid #111" required>
          <option value="">Selecione</option>
          <option value="Cartão de Crédito" <?php if ($cliente['forma_pagamento'] == 'Cartão de Crédito') echo 'selected' ?>>Cartão de Crédito</option>
          <option value="Cartão de Débito" <?php if ($cliente['forma_pagamento'] == 'Cartão de Débito') echo 'selected' ?>>Cartão de Débito</option>
          <option value="Pix" <?php if ($cliente['forma_pagamento'] == 'Pix') echo 'selected' ?>>Pix</option>
          <option value="Dinheiro" <?php if ($cliente['forma_pagamento'] == 'Dinheiro') echo 'selected' ?>>Dinheiro</option>
          <option value="Boleto" <?php if ($cliente['forma_pagamento'] == 'Boleto') echo 'selected' ?>>Boleto</option>
        </select></div>
      </div>
      <button type="submit" class="btn btn-sucesso" style="margin-top:16px;width:100%;padding:14px;font-size:16px" onclick="return confirm('Confirmar compra?')">Confirmar e Finalizar</button>
    </form>
    <p><a href="carrinho.php">&larr; Voltar ao carrinho</a></p>
  <?php endif; ?>
</div>
</body></html>
