<?php
session_start();
require_once '../conexao.php';

if (isset($_GET['excluir_cli'])) {
  $id=(int)$_GET['excluir_cli'];
  mysqli_query($conn,"DELETE FROM vendas WHERE id_clientes=$id");
  mysqli_query($conn,"DELETE FROM clientes WHERE id_clientes=$id");
  $_SESSION['mensagem']='Cliente excluído!'; header('Location: clientes.php'); exit;
}
if (isset($_POST['cli_nome'])) {
  $id=(int)@$_POST['cli_id']; $nome=trim($_POST['cli_nome']); $fone=trim($_POST['cli_telefone']);
  $end=trim($_POST['cli_endereco']); $pag=trim($_POST['cli_pagamento']); $usr=trim($_POST['cli_usuario']); $senha=$_POST['cli_senha'];
  if ($nome=='') { $_SESSION['mensagem']='Nome é obrigatório.'; $_SESSION['tipo_msg']='erro'; }
  else {
    if ($id>0) {
      if ($senha!='') { $hash=sha1('mixTudo_salt_'.$senha); $s=mysqli_prepare($conn,"UPDATE clientes SET nome=?,telefone=?,endereco=?,forma_pagamento=?,usuario=?,senha=? WHERE id_clientes=?"); mysqli_stmt_bind_param($s,'ssssssi',$nome,$fone,$end,$pag,$usr,$hash,$id); }
      else { $s=mysqli_prepare($conn,"UPDATE clientes SET nome=?,telefone=?,endereco=?,forma_pagamento=?,usuario=? WHERE id_clientes=?"); mysqli_stmt_bind_param($s,'sssssi',$nome,$fone,$end,$pag,$usr,$id); }
      mysqli_stmt_execute($s); $_SESSION['mensagem']='Cliente atualizado!';
    } else {
      if ($usr==''||$senha=='') { $_SESSION['mensagem']='Usuário e senha obrigatórios.'; $_SESSION['tipo_msg']='erro'; header('Location: clientes.php'); exit; }
      $hash=sha1('mixTudo_salt_'.$senha);
      $s=mysqli_prepare($conn,"INSERT INTO clientes (nome,telefone,endereco,forma_pagamento,usuario,senha) VALUES (?,?,?,?,?,?)");
      mysqli_stmt_bind_param($s,'ssssss',$nome,$fone,$end,$pag,$usr,$hash); mysqli_stmt_execute($s);
      $_SESSION['mensagem']='Cliente cadastrado!';
    }
  }
  header('Location: clientes.php'); exit;
}
include 'header.php';
?>
<h1>Clientes</h1>
<form method="POST">
  <input type="hidden" name="cli_id" value="<?php echo (int)@$_GET['editar'] ?>">
  <div><label>Nome *</label><input type="text" name="cli_nome" value="<?php echo htmlspecialchars(@$_GET['nome']) ?>"></div>
  <div><label>Telefone</label><input type="text" name="cli_telefone" value="<?php echo htmlspecialchars(@$_GET['fone']) ?>"></div>
  <div><label>Endereço</label><input type="text" name="cli_endereco" value="<?php echo htmlspecialchars(@$_GET['end']) ?>"></div>
  <div><label>Pagamento</label><input type="text" name="cli_pagamento" value="<?php echo htmlspecialchars(@$_GET['pag']) ?>"></div>
  <div><label>Usuário <?php echo isset($_GET['editar'])?'':'*' ?></label><input type="text" name="cli_usuario" value="<?php echo htmlspecialchars(@$_GET['usr']) ?>"></div>
  <div><label>Senha <?php echo isset($_GET['editar'])?'(vazio=manter)':'*' ?></label><input type="password" name="cli_senha"></div>
  <button type="submit"><i class="fas fa-<?php echo isset($_GET['editar']) ? 'save' : 'plus' ?>"></i> <?php echo isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?></button>
</form>
<table>
  <tr><th>Nome</th><th>Telefone</th><th>Endereço</th><th>Pagamento</th><th>Usuário</th><th>Ações</th></tr>
  <?php $res=mysqli_query($conn,"SELECT * FROM clientes ORDER BY id_clientes");
  if(mysqli_num_rows($res)==0) echo '<tr><td colspan="6" class="vazio">Nenhum cliente</td></tr>';
  else while($cli=mysqli_fetch_assoc($res)){ ?>
    <tr>
      <td><?php echo htmlspecialchars($cli['nome']) ?></td>
      <td><?php echo $cli['telefone']?htmlspecialchars($cli['telefone']):'-' ?></td>
      <td><?php echo htmlspecialchars($cli['endereco']) ?></td>
      <td><?php echo htmlspecialchars($cli['forma_pagamento']) ?></td>
      <td><?php echo htmlspecialchars(@$cli['usuario']?$cli['usuario']:'-') ?></td>
      <td>
        <a href="clientes.php?editar=<?php echo $cli['id_clientes'] ?>&nome=<?php echo urlencode($cli['nome']) ?>&fone=<?php echo urlencode($cli['telefone']) ?>&end=<?php echo urlencode($cli['endereco']) ?>&pag=<?php echo urlencode($cli['forma_pagamento']) ?>&usr=<?php echo urlencode(@$cli['usuario']) ?>" class="btn btn-peq"><i class="fas fa-edit"></i> Editar</a>
        <a href="clientes.php?excluir_cli=<?php echo $cli['id_clientes'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir?')"><i class="fas fa-trash-alt"></i> Excluir</a>
      </td>
    </tr>
  <?php } ?>
</table>
</body></html>
