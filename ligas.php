<?php
$step = $_GET["step"] ?? "minhas";

if ($step == "criar-liga") {
  $step = "criar";
}

if ($step == "entrar-liga") {
  $step = "entrar";
}

if ($step == "detalhes-liga") {
  $step = "detalhes";
}

if ($step == "minhas-ligas") {
  $step = "minhas";
}

$titulos = [
  "minhas" => "Minhas Ligas",
  "criar" => "Criar Liga",
  "entrar" => "Entrar em Liga",
  "detalhes" => "Detalhes da Liga"
];

$tituloPagina = $titulos[$step] ?? "Minhas Ligas";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $tituloPagina; ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/ligas.css" />
  <script src="js/ligas.js" defer></script>
</head>
<body>

<?php if ($step == "criar") { ?>

  <main class="container" aria-labelledby="titulo-criar">
    <header>
      <h1 id="titulo-criar">Criar Liga</h1>
    </header>

    <form class="card" action="ligas.php?step=minhas" method="post" aria-labelledby="titulo-criar">
      <div class="form-group">
        <label for="nome-liga">Nome da Liga</label>
        <input id="nome-liga" type="text" required />
        <p class="msg-erro"></p>
      </div>

      <div class="form-group">
        <label for="palavra-chave">Palavra-chave</label>
        <input id="palavra-chave" type="text" required />
        <p class="msg-erro"></p>
      </div>

      <button type="submit" class="btn">Criar Liga</button>
    </form>
  </main>

<?php } elseif ($step == "entrar") { ?>

  <main class="container" aria-labelledby="titulo-entrar">
    <header>
      <h1 id="titulo-entrar">Entrar em Liga</h1>
    </header>

    <form class="card" action="ligas.php?step=minhas" method="post" aria-labelledby="titulo-entrar">
      <div class="form-group">
        <label for="nome-liga-entrada">Nome da Liga</label>
        <input id="nome-liga-entrada" type="text" required />
        <p class="msg-erro"></p>
      </div>

      <div class="form-group">
        <label for="senha-liga">Palavra-chave</label>
        <input id="senha-liga" type="password" required />
        <p class="msg-erro"></p>
      </div>

      <button type="submit" class="btn">Entrar</button>
    </form>
  </main>

<?php } elseif ($step == "detalhes") { ?>

  <main class="container" aria-labelledby="titulo-liga">
    <header>
      <h1 id="titulo-liga">Liga Amigos</h1>
      <!-- Pegar o nome/Info da liga no DB -->
    </header>

    <section class="card" aria-labelledby="titulo-ranking-geral">
      <h2 id="titulo-ranking-geral">Ranking Geral</h2>

      <table>
        <thead>
          <tr>
            <th>Posição</th>
            <th>Jogador</th>
            <th>Pontos</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1º</td>
            <!-- Pegar a Info  no DB -->
            <td>Carlos</td>
            <td>3500</td>
          </tr>

          <tr>
            <td>2º</td>
            <!-- Pegar a Info  no DB -->
            <td>Henrique</td>
            <td>2900</td>
          </tr>
        </tbody>
      </table>
    </section>

    <section class="card" aria-labelledby="titulo-ranking-semanal">
      <h2 id="titulo-ranking-semanal">Ranking Semanal</h2>

      <table>
        <thead>
          <tr>
            <th>Posição</th>
            <th>Jogador</th>
            <th>Pontos</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1º</td>
            <!-- Pegar a Info  no DB -->
            <td>Henrique</td>
            <td>180</td>
          </tr>

          <tr>
            <td>2º</td>
            <!-- Pegar a Info  no DB -->
            <td>Carlos</td>
            <td>160</td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>

<?php } else { ?>

  <main class="container" aria-labelledby="titulo-minhas-ligas">
    <header>
      <h1 id="titulo-minhas-ligas">Minhas Ligas</h1>
    </header>

    <section class="card" aria-labelledby="titulo-participando">
      <h2 id="titulo-participando">Ligas Participando</h2>

      <div class="liga-item">
        <span>Liga Amigos</span>
        <!-- Pegar o nome/Info da liga no DB -->
        <a href="ligas.php?step=detalhes">Ver</a>
      </div>

      <div class="liga-item">
        <span>Liga Faculdade</span>
        <!-- Pegar o nome/Info da liga no DB -->
        <a href="ligas.php?step=detalhes">Ver</a>
      </div>
    </section>

    <nav class="acoes" aria-label="Ações de ligas">
      <a href="ligas.php?step=criar" class="btn">Criar Liga</a>
      <a href="ligas.php?step=entrar" class="btn">Entrar em Liga</a>
    </nav>

    <section class="card" aria-labelledby="titulo-pontuacao">
      <h2 id="titulo-pontuacao">Pontuação Geral</h2>
      <!-- Pegar o Info  no DB -->

      <table>
        <thead>
          <tr>
            <th>Jogador</th>
            <!-- Pegar o Info  no DB -->
            <th>Total</th>
            <!-- Pegar o Info  no DB -->
            <th>Semanal</th>
            <!-- Pegar o Info  no DB -->
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>João</td>
            <!-- Pegar o Info  no DB -->
            <td>1500</td>
            <!-- Pegar o Info  no DB -->
            <td>120</td>
            <!-- Pegar a Info  no DB -->
          </tr>

          <tr>
            <td>Maria</td>
            <!-- Pegar o Info  no DB -->
            <td>1400</td>
            <!-- Pegar o Info  no DB -->
            <td>100</td>
            <!-- Pegar o Info  no DB -->
          </tr>
        </tbody>
      </table>
    </section>
  </main>

<?php } ?>

</body>
</html>