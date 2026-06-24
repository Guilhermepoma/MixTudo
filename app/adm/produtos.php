<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['excluir_prod'])) {
  $id=(int)$_GET['excluir_prod'];
  mysqli_query($conn,"DELETE FROM itens_venda WHERE id_produto=$id");
  mysqli_query($conn,"DELETE FROM estoque WHERE id_produto=$id");
  mysqli_query($conn,"DELETE FROM produtos WHERE id=$id");
  $_SESSION['mensagem']='Produto excluído!'; header('Location: produtos.php'); exit;
}

if (isset($_POST['prod_nome'])) {
  $id=(int)@$_POST['prod_id']; $nome=trim($_POST['prod_nome']); $preco=(float)str_replace(',','.',$_POST['prod_preco']);
  $marca=trim($_POST['prod_marca']); $cat=(int)$_POST['prod_categoria']; $est=(int)$_POST['prod_estoque'];
  if ($nome==''||$preco<=0) { $_SESSION['mensagem']='Preencha nome e preço.'; $_SESSION['tipo_msg']='erro'; }
  else {
    if ($id>0) {
      $s=mysqli_prepare($conn,"UPDATE produtos SET nome=?,preco=?,marca=?,id_categoria=? WHERE id=?");
      mysqli_stmt_bind_param($s,'sdsii',$nome,$preco,$marca,$cat,$id); mysqli_stmt_execute($s);
      $s2=mysqli_prepare($conn,"UPDATE estoque SET quantidade=? WHERE id_produto=?");
      mysqli_stmt_bind_param($s2,'ii',$est,$id); mysqli_stmt_execute($s2);
      $_SESSION['mensagem']='Produto atualizado!';
    } else {
      $s=mysqli_prepare($conn,"INSERT INTO produtos (nome,preco,marca,id_categoria) VALUES (?,?,?,?)");
      mysqli_stmt_bind_param($s,'sdsi',$nome,$preco,$marca,$cat); mysqli_stmt_execute($s);
      $pid=mysqli_insert_id($conn);
      $s2=mysqli_prepare($conn,"INSERT INTO estoque (quantidade,id_produto) VALUES (?,?)");
      mysqli_stmt_bind_param($s2,'ii',$est,$pid); mysqli_stmt_execute($s2);
      $_SESSION['mensagem']='Produto cadastrado!';
    }
  }
  header('Location: produtos.php'); exit;
}
include 'header.php';
$cat_sel=(int)@$_GET['cat'];
?>
<h1>Produtos</h1>
<form method="POST">
  <input type="hidden" name="prod_id" value="<?php echo (int)@$_GET['editar'] ?>">
  <div><label>Nome *</label><input type="text" name="prod_nome" value="<?php echo htmlspecialchars(@$_GET['nome']) ?>"></div>
  <div><label>Preço *</label><input type="text" name="prod_preco" value="<?php echo @$_GET['preco'] ?>"></div>
  <div><label>Marca</label><input type="text" name="prod_marca" value="<?php echo htmlspecialchars(@$_GET['marca']) ?>"></div>
  <div><label>Categoria</label><select name="prod_categoria">
    <option value="0">---</option>
    <?php $cats=mysqli_query($conn,"SELECT * FROM categoria ORDER BY nome");
    while($c=mysqli_fetch_assoc($cats)) echo '<option value="'.$c['id_categoria'].'"'.($c['id_categoria']==$cat_sel?' selected':'').'>'.htmlspecialchars($c['nome']).'</option>'; ?>
  </select></div>
  <div><label>Estoque</label><input type="number" name="prod_estoque" value="<?php echo (int)@$_GET['est'] ?>"></div>
  <button type="submit"><i class="fas fa-<?php echo isset($_GET['editar']) ? 'save' : 'plus' ?>"></i> <?php echo isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?></button>
</form>
<table>
  <tr><th>Nome</th><th>Preço</th><th>Marca</th><th>Categoria</th><th>Estoque</th><th>Ações</th></tr>
  <?php
  $res=mysqli_query($conn,"SELECT p.*,e.quantidade,c.nome AS nome_categoria FROM produtos p LEFT JOIN estoque e ON e.id_produto=p.id LEFT JOIN categoria c ON c.id_categoria=p.id_categoria ORDER BY p.id");
  if(mysqli_num_rows($res)==0) echo '<tr><td colspan="6" class="vazio">Nenhum produto</td></tr>';
  else while($p=mysqli_fetch_assoc($res)){ ?>
    <tr>
      <td><?php echo htmlspecialchars($p['nome']) ?></td>
      <td>R$ <?php echo number_format($p['preco'],2,',','.') ?></td>
      <td><?php echo htmlspecialchars($p['marca']) ?></td>
      <td><?php echo $p['nome_categoria']?htmlspecialchars($p['nome_categoria']):'-' ?></td>
      <td><?php echo (int)$p['quantidade'] ?></td>
      <td>
        <a href="produtos.php?editar=<?php echo $p['id'] ?>&nome=<?php echo urlencode($p['nome']) ?>&preco=<?php echo $p['preco'] ?>&marca=<?php echo urlencode($p['marca']) ?>&cat=<?php echo $p['id_categoria'] ?>&est=<?php echo (int)$p['quantidade'] ?>" class="btn btn-peq"><i class="fas fa-edit"></i> Editar</a>
        <a href="produtos.php?excluir_prod=<?php echo $p['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir?')"><i class="fas fa-trash-alt"></i> Excluir</a>
      </td>
    </tr>
  <?php } ?>
</table>
</body></html>
