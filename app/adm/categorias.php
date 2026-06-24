<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['excluir_cat'])) { mysqli_query($conn, "DELETE FROM categoria WHERE id_categoria=".(int)$_GET['excluir_cat']); $_SESSION['mensagem']='Categoria excluída!'; header('Location: categorias.php'); exit; }
if (isset($_POST['cat_nome'])) {
  $nome = trim($_POST['cat_nome']); $class = trim($_POST['cat_classificacao']); $id = (int)@$_POST['cat_id'];
  if ($nome=='') { $_SESSION['mensagem']='O campo nome é obrigatório.'; $_SESSION['tipo_msg']='erro'; }
  else {
    if ($id>0) { $s=mysqli_prepare($conn,"UPDATE categoria SET nome=?,classificacao=? WHERE id_categoria=?"); mysqli_stmt_bind_param($s,'ssi',$nome,$class,$id); mysqli_stmt_execute($s); $_SESSION['mensagem']='Categoria atualizada!'; }
    else { $s=mysqli_prepare($conn,"INSERT INTO categoria (nome,classificacao) VALUES (?,?)"); mysqli_stmt_bind_param($s,'ss',$nome,$class); mysqli_stmt_execute($s); $_SESSION['mensagem']='Categoria cadastrada!'; }
  }
  header('Location: categorias.php'); exit;
}

include 'header.php';
?>
<h1>Categorias</h1>
<form method="POST">
  <input type="hidden" name="cat_id" value="<?php echo (int)@$_GET['editar'] ?>">
  <div><label>Nome *</label><input type="text" name="cat_nome" value="<?php echo htmlspecialchars(@$_GET['nome']) ?>"></div>
  <div><label>Classificação</label><input type="text" name="cat_classificacao" value="<?php echo htmlspecialchars(@$_GET['class']) ?>"></div>
  <button type="submit"><i class="fas fa-<?php echo isset($_GET['editar']) ? 'save' : 'plus' ?>"></i> <?php echo isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?></button>
</form>
<table>
  <tr><th>Nome</th><th>Classificação</th><th>Ações</th></tr>
  <?php $res = mysqli_query($conn, "SELECT * FROM categoria ORDER BY id_categoria");
  if (mysqli_num_rows($res)==0) echo '<tr><td colspan="3" class="vazio">Nenhuma categoria</td></tr>';
  else while ($cat = mysqli_fetch_assoc($res)) { ?>
    <tr>
      <td><?php echo htmlspecialchars($cat['nome']) ?></td>
      <td><?php echo htmlspecialchars($cat['classificacao']) ?></td>
      <td>
        <a href="categorias.php?editar=<?php echo $cat['id_categoria'] ?>&nome=<?php echo urlencode($cat['nome']) ?>&class=<?php echo urlencode($cat['classificacao']) ?>" class="btn btn-peq"><i class="fas fa-edit"></i> Editar</a>
        <a href="categorias.php?excluir_cat=<?php echo $cat['id_categoria'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir?')"><i class="fas fa-trash-alt"></i> Excluir</a>
      </td>
    </tr>
  <?php } ?>
</table>
</body></html>
