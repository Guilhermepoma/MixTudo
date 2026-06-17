<?php
session_start();
require_once 'conexao.php';

// ========================
// LOGICA DAS CATEGORIAS
// ========================

if (isset($_GET['excluir_cat'])) {
    $id = (int)$_GET['excluir_cat'];
    mysqli_query($conn, "DELETE FROM categoria WHERE id_categoria = $id");
    $_SESSION['mensagem'] = 'Categoria excluída!';
    header('Location: categorias.php');
    exit;
}

if (isset($_POST['cat_nome'])) {
    $nome = trim($_POST['cat_nome']);
    $classificacao = trim($_POST['cat_classificacao']);
    $id = (int)(isset($_POST['cat_id']) ? $_POST['cat_id'] : 0);

    if ($nome == '') {
        $_SESSION['mensagem'] = 'O campo nome da categoria é obrigatório.';
        $_SESSION['tipo_msg'] = 'erro';
    } else {
        if ($id > 0) {
            $sql = "UPDATE categoria SET nome=?, classificacao=? WHERE id_categoria=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ssi', $nome, $classificacao, $id);
            mysqli_stmt_execute($stmt);
            $_SESSION['mensagem'] = 'Categoria atualizada com sucesso!';
        } else {
            $sql = "INSERT INTO categoria (nome, classificacao) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'ss', $nome, $classificacao);
            mysqli_stmt_execute($stmt);
            $_SESSION['mensagem'] = 'Categoria cadastrada com sucesso!';
        }
    }
    header('Location: categorias.php');
    exit;
}

include 'header.php';
?>

<h1>Categorias</h1>

<form method="POST">
  <input type="hidden" name="cat_id" value="<?php echo isset($_GET['editar']) ? (int)$_GET['editar'] : 0 ?>">

  <div>
    <label>Nome *</label>
    <input type="text" name="cat_nome" value="<?php
      if (isset($_GET['nome'])) {
        echo htmlspecialchars($_GET['nome']);
      }
    ?>">
  </div>

  <div>
    <label>Classificação</label>
    <input type="text" name="cat_classificacao" value="<?php
      if (isset($_GET['class'])) {
        echo htmlspecialchars($_GET['class']);
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
    <th>Classificação</th>
    <th>Ações</th>
  </tr>

  <?php
  $res = mysqli_query($conn, "SELECT * FROM categoria ORDER BY id_categoria");
  if (mysqli_num_rows($res) == 0) {
    echo '<tr><td colspan="3" class="vazio">Nenhuma categoria cadastrada</td></tr>';
  } else {
    while ($cat = mysqli_fetch_assoc($res)) {
      ?>
      <tr>
        <td><?php echo htmlspecialchars($cat['nome']) ?></td>
        <td><?php echo htmlspecialchars($cat['classificacao']) ?></td>
        <td>
          <a href="categorias.php?editar=<?php echo $cat['id_categoria'] ?>&nome=<?php echo urlencode($cat['nome']) ?>&class=<?php echo urlencode($cat['classificacao']) ?>" class="btn btn-peq">Editar</a>
          <a href="categorias.php?excluir_cat=<?php echo $cat['id_categoria'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir esta categoria?')">Excluir</a>
        </td>
      </tr>
      <?php
    }
  }
  ?>
</table>

</body></html>
