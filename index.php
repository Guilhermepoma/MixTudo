<?php
session_start();

// Inicializa sessões
foreach (['categorias','produtos','clientes'] as $s) {
    if (!isset($_SESSION[$s])) $_SESSION[$s] = [];
}
if (!isset($_SESSION['next'])) $_SESSION['next'] = 1;

$pagina = $_GET['pagina'] ?? 'inicio';
$msg = $_SESSION['msg'] ?? null;
$tipo_msg = $_SESSION['tipo_msg'] ?? 'sucesso';
unset($_SESSION['msg'], $_SESSION['tipo_msg']);

// ========== AÇÕES ==========

// Categoria
if (isset($_POST['cat_nome'])) {
    $nome = trim($_POST['cat_nome']);
    $id = (int)($_POST['cat_id'] ?? 0);
    if (!$nome) {
        $_SESSION['msg'] = 'Preencha o nome da categoria.';
        $_SESSION['tipo_msg'] = 'erro';
    } elseif ($id) {
        foreach ($_SESSION['categorias'] as &$c) {
            if ($c['id'] === $id) { $c['nome'] = $nome; break; }
        }
        $_SESSION['msg'] = 'Categoria atualizada!';
    } else {
        $_SESSION['categorias'][] = ['id' => $_SESSION['next']++, 'nome' => $nome];
        $_SESSION['msg'] = 'Categoria cadastrada!';
    }
    header('Location: index.php?pagina=categorias'); exit;
}

if (isset($_GET['excluir_cat'])) {
    $_SESSION['categorias'] = array_values(array_filter($_SESSION['categorias'], fn($c) => $c['id'] != $_GET['excluir_cat']));
    $_SESSION['msg'] = 'Categoria excluída!';
    header('Location: index.php?pagina=categorias'); exit;
}

// Produto
if (isset($_POST['prod_nome'])) {
    $id = (int)($_POST['prod_id'] ?? 0);
    $dados = [
        'nome' => trim($_POST['prod_nome']),
        'preco' => (float)($_POST['prod_preco'] ?? 0),
        'categoria' => $_POST['prod_cat'] ?? ''
    ];
    $erros = [];
    if (!$dados['nome']) $erros[] = 'Nome';
    if ($dados['preco'] <= 0) $erros[] = 'Preço';
    if ($erros) {
        $_SESSION['msg'] = 'Preencha: ' . implode(', ', $erros);
        $_SESSION['tipo_msg'] = 'erro';
    } elseif ($id) {
        foreach ($_SESSION['produtos'] as &$p) {
            if ($p['id'] === $id) { $p = array_merge($p, $dados); break; }
        }
        $_SESSION['msg'] = 'Produto atualizado!';
    } else {
        $dados['id'] = $_SESSION['next']++;
        $dados['estoque'] = (int)($_POST['prod_estoque'] ?? 0);
        $_SESSION['produtos'][] = $dados;
        $_SESSION['msg'] = 'Produto cadastrado!';
    }
    header('Location: index.php?pagina=produtos'); exit;
}

if (isset($_GET['excluir_prod'])) {
    $_SESSION['produtos'] = array_values(array_filter($_SESSION['produtos'], fn($p) => $p['id'] != $_GET['excluir_prod']));
    $_SESSION['msg'] = 'Produto excluído!';
    header('Location: index.php?pagina=produtos'); exit;
}

// Cliente
if (isset($_POST['cli_nome'])) {
    $id = (int)($_POST['cli_id'] ?? 0);
    $nome = trim($_POST['cli_nome']);
    $telefone = trim($_POST['cli_telefone'] ?? '');
    if (!$nome) {
        $_SESSION['msg'] = 'Preencha o nome do cliente.';
        $_SESSION['tipo_msg'] = 'erro';
    } elseif ($id) {
        foreach ($_SESSION['clientes'] as &$c) {
            if ($c['id'] === $id) { $c['nome'] = $nome; $c['telefone'] = $telefone; break; }
        }
        $_SESSION['msg'] = 'Cliente atualizado!';
    } else {
        $_SESSION['clientes'][] = ['id' => $_SESSION['next']++, 'nome' => $nome, 'telefone' => $telefone];
        $_SESSION['msg'] = 'Cliente cadastrado!';
    }
    header('Location: index.php?pagina=clientes'); exit;
}

if (isset($_GET['excluir_cli'])) {
    $_SESSION['clientes'] = array_values(array_filter($_SESSION['clientes'], fn($c) => $c['id'] != $_GET['excluir_cli']));
    $_SESSION['msg'] = 'Cliente excluído!';
    header('Location: index.php?pagina=clientes'); exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>MixTudo</title>
  <link rel="stylesheet" href="MixTudo.css">
</head>
<body>

<div class="menu">
  <a href="index.php" class="<?= $pagina === 'inicio' ? 'ativo' : '' ?>">Início</a>
  <a href="index.php?pagina=categorias" class="<?= $pagina === 'categorias' ? 'ativo' : '' ?>">Categorias</a>
  <a href="index.php?pagina=produtos" class="<?= $pagina === 'produtos' ? 'ativo' : '' ?>">Produtos</a>
  <a href="index.php?pagina=clientes" class="<?= $pagina === 'clientes' ? 'ativo' : '' ?>">Clientes</a>
</div>

<?php if ($msg): ?>
  <div class="msg <?= $tipo_msg ?>"><?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<?php if ($pagina === 'inicio'): ?>

  <h1>MixTudo Variedades</h1>
  <div class="flex">
    <div class="cartao">
      <strong>Produtos:</strong> <?= count($_SESSION['produtos']) ?> cadastrados
    </div>
    <div class="cartao">
      <strong>Clientes:</strong> <?= count($_SESSION['clientes']) ?> cadastrados
    </div>
  </div>

<?php elseif ($pagina === 'categorias'): ?>

  <h1>Categorias</h1>
  <form method="POST">
    <input type="hidden" name="cat_id" value="<?= $_GET['editar'] ?? 0 ?>">
    <div>
      <label>Nome da categoria *</label>
      <input type="text" name="cat_nome" value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>" required>
    </div>
    <button type="submit"><?= isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?></button>
  </form>

  <table>
    <tr><th>Nome</th><th>Ações</th></tr>
    <?php if (!$_SESSION['categorias']): ?>
      <tr><td colspan="2" class="vazio">Nenhuma categoria</td></tr>
    <?php else: foreach ($_SESSION['categorias'] as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['nome']) ?></td>
        <td>
          <a href="index.php?pagina=categorias&editar=<?= $c['id'] ?>&nome=<?= urlencode($c['nome']) ?>" class="btn btn-peq">Editar</a>
          <a href="index.php?pagina=categorias&excluir_cat=<?= $c['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </table>

<?php elseif ($pagina === 'produtos'): ?>

  <h1>Produtos</h1>
  <form method="POST">
    <input type="hidden" name="prod_id" value="<?= $_GET['editar'] ?? 0 ?>">
    <div>
      <label>Nome *</label>
      <input type="text" name="prod_nome" value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>" required>
    </div>
    <div>
      <label>Preço *</label>
      <input type="number" name="prod_preco" step="0.01" value="<?= $_GET['preco'] ?? '' ?>" required>
    </div>
    <div>
      <label>Categoria</label>
      <select name="prod_cat">
        <option value="">---</option>
        <?php foreach ($_SESSION['categorias'] as $c): ?>
          <option value="<?= $c['nome'] ?>" <?= ($_GET['cat'] ?? '') === $c['nome'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Estoque</label>
      <input type="number" name="prod_estoque" value="<?= (int)($_GET['est'] ?? 0) ?>">
    </div>
    <button type="submit"><?= isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?></button>
  </form>

  <table>
    <tr><th>Nome</th><th>Preço</th><th>Categoria</th><th>Estoque</th><th>Ações</th></tr>
    <?php if (!$_SESSION['produtos']): ?>
      <tr><td colspan="5" class="vazio">Nenhum produto</td></tr>
    <?php else: foreach ($_SESSION['produtos'] as $p): ?>
      <tr>
        <td><?= htmlspecialchars($p['nome']) ?></td>
        <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
        <td><?= htmlspecialchars($p['categoria'] ?: '-') ?></td>
        <td><?= $p['estoque'] ?? 0 ?></td>
        <td>
          <a href="index.php?pagina=produtos&editar=<?= $p['id'] ?>&nome=<?= urlencode($p['nome']) ?>&preco=<?= $p['preco'] ?>&cat=<?= urlencode($p['categoria']) ?>&est=<?= $p['estoque'] ?? 0 ?>" class="btn btn-peq">Editar</a>
          <a href="index.php?pagina=produtos&excluir_prod=<?= $p['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </table>

<?php elseif ($pagina === 'clientes'): ?>

  <h1>Clientes</h1>
  <form method="POST">
    <input type="hidden" name="cli_id" value="<?= $_GET['editar'] ?? 0 ?>">
    <div>
      <label>Nome *</label>
      <input type="text" name="cli_nome" value="<?= htmlspecialchars($_GET['nome'] ?? '') ?>" required>
    </div>
    <div>
      <label>Telefone</label>
      <input type="text" name="cli_telefone" value="<?= htmlspecialchars($_GET['fone'] ?? '') ?>">
    </div>
    <button type="submit"><?= isset($_GET['editar']) ? 'Salvar' : 'Cadastrar' ?></button>
  </form>

  <table>
    <tr><th>Nome</th><th>Telefone</th><th>Ações</th></tr>
    <?php if (!$_SESSION['clientes']): ?>
      <tr><td colspan="3" class="vazio">Nenhum cliente</td></tr>
    <?php else: foreach ($_SESSION['clientes'] as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['nome']) ?></td>
        <td><?= htmlspecialchars($c['telefone'] ?: '-') ?></td>
        <td>
          <a href="index.php?pagina=clientes&editar=<?= $c['id'] ?>&nome=<?= urlencode($c['nome']) ?>&fone=<?= urlencode($c['telefone'] ?? '') ?>" class="btn btn-peq">Editar</a>
          <a href="index.php?pagina=clientes&excluir_cli=<?= $c['id'] ?>" class="btn btn-perigo btn-peq" onclick="return confirm('Excluir?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach; endif; ?>
  </table>

<?php endif; ?>

</body>
</html>
