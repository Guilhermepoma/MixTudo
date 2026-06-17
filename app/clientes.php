<?php
session_start();
require_once 'conexao.php';

// ========================
// LOGICA DOS CLIENTES
// ========================

if (isset($_GET['excluir_cli'])) {
    $id = (int)$_GET['excluir_cli'];
    mysqli_query($conn, "DELETE FROM vendas WHERE id_clientes = $id");
    mysqli_query($conn, "DELETE FROM clientes WHERE id_clientes = $id");
    $_SESSION['mensagem'] = 'Cliente excluído!';
    header('Location: clientes.php');
    exit;
}

if (isset($_POST['cli_nome'])) {
    $id = (int)(isset($_POST['cli_id']) ? $_POST['cli_id'] : 0);
    $nome = trim($_POST['cli_nome']);
    $fone = trim($_POST['cli_telefone']);
    $endereco = trim($_POST['cli_endereco']);
    $pagamento = trim($_POST['cli_pagamento']);

    if ($nome == '') {
        $_SESSION['mensagem'] = 'O campo nome do cliente é obrigatório.';
        $_SESSION['tipo_msg'] = 'erro';
    } else {
        if ($id > 0) {
            $sql = "UPDATE clientes SET nome=?, telefone=?, endereco=?, forma_pagamento=? WHERE id_clientes=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssssi', $nome, $fone, $endereco, $pagamento, $id);
            mysqli_stmt_execute($stmt);
            $_SESSION['mensagem'] = 'Cliente atualizado!';
        } else {
            $sql = "INSERT INTO clientes (nome, telefone, endereco, forma_pagamento) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssss', $nome, $fone, $endereco, $pagamento);
            mysqli_stmt_execute($stmt);
            $_SESSION['mensagem'] = 'Cliente cadastrado!';
        }
    }
    header('Location: clientes.php');
    exit;
}

include 'header.php';
?>

<h1>Clientes</h1>

<form method="POST">
  <input type="hidden" name="cli_id" value="<?php echo isset($_GET['editar']) ? (int)$_GET['editar'] : 0 ?>">

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

  <div>
    <label>Endereço</label>
    <input type="text" name="cli_endereco" value="<?php
      if (isset($_GET['end'])) {
        echo htmlspecialchars($_GET['end']);
      }
    ?>">
  </div>

  <div>
    <label>Forma de Pagamento</label>
    <input type="text" name="cli_pagamento" value="<?php
      if (isset($_GET['pag'])) {
        echo htmlspecialchars($_GET['pag']);
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
    <th>Telefone</th>
    <th>Endereço</th>
    <th>Pagamento</th>
    <th>Ações</th>
  </tr>

  <?php
  $res = mysqli_query($conn, "SELECT * FROM clientes ORDER BY id_clientes");
  if (mysqli_num_rows($res) == 0) {
    echo '<tr><td colspan="5" class="vazio">Nenhum cliente cadastrado</td></tr>';
  } else {
    while ($cli = mysqli_fetch_assoc($res)) {
      ?>
      <tr>
        <td><?php echo htmlspecialchars($cli['nome']) ?></td>
        <td><?php echo $cli['telefone'] ? htmlspecialchars($cli['telefone']) : '-' ?></td>
        <td><?php echo htmlspecialchars($cli['endereco']) ?></td>
        <td><?php echo htmlspecialchars($cli['forma_pagamento']) ?></td>
        <td>
          <a href="clientes.php?editar=<?php echo $cli['id_clientes'] ?>&nome=<?php echo urlencode($cli['nome']) ?>&fone=<?php echo urlencode($cli['telefone']) ?>&end=<?php echo urlencode($cli['endereco']) ?>&pag=<?php echo urlencode($cli['forma_pagamento']) ?>" class="btn btn-peq">Editar</a>
          <a href="clientes.php?excluir_cli=<?php echo $cli['id_clientes'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir este cliente?')">Excluir</a>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>

</body></html>
