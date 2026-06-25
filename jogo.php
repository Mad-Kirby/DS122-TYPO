<?php
// Protege a página para que apenas usuários logados possam acessar o jogo.
require_once "includes/auth.php";

// Importa a conexão com o banco de dados.
require_once "includes/conexao.php";

// Define qual tela do jogo será exibida.
$step = $_GET["step"] ?? "como-jogar";
$pontos = 0;
$partida = null;
$rankingGeral = [];

// Etapa responsável por receber a pontuação enviada pelo JavaScript
// e salvar uma nova partida no banco de dados.
if ($step === "salvar-pontuacao") {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        header("Location: jogo.php?step=como-jogar");
        exit;
    }

    // Valida a pontuação recebida via POST, garantindo que seja um número inteiro.
    $pontos = filter_input(INPUT_POST, "pontos", FILTER_VALIDATE_INT);

    if ($pontos === false || $pontos === null || $pontos < 0) {
        $pontos = 0;
    }

    if ($pontos > 100000) {
        $pontos = 100000;
    }
    // Salva a partida associando a pontuação ao usuário logado.
    $sql = "INSERT INTO partidas (id_usuario, pontos) 
            VALUES (:id_usuario, :pontos)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id_usuario", $_SESSION["usuario_id"], PDO::PARAM_INT);
    $stmt->bindValue(":pontos", $pontos, PDO::PARAM_INT);
    $stmt->execute();

    $idPartida = $pdo->lastInsertId();
    // Após salvar a partida, redireciona para a tela de pontuação
    // usando o id da partida recém-criada.
    header("Location: jogo.php?step=pontuacao&id_partida=" . $idPartida);
    exit;
}

// Busca a partida salva para exibir a pontuação correta na tela de resultado.
if ($step === "pontuacao") {
    $idPartida = filter_input(INPUT_GET, "id_partida", FILTER_VALIDATE_INT);

    if ($idPartida) {
        $sql = "SELECT id_partida, pontos, jogada_em
                FROM partidas
                WHERE id_partida = :id_partida
                AND id_usuario = :id_usuario";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id_partida", $idPartida, PDO::PARAM_INT);
        $stmt->bindValue(":id_usuario", $_SESSION["usuario_id"], PDO::PARAM_INT);
        $stmt->execute();

        $partida = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($partida) {
            $pontos = (int) $partida["pontos"];
        }
    }
}

// Monta o ranking geral somando os pontos de todas as partidas de cada usuário.
if ($step === "placar") {
    $sqlRanking = "SELECT 
                    usuarios.nome,
                    SUM(partidas.pontos) AS total_pontos
                   FROM partidas
                   INNER JOIN usuarios 
                        ON usuarios.id_usuario = partidas.id_usuario
                   GROUP BY usuarios.id_usuario, usuarios.nome
                   ORDER BY total_pontos DESC
                   LIMIT 10";

    $stmtRanking = $pdo->prepare($sqlRanking);
    $stmtRanking->execute();

    $rankingGeral = $stmtRanking->fetchAll(PDO::FETCH_ASSOC);
}

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
        <a href="jogo.php?step=placar" class="screen__button">Continuar</a>
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
            <?php if (count($rankingGeral) === 0) { ?>
              <div class="scoreboard-row">
                <span>Nenhuma partida</span>
                <span>0 pts</span>
              </div>
            <?php } else { ?>
              <?php foreach ($rankingGeral as $linha) { ?>
                <div class="scoreboard-row">
                  <span><?php echo htmlspecialchars($linha["nome"]); ?></span>
                  <span><?php echo (int) $linha["total_pontos"]; ?> pts</span>
                </div>
              <?php } ?>
            <?php } ?>
          </div>
        </div>
      </article>

        <div class="screen__actions screen__actions--scoreboard">
        <a href="historico.php" class="screen__button">Histórico de Partidas</a>
        <a href="ligas.php" class="screen__button">Ligas</a>
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
            <li>Quanto mais rápido e preciso, maior sua pontuação.</li>
            <li>Acertos em sequência também fazem diferença!</li>
          </ol>
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