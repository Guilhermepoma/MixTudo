<h1>Categorias</h1>

<form method="POST">
  <input type="hidden" name="cat_id" value="<?php echo $_GET['editar'] ?? 0 ?>">

  <div>
    <label>Nome da categoria *</label>
    <input type="text" name="cat_nome" value="<?php
      if (isset($_GET['nome'])) {
        echo htmlspecialchars($_GET['nome']);
      }
    ?>">
  </div>

  <button type="submit">
    <?php
      if (isset($_GET['editar'])) {
        echo 'Salvar';
      } else {
        echo 'Cadastrar';
      }
    ?>
  </button>
</form>

<table>
  <tr>
    <th>Nome</th>
    <th>Ações</th>
  </tr>

  <?php
  if (count($_SESSION['categorias']) == 0) {
    echo '<tr><td colspan="2" class="vazio">Nenhuma categoria cadastrada</td></tr>';
  } else {
    foreach ($_SESSION['categorias'] as $c) {
      ?>
      <tr>
        <td><?php echo htmlspecialchars($c['nome']) ?></td>
        <td>
          <a href="app.php?pagina=categorias&editar=<?php echo $c['id'] ?>&nome=<?php echo urlencode($c['nome']) ?>" class="btn btn-peq">Editar</a>
          <a href="app.php?pagina=categorias&excluir_cat=<?php echo $c['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir esta categoria?')">Excluir</a>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>
