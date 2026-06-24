<?php
session_start();
if (!isset($_SESSION['cliente_id'])) { header('Location: login.php'); exit; }
require_once '../conexao.php';

$cat = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$sql = "SELECT p.*, e.quantidade, c.nome AS nome_categoria FROM produtos p LEFT JOIN estoque e ON e.id_produto = p.id LEFT JOIN categoria c ON c.id_categoria = p.id_categoria WHERE 1=1";
if ($cat > 0) $sql .= " AND p.id_categoria = $cat";
if ($busca != '') { $b = mysqli_real_escape_string($conn, $busca); $sql .= " AND (p.nome LIKE '%$b%' OR p.marca LIKE '%$b%')"; }
$sql .= " ORDER BY p.nome";
$produtos = mysqli_query($conn, $sql);
$cats = mysqli_query($conn, "SELECT * FROM categoria ORDER BY nome");
$mensagem = isset($_SESSION['carrinho_msg']) ? $_SESSION['carrinho_msg'] : null;
unset($_SESSION['carrinho_msg']);

include 'header_cliente.php';
?>
<header class="mix-header" style="position:relative;height:auto;padding:40px 20px;flex-direction:column">
  <h1 style="font-size:36px">MixTudo</h1>
  <p>Catálogo de produtos</p>
</header>

<section class="mix-section">

  <h2 class="mix-title">Nossos Produtos</h2>

  <?php if ($mensagem): ?><div class="msg sucesso"><?php echo $mensagem ?></div><?php endif; ?>

  <div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap;align-items:center">
    <form method="GET" style="display:flex;gap:8px;flex:1;min-width:250px">
      <input type="text" name="busca" placeholder="Buscar produto ou marca..." value="<?php echo htmlspecialchars($busca) ?>" style="flex:1;border-radius:20px;padding:10px 16px;border:2px solid #111">
      <button type="submit" style="border-radius:20px;padding:10px 20px;border:none;background:#1f3b73;color:white;font-weight:600;cursor:pointer"><i class="fas fa-search"></i> Buscar</button>
    </form>
  </div>

  <form method="GET" class="mix-filtros">
    <button type="submit" name="cat" value="0" class="<?php if ($cat==0) echo 'ativo' ?>">Todos</button>
    <?php mysqli_data_seek($cats, 0); while ($c = mysqli_fetch_assoc($cats)): ?>
      <button type="submit" name="cat" value="<?php echo $c['id_categoria'] ?>" class="<?php if ($cat==$c['id_categoria']) echo 'ativo' ?>"><?php echo htmlspecialchars($c['nome']) ?></button>
    <?php endwhile; ?>
  </form>

  <div class="mix-produtos">
    <?php if (mysqli_num_rows($produtos)==0): ?><p class="vazio" style="grid-column:1/-1">Nenhum produto encontrado.</p>
    <?php else: while ($p = mysqli_fetch_assoc($produtos)): ?>
      <div class="mix-produto">
        <img src="https://placehold.co/400x300/1f3b73/ffffff?text=<?php echo urlencode(strtoupper(substr($p['nome'],0,2))) ?>" alt="<?php echo htmlspecialchars($p['nome']) ?>">
        <div class="mix-produto-info">
          <h3><?php echo htmlspecialchars($p['nome']) ?></h3>
          <p><?php echo htmlspecialchars($p['marca']).($p['marca'] ? ' &middot; ' : '').htmlspecialchars(@$p['nome_categoria'] ? $p['nome_categoria'] : 'Sem cat.') ?></p>
          <span class="preco">R$ <?php echo number_format($p['preco'],2,',','.') ?></span>
          <?php if ($p['quantidade'] > 0): ?><a href="carrinho.php?add=<?php echo $p['id'] ?>" class="mix-produto-btn"><i class="fas fa-cart-plus"></i> Adicionar ao carrinho</a>
          <?php else: ?><span class="mix-produto-btn" style="background:#bbb;cursor:not-allowed"><i class="fas fa-times-circle"></i> Indisponível</span><?php endif; ?>
        </div>
      </div>
    <?php endwhile; endif; ?>
  </div>
</section>
</body></html>
