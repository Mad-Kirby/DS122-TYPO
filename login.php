<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/autenticar.css" />
  <script src="js/login.js" defer></script>
</head>
<body>
  <main class="auth-page">
    <section class="auth-card" aria-labelledby="titulo-cadastro">
      <h1 id="titulo-cadastro" class="auth-title">Login</h1>

      <p class="auth-subtitle">
        Não possui uma conta?
        <a href="cadastro.php" class="auth-link">Cadastre-se.</a>
      </p>

      <form class="auth-form" action="jogo.php?step=como-jogar" method="post">
        <div class="field">
          <input type="text" id="nome" name="nome" placeholder="Nome:" />
          <p class="msg-erro" id="erro-nome"></p>
        </div>

        <div class="field">
          <input type="password" id="senha" name="senha" placeholder="Senha:" />
          <p class="msg-erro" id="erro-senha"></p>
        </div>

        <button type="submit" class="btn-submit">Continuar</button>
      </form>
    </section>
  </main>
</body>
</html>