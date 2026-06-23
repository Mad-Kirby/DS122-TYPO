<?php
require_once "includes/auth.php";

$step = $_GET["step"] ?? "como-jogar";
$pontos = isset($_POST["pontos"]) ? (int) $_POST["pontos"] : 0;

if ($step == "tentativa-pts") {
  $step = "pontuacao";
}

$titulos = [
  "como-jogar" => "Como jogar?",
  "tentativa" => "Tentativa",
  "pontuacao" => "Pontuação Tentativa",
  "placar" => "Placar"
];

$tituloPagina = $titulos[$step] ?? "Como jogar?";
$cssPagina = $step == "como-jogar" ? "css/como-jogar.css" : "css/jogo.css";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $tituloPagina; ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $cssPagina; ?>" />

  <?php if ($step == "tentativa") { ?>
    <script type="module" src="js/jogo.js" defer></script>
  <?php } ?>
</head>
<body>

<?php if ($step == "tentativa") { ?>

  <main class="screen screen--attempt">
    <section class="screen__panel" aria-labelledby="title-attempt">
      <header class="screen__header">
        <h1 class="screen__title screen__title--attempt" id="title-attempt">Tentativa</h1>
      </header>

      <article class="screen__card">
        <div class="screen__top">
          <h2>Memorize a palavra abaixo:</h2>
          <p class="screen__time">Tempo: <span></span></p> 
          <p class="screen__pts">Pontuação: <span></span></p>
          <p class="screen__errors">Erros: <span></span></p>
        </div>

        <div class="screen__input-wrap">
          <p class="memorize__field"></p>
          <input
            class="screen__input"
            type="text"
            name="tentativa"
            aria-label="Digite a palavra abaixo"
            autocomplete="off"
            spellcheck="false"
          />
        </div>
      </article>

      <p class="screen__result" aria-live="polite"></p>
    </section>
  </main>

<?php } elseif ($step == "pontuacao") { ?>

  <main class="screen screen--result">
    <section class="screen__panel" aria-labelledby="title-result">
      <header class="screen__header">
        <h1 class="screen__title screen__title--result" id="title-result">Pontuação Tentativa</h1>
      </header>

      <article class="screen__card" aria-label="Pontuação da tentativa">
        <p class="screen__score"><?php echo $pontos; ?> PONTOS</p>
      </article>

      <div class="screen__actions">
        <a href="jogo.php?step=placar&pontos=<?php echo $pontos; ?>" class="screen__button">Continuar</a>
      </div>
    </section>
  </main>

<?php } elseif ($step == "placar") { ?>

  <main class="screen screen--scoreboard">
    <section class="screen__panel" aria-labelledby="title-scoreboard">
      <header class="screen__header">
        <h1 class="screen__title screen__title--scoreboard" id="title-scoreboard">Placar</h1>
      </header>

      <article class="scoreboard-card" aria-label="Lista de pontuações">
        <div class="scoreboard-table">
          <div class="scoreboard-table__head">
            <span>Jogador</span>
            <span>Pontuação</span>
          </div>

          <div class="scoreboard-table__line"></div>

          <div class="scoreboard-table__body">
            <div class="scoreboard-row">
              <span id="nome-jogador">jogador</span>
              <span id="pontuacao-final"><?php echo $pontos; ?> pts</span>
            </div>
          </div>
        </div>
      </article>

        <div class="screen__actions screen__actions--scoreboard">
        <a href="jogo.php?step=como-jogar" class="screen__button">Jogar Novamente</a>
        <a href="logout.php" class="screen__button">Sair</a>
        </div>
    </section>
  </main>

<?php } else { ?>

  <main class="howto-screen" id="conteudo-principal">
    <section class="howto-layout" aria-labelledby="titulo-como-jogar">
      <div class="howto-panel">
        <header class="howto-header">
          <h1 id="titulo-como-jogar">Como jogar?</h1>
        </header>

        <section class="howto-box howto-box--steps" aria-labelledby="titulo-passos">
          <h2 id="titulo-passos" class="sr-only">Passos do jogo</h2>
          <ol class="howto-list">
            <li>Uma ou mais palavras aparecerão na tela.</li>
            <li>Memorize antes que o tempo acabe.</li>
            <li>Digite exatamente o que foi mostrado.</li>
            <li>Acerte para ganhar pontos!</li>
          </ol>
        </section>

        <section class="howto-box howto-box--warning" aria-labelledby="titulo-atencao">
          <h2 id="titulo-atencao">Atenção:</h2>
          <ol class="howto-list howto-list--secondary">
            <li>Letras maiúsculas e acentos podem contar como erro.</li>
            <li>Quanto mais rápido e preciso, maior sua pontuação!</li>
          </ol>
        </section>

        <section class="howto-scoring" aria-labelledby="titulo-pontuacao">
          <h2 id="titulo-pontuacao" class="sr-only">Pontuação</h2>

          <div class="score-row">
            <span class="score-label">Acerto 100%</span>
            <span class="score-separator">=</span>
            <span class="score-value">10 pontos</span>
          </div>

          <div class="score-row">
            <span class="score-label">Acerto 50%</span>
            <span class="score-separator">=</span>
            <span class="score-value">5 pontos</span>
          </div>

          <div class="score-row">
            <span class="score-label">Erro</span>
            <span class="score-separator">=</span>
            <span class="score-value">0 pontos</span>
          </div>
        </section>
      </div>

      <div class="howto-actions">
        <a href="jogo.php?step=tentativa" class="start-button" data-action="comecar">COMEÇAR!</a>
      </div>
    </section>
  </main>

<?php } ?>

</body>
</html>