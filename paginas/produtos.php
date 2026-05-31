<h1>Produtos</h1>

<form method="POST">
  <input type="hidden" name="prod_id" value="<?php echo $_GET['editar'] ?? 0 ?>">

  <div>
    <label>Nome *</label>
    <input type="text" name="prod_nome" value="<?php
      if (isset($_GET['nome'])) echo htmlspecialchars($_GET['nome']);
    ?>">
  </div>

  <div>
    <label>Preço *</label>
    <input type="text" name="prod_preco" value="<?php
      if (isset($_GET['preco'])) echo $_GET['preco'];
    ?>">
  </div>

  <div>
    <label>Categoria</label>
    <select name="prod_cat">
      <option value="">---</option>
      <?php
      foreach ($_SESSION['categorias'] as $c) {
        $selected = '';
        if (isset($_GET['cat']) && $_GET['cat'] == $c['nome']) {
          $selected = ' selected';
        }
        echo '<option value="' . $c['nome'] . '"' . $selected . '>' . htmlspecialchars($c['nome']) . '</option>';
      }
      ?>
    </select>
  </div>

  <div>
    <label>Estoque</label>
    <input type="number" name="prod_estoque" value="<?php
      if (isset($_GET['est'])) {
        echo (int)$_GET['est'];
      } else {
        echo '0';
      }
    ?>">
  </div>

  <button type="submit">
    <?php echo isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?>
  </button>
</form>

<table>
  <tr>
    <th>Nome</th>
    <th>Preço</th>
    <th>Categoria</th>
    <th>Estoque</th>
    <th>Ações</th>
  </tr>

  <?php
  if (count($_SESSION['produtos']) == 0) {
    echo '<tr><td colspan="5" class="vazio">Nenhum produto cadastrado</td></tr>';
  } else {
    foreach ($_SESSION['produtos'] as $p) {
      ?>
      <tr>
        <td><?php echo htmlspecialchars($p['nome']) ?></td>
        <td>R$ <?php echo number_format($p['preco'], 2, ',', '.') ?></td>
        <td><?php
          if ($p['categoria'] != '') {
            echo htmlspecialchars($p['categoria']);
          } else {
            echo '-';
          }
        ?></td>
        <td><?php echo $p['estoque'] ?></td>
        <td>
          <a href="app.php?pagina=produtos&editar=<?php echo $p['id'] ?>&nome=<?php echo urlencode($p['nome']) ?>&preco=<?php echo $p['preco'] ?>&cat=<?php echo urlencode($p['categoria']) ?>&est=<?php echo $p['estoque'] ?>" class="btn btn-peq">Editar</a>
          <a href="app.php?pagina=produtos&excluir_prod=<?php echo $p['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir este produto?')">Excluir</a>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>
