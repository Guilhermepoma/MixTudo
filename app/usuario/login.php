<?php
session_start();
if (isset($_SESSION['cliente_id'])) {
    header('Location: loja.php');
    exit;
}

require_once '../conexao.php';
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    if ($usuario && $senha) {
        $usuario_esc = mysqli_real_escape_string($conn, $usuario);
        $res = mysqli_query($conn, "SELECT id_clientes, nome, senha FROM clientes WHERE usuario = '$usuario_esc'");

        if ($cli = mysqli_fetch_assoc($res)) {
            if (sha1('mixTudo_salt_' . $senha) === $cli['senha']) {
                $_SESSION['cliente_id'] = $cli['id_clientes'];
                $_SESSION['cliente_nome'] = $cli['nome'];
                header('Location: loja.php');
                exit;
            }
        }
    }
    $erro = 'Usuário ou senha inválidos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - MixTudo</title>
  <link rel="stylesheet" href="../../MixTudo.css">
</head>
<body class="login-body">
  <div class="login-box">
    <h1>MixTudo</h1>
    <p class="login-sub">Faça seu login para comprar</p>
    <?php if ($erro): ?>
      <div class="msg erro"><?php echo $erro; ?></div>
    <?php endif; ?>
    <form method="POST" class="login-form">
      <input type="text" name="usuario" placeholder="Usuário" required>
      <input type="password" name="senha" placeholder="Senha" required>
      <button type="submit">Entrar</button>
    </form>
    <p class="login-link">Ainda não tem conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
    <p class="login-link"><a href="../../index.html">Voltar ao início</a></p>
  </div>
</body>
</html>
