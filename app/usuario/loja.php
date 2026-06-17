<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}
require_once '../conexao.php';

$categoria_id = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$sql = "
    SELECT p.*, e.quantidade, c.nome AS nome_categoria
    FROM produtos p
    LEFT JOIN estoque e ON e.id_produto = p.id
    LEFT JOIN categoria c ON c.id_categoria = p.id_categoria
    WHERE 1=1
";

if ($categoria_id > 0) {
    $sql .= " AND p.id_categoria = $categoria_id";
}
if ($busca != '') {
    $busca_esc = mysqli_real_escape_string($conn, $busca);
    $sql .= " AND (p.nome LIKE '%$busca_esc%' OR p.marca LIKE '%$busca_esc%')";
}
$sql .= " ORDER BY p.nome";

$produtos = mysqli_query($conn, $sql);

$cats = mysqli_query($conn, "SELECT * FROM categoria ORDER BY nome");

$mensagem = isset($_SESSION['carrinho_msg']) ? $_SESSION['carrinho_msg'] : null;
unset($_SESSION['carrinho_msg']);

include 'header_cliente.php';
?>
<div style="max-width:1200px;margin:0 auto">

  <h1>Produtos</h1>

  <?php if ($mensagem): ?>
    <div class="msg sucesso"><?php echo $mensagem; ?></div>
  <?php endif; ?>

  <form method="GET" style="display:flex;gap:12px;margin-bottom:24px;align-items:flex-end;flex-wrap:wrap">
    <div>
      <label>Buscar</label>
      <input type="text" name="busca" placeholder="Produto ou marca..." value="<?php echo htmlspecialchars($busca); ?>">
    </div>
    <div>
      <label>Categoria</label>
      <select name="cat">
        <option value="0">Todas</option>
        <?php while ($c = mysqli_fetch_assoc($cats)): ?>
          <option value="<?php echo $c['id_categoria']; ?>" <?php if ($categoria_id == $c['id_categoria']) echo 'selected'; ?>>
            <?php echo htmlspecialchars($c['nome']); ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <button type="submit">Filtrar</button>
  </form>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px">
    <?php if (mysqli_num_rows($produtos) == 0): ?>
      <p class="vazio" style="grid-column:1/-1">Nenhum produto encontrado.</p>
    <?php else: ?>
      <?php while ($p = mysqli_fetch_assoc($produtos)): ?>
        <div class="cartao" style="display:flex;flex-direction:column;padding:0;overflow:hidden">
          <div style="background:#0d47a1;color:#fff;padding:32px;text-align:center;font-size:20px;font-weight:700">
            MixTudo
          </div>
          <div style="padding:16px;flex:1;display:flex;flex-direction:column">
            <h3 style="font-size:18px;margin-bottom:4px"><?php echo htmlspecialchars($p['nome']); ?></h3>
            <p style="font-size:13px;color:#777;margin-bottom:4px"><?php echo htmlspecialchars($p['marca']); ?> &middot; <?php echo htmlspecialchars(isset($p['nome_categoria']) ? $p['nome_categoria'] : 'Sem cat.'); ?></p>
            <p style="font-size:24px;font-weight:700;color:#0d47a1;margin:12px 0">
              R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
            </p>
            <p style="font-size:13px;margin-bottom:12px;color:<?php echo $p['quantidade'] > 0 ? '#2e7d32' : '#c62828'; ?>">
              <?php echo $p['quantidade'] > 0 ? "Em estoque: {$p['quantidade']} un." : 'Indisponível'; ?>
            </p>
            <?php if ($p['quantidade'] > 0): ?>
              <a href="carrinho.php?add=<?php echo $p['id']; ?>" class="btn btn-sucesso" style="margin-top:auto;text-align:center">Adicionar ao carrinho</a>
            <?php else: ?>
              <button class="btn" style="margin-top:auto;text-align:center;background:#999;cursor:not-allowed" disabled>Indisponível</button>
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>
</div>

</body></html>
