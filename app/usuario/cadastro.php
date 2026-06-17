<?php
session_start();
require_once '../conexao.php';

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $senha2 = $_POST['senha2'];

    if (!$email || !$senha) {
        $erro = 'Preencha email e senha.';
    } elseif ($senha !== $senha2) {
        $erro = 'As senhas não conferem.';
    } elseif (strlen($senha) < 4) {
        $erro = 'A senha deve ter pelo menos 4 caracteres.';
    } else {
        $email_esc = mysqli_real_escape_string($conn, $email);
        $check = mysqli_query($conn, "SELECT id_clientes FROM clientes WHERE usuario = '$email_esc'");
        if (mysqli_fetch_assoc($check)) {
            $erro = 'Este email já está cadastrado.';
        } else {
            $hash = sha1('mixTudo_salt_' . $senha);
            $sql = "INSERT INTO clientes (nome, telefone, endereco, forma_pagamento, usuario, senha) VALUES ('$email_esc', '', '', '', '$email_esc', '$hash')";
            if (mysqli_query($conn, $sql)) {
                $sucesso = 'Cadastro realizado com sucesso! Faça seu login.';
            } else {
                $erro = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - MixTudo</title>
  <link rel="stylesheet" href="../../MixTudo.css">
</head>
<body class="login-body">
  <div class="login-box">
    <h1>MixTudo</h1>
    <p class="login-sub">Crie sua conta</p>
    <?php if ($erro): ?>
      <div class="msg erro"><?php echo $erro; ?></div>
    <?php endif; ?>
    <?php if ($sucesso): ?>
      <div class="msg sucesso"><?php echo $sucesso; ?></div>
      <p style="text-align:center"><a href="login.php" class="btn">Ir para o login</a></p>
    <?php else: ?>
      <form method="POST" class="login-form">
        <input type="email" name="email" placeholder="Seu email *" value="<?php echo htmlspecialchars(isset($_POST['email']) ? $_POST['email'] : ''); ?>" required>
        <input type="password" name="senha" placeholder="Senha *" required>
        <input type="password" name="senha2" placeholder="Repita a senha *" required>
        <button type="submit">Criar conta</button>
      </form>
      <p class="login-link">Já tem conta? <a href="login.php">Faça login</a></p>
    <?php endif; ?>
    <p class="login-link"><a href="../../index.html">Voltar ao início</a></p>
  </div>
</body>
</html>
