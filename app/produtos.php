<?php
session_start();
require_once 'conexao.php';

// ========================
// LOGICA DOS PRODUTOS
// ========================

if (isset($_GET['excluir_prod'])) {
    $id = (int)$_GET['excluir_prod'];
    mysqli_query($conn, "DELETE FROM itens_venda WHERE id_produto = $id");
    mysqli_query($conn, "DELETE FROM estoque WHERE id_produto = $id");
    mysqli_query($conn, "DELETE FROM produtos WHERE id = $id");
    $_SESSION['mensagem'] = 'Produto excluído!';
    header('Location: produtos.php');
    exit;
}

if (isset($_POST['prod_nome'])) {
    $id = (int)(isset($_POST['prod_id']) ? $_POST['prod_id'] : 0);
    $nome = trim($_POST['prod_nome']);
    $preco = str_replace(',', '.', $_POST['prod_preco']);
    $preco = (float)$preco;
    $marca = trim($_POST['prod_marca']);
    $id_categoria = (int)$_POST['prod_categoria'];
    $estoque = (int)$_POST['prod_estoque'];

    $erros = array();
    if ($nome == '') $erros[] = 'Nome';
    if ($preco <= 0) $erros[] = 'Preço';

    if (count($erros) > 0) {
        $_SESSION['mensagem'] = 'Preencha corretamente: ' . implode(', ', $erros) . '.';
        $_SESSION['tipo_msg'] = 'erro';
    } else {
        if ($id > 0) {
            $sql = "UPDATE produtos SET nome=?, preco=?, marca=?, id_categoria=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sdsii', $nome, $preco, $marca, $id_categoria, $id);
            mysqli_stmt_execute($stmt);

            $sql2 = "UPDATE estoque SET quantidade=? WHERE id_produto=?";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, 'ii', $estoque, $id);
            mysqli_stmt_execute($stmt2);

            $_SESSION['mensagem'] = 'Produto atualizado!';
        } else {
            $sql = "INSERT INTO produtos (nome, preco, marca, id_categoria) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'sdsi', $nome, $preco, $marca, $id_categoria);
            mysqli_stmt_execute($stmt);

            $id_produto = mysqli_insert_id($conn);

            $sql2 = "INSERT INTO estoque (quantidade, id_produto) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($conn, $sql2);
            mysqli_stmt_bind_param($stmt2, 'ii', $estoque, $id_produto);
            mysqli_stmt_execute($stmt2);

            $_SESSION['mensagem'] = 'Produto cadastrado!';
        }
    }
    header('Location: produtos.php');
    exit;
}

include 'header.php';
?>

<h1>Produtos</h1>

<form method="POST">
  <input type="hidden" name="prod_id" value="<?php echo isset($_GET['editar']) ? (int)$_GET['editar'] : 0 ?>">

  <div>
    <label>Nome *</label>
    <input type="text" name="prod_nome" value="<?php if (isset($_GET['nome'])) echo htmlspecialchars($_GET['nome']); ?>">
  </div>

  <div>
    <label>Preço *</label>
    <input type="text" name="prod_preco" value="<?php if (isset($_GET['preco'])) echo $_GET['preco']; ?>">
  </div>

  <div>
    <label>Marca</label>
    <input type="text" name="prod_marca" value="<?php if (isset($_GET['marca'])) echo htmlspecialchars($_GET['marca']); ?>">
  </div>

  <div>
    <label>Categoria</label>
    <select name="prod_categoria">
      <option value="0">---</option>
      <?php
      $categorias = mysqli_query($conn, "SELECT * FROM categoria ORDER BY nome");
      $cat_sel = isset($_GET['cat']) ? (int)$_GET['cat'] : 0;
      while ($c = mysqli_fetch_assoc($categorias)) {
        $selected = ($c['id_categoria'] == $cat_sel) ? ' selected' : '';
        echo '<option value="' . $c['id_categoria'] . '"' . $selected . '>' . htmlspecialchars($c['nome']) . '</option>';
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
    <th>Marca</th>
    <th>Categoria</th>
    <th>Estoque</th>
    <th>Ações</th>
  </tr>

  <?php
  $sql = "
    SELECT p.*, e.quantidade, c.nome AS nome_categoria
    FROM produtos p
    LEFT JOIN estoque e ON e.id_produto = p.id
    LEFT JOIN categoria c ON c.id_categoria = p.id_categoria
    ORDER BY p.id
  ";
  $res = mysqli_query($conn, $sql);

  if (mysqli_num_rows($res) == 0) {
    echo '<tr><td colspan="6" class="vazio">Nenhum produto cadastrado</td></tr>';
  } else {
    while ($p = mysqli_fetch_assoc($res)) {
      ?>
      <tr>
        <td><?php echo htmlspecialchars($p['nome']) ?></td>
        <td>R$ <?php echo number_format($p['preco'], 2, ',', '.') ?></td>
        <td><?php echo htmlspecialchars($p['marca']) ?></td>
        <td><?php echo $p['nome_categoria'] ? htmlspecialchars($p['nome_categoria']) : '-' ?></td>
        <td><?php echo (int)$p['quantidade'] ?></td>
        <td>
          <a href="produtos.php?editar=<?php echo $p['id'] ?>&nome=<?php echo urlencode($p['nome']) ?>&preco=<?php echo $p['preco'] ?>&marca=<?php echo urlencode($p['marca']) ?>&cat=<?php echo $p['id_categoria'] ?>&est=<?php echo (int)$p['quantidade'] ?>" class="btn btn-peq">Editar</a>
          <a href="produtos.php?excluir_prod=<?php echo $p['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir este produto?')">Excluir</a>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>

</body></html>
