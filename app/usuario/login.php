<?php
session_start();
if (isset($_SESSION['cliente_id'])) { header('Location: loja.php'); exit; }
require_once '../conexao.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = trim($_POST['usuario']); $s = $_POST['senha'];
  if ($u && $s) {
    $u = mysqli_real_escape_string($conn, $u);
    $cli = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_clientes,nome,senha FROM clientes WHERE usuario='$u'"));
    if ($cli && sha1('mixTudo_salt_'.$s) === $cli['senha']) { $_SESSION['cliente_id']=$cli['id_clientes']; $_SESSION['cliente_nome']=$cli['nome']; header('Location: loja.php'); exit; }
  }
  $erro = 'Usuário ou senha inválidos.';
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0"><title>Login - MixTudo</title><link rel="stylesheet" href="../../MixTudo.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></head>
<body class="login-body">
  <div class="login-box">
    <h1><i class="fas fa-store"></i> MixTudo</h1>
    <p class="login-sub"><i class="fas fa-user"></i> Faça seu login para comprar</p>

    <?php if ($erro): ?><div class="msg erro"><?php echo $erro ?></div><?php endif; ?>
    <form method="POST" class="login-form">
      <input type="text" name="usuario" placeholder="Usuário" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit"><i class="fas fa-sign-in-alt"></i> Entrar</button>
    </form>
    <p class="login-link">Ainda não tem conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
    <p class="login-link"><a href="../../index.html">Voltar ao início</a></p>
  </div>
</body>
</html>
