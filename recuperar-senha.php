<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Recuperar Senha</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/autenticar.css" />
  <script src="js/recuperar-senha.js" defer></script>
</head>
<body>
  <button type="button" class="btn-submit back-btn" onclick="history.back()" aria-label="Voltar">
    ← Voltar
  </button>
  <main class="auth-page">
    <section class="auth-card" aria-labelledby="titulo-cadastro">
      <h1 id="titulo-cadastro" class="auth-title">Recuperar Senha</h1>

      <form class="auth-form" action="cadastro.php?step=senha" method="post">
        <div class="field">
          <input type="text" id="nome" name="nome" placeholder="E-mail:" />
          <p class="msg-erro" id="erro-nome"></p>
        </div>

        <div class="field">
          <input type="email" id="email" name="email" placeholder="Código de Recuperação:" />
          <p class="msg-erro" id="erro-email"></p>
        </div>

        <button type="submit" class="btn-submit">Continuar</button>
      </form>
    </section>
  </main>
</body>
</html>