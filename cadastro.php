<?php
$step = $_GET["step"] ?? "dados";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastro</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/autenticar.css" />
</head>
<body>
  <main class="auth-page">
    <section class="auth-card" aria-labelledby="titulo-cadastro">
      <h1 id="titulo-cadastro" class="auth-title">Cadastre-se</h1>

      <p class="auth-subtitle">
        Já possui uma conta?
        <a href="login.php" class="auth-link">Faça login.</a>
      </p>

      <?php if ($step == "senha") { ?>

        <form class="auth-form" action="login.php" method="post">
        <div class="field">
            <input type="password" id="senha" name="senha" placeholder="Senha:"/>
        </div>

        <div class="field">
            <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Confirmar Senha:"/>
        </div>

          <button type="submit" class="btn-submit">Criar Conta</button>
        </form>

      <?php } else { ?>

        <form class="auth-form" action="cadastro.php?step=senha" method="post">
          <div class="field">
            <input type="text" id="nome" name="nome" placeholder="Nome:" />
          </div>

          <div class="field">
            <input type="email" id="email" name="email" placeholder="E-mail:" />
          </div>

          <button type="submit" class="btn-submit">Continuar</button>
        </form>

      <?php } ?>
    </section>
  </main>
</body>
</html>