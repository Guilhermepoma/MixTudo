<h1>Clientes</h1>

<form method="POST">
  <input type="hidden" name="cli_id" value="<?php echo $_GET['editar'] ?? 0 ?>">

  <div>
    <label>Nome *</label>
    <input type="text" name="cli_nome" value="<?php
      if (isset($_GET['nome'])) {
        echo htmlspecialchars($_GET['nome']);
      }
    ?>">
  </div>

  <div>
    <label>Telefone</label>
    <input type="text" name="cli_telefone" value="<?php
      if (isset($_GET['fone'])) {
        echo htmlspecialchars($_GET['fone']);
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
    <th>Telefone</th>
    <th>Ações</th>
  </tr>

  <?php
  if (count($_SESSION['clientes']) == 0) {
    echo '<tr><td colspan="3" class="vazio">Nenhum cliente cadastrado</td></tr>';
  } else {
    foreach ($_SESSION['clientes'] as $c) {
      ?>
      <tr>
        <td><?php echo htmlspecialchars($c['nome']) ?></td>
        <td><?php
          if ($c['telefone'] != '') {
            echo htmlspecialchars($c['telefone']);
          } else {
            echo '-';
          }
        ?></td>
        <td>
          <a href="app.php?pagina=clientes&editar=<?php echo $c['id'] ?>&nome=<?php echo urlencode($c['nome']) ?>&fone=<?php echo urlencode($c['telefone'] ?? '') ?>" class="btn btn-peq">Editar</a>
          <a href="app.php?pagina=clientes&excluir_cli=<?php echo $c['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir este cliente?')">Excluir</a>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>
