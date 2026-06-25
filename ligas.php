<?php
// Protege a página de ligas para que apenas usuários logados possam acessar.
require_once "includes/auth.php";

// Importa a conexão com o banco de dados.
require_once "includes/conexao.php";

// Define qual tela de ligas será exibida: minhas ligas, criar, entrar ou detalhes.
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

$erro = $_GET["erro"] ?? "";
$sucesso = $_GET["sucesso"] ?? "";

// Valida o nome da liga antes de salvar no banco.
function validarNomeLiga($nomeLiga) {
    if ($nomeLiga === "") {
        return "Nome da liga não pode estar vazio.";
    }

    if (strlen($nomeLiga) < 5 || strlen($nomeLiga) > 32) {
        return "Nome da liga deve ter entre 5 e 32 caracteres.";
    }

    if (!preg_match("/^[\wÀ-ÿ´`^~¨ !@#$%&?.-]+$/u", $nomeLiga)) {
        return "Nome da liga possui caracteres inválidos.";
    }

    if (preg_match("/^[´`^~¨ _!@#$%&?.-]+$/u", $nomeLiga)) {
        return "Nome da liga não pode conter somente caracteres especiais.";
    }

    return "";
}

// Valida a palavra-chave usada para entrar ou criar uma liga.
function validarPalavraChave($palavraChave) {
    if ($palavraChave === "") {
        return "Palavra-chave não pode estar vazia.";
    }

    if (strlen($palavraChave) < 5 || strlen($palavraChave) > 32) {
        return "Palavra-chave deve ter entre 5 e 32 caracteres.";
    }

    if (!preg_match("/^[\w !@#$%&?*.-]+$/u", $palavraChave)) {
        return "Palavra-chave possui caracteres inválidos.";
    }

    if (preg_match("/^[_!@#$%&?. -]+$/u", $palavraChave)) {
        return "Palavra-chave não pode conter somente caracteres especiais.";
    }

    return "";
}

// Processa os formulários de criação ou entrada em ligas.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idUsuario = $_SESSION["usuario_id"];

    // Criação de uma nova liga pelo usuário logado.
    if ($step === "criar") {
        $nomeLiga = trim($_POST["nome_liga"] ?? "");
        $palavraChave = trim($_POST["palavra_chave"] ?? "");

        $erroValidacao = validarNomeLiga($nomeLiga);

        if ($erroValidacao === "") {
            $erroValidacao = validarPalavraChave($palavraChave);
        }

        if ($erroValidacao !== "") {
            header("Location: ligas.php?step=criar&erro=" . urlencode($erroValidacao));
            exit;
        }

        // Verifica se já existe uma liga com o mesmo nome.
        $sqlVerificar = "SELECT id_liga FROM ligas WHERE nome = :nome LIMIT 1";
        $stmtVerificar = $pdo->prepare($sqlVerificar);
        $stmtVerificar->bindValue(":nome", $nomeLiga);
        $stmtVerificar->execute();

        if ($stmtVerificar->fetch()) {
            header("Location: ligas.php?step=criar&erro=" . urlencode("Já existe uma liga com esse nome."));
            exit;
        }

        try {
            // Usa transação para garantir que a liga e o vínculo do criador
            // sejam salvos juntos. Se algo falhar, nada é gravado.
            $pdo->beginTransaction();

            // Gera o hash da palavra-chave da liga antes de salvar no banco.
            $palavraChaveHash = password_hash($palavraChave, PASSWORD_DEFAULT);

            // Insere a nova liga na tabela ligas.
            $sqlLiga = "INSERT INTO ligas (nome, palavra_chave_hash, id_criador)
                        VALUES (:nome, :palavra_chave_hash, :id_criador)";

            $stmtLiga = $pdo->prepare($sqlLiga);
            $stmtLiga->bindValue(":nome", $nomeLiga);
            $stmtLiga->bindValue(":palavra_chave_hash", $palavraChaveHash);
            $stmtLiga->bindValue(":id_criador", $idUsuario, PDO::PARAM_INT);
            $stmtLiga->execute();

            $idLiga = $pdo->lastInsertId();

            $sqlMembro = "INSERT INTO usuarios_ligas (id_usuario, id_liga)
                          VALUES (:id_usuario, :id_liga)";

            $stmtMembro = $pdo->prepare($sqlMembro);
            $stmtMembro->bindValue(":id_usuario", $idUsuario, PDO::PARAM_INT);
            $stmtMembro->bindValue(":id_liga", $idLiga, PDO::PARAM_INT);
            $stmtMembro->execute();

            $pdo->commit();

            header("Location: ligas.php?sucesso=" . urlencode("Liga criada com sucesso."));
            exit;

        } catch (PDOException $e) {
            $pdo->rollBack();

            header("Location: ligas.php?step=criar&erro=" . urlencode("Erro ao criar liga."));
            exit;
        }
    }
    // Entrada de um usuário em uma liga já existente.
    if ($step === "entrar") {
        $nomeLiga = trim($_POST["nome_liga"] ?? "");
        $palavraChave = trim($_POST["palavra_chave"] ?? "");

        if ($nomeLiga === "" || $palavraChave === "") {
            header("Location: ligas.php?step=entrar&erro=" . urlencode("Preencha nome da liga e palavra-chave."));
            exit;
        }
        // Busca a liga pelo nome informado pelo usuário.
        $sqlLiga = "SELECT id_liga, palavra_chave_hash
                    FROM ligas
                    WHERE nome = :nome
                    LIMIT 1";

        $stmtLiga = $pdo->prepare($sqlLiga);
        $stmtLiga->bindValue(":nome", $nomeLiga);
        $stmtLiga->execute();

        $liga = $stmtLiga->fetch(PDO::FETCH_ASSOC);

        if (!$liga) {
            header("Location: ligas.php?step=entrar&erro=" . urlencode("Liga não encontrada."));
            exit;
        }
        
        // Compara a palavra-chave digitada com o hash salvo no banco.
        if (!password_verify($palavraChave, $liga["palavra_chave_hash"])) {
            header("Location: ligas.php?step=entrar&erro=" . urlencode("Palavra-chave incorreta."));
            exit;
        }
        // Verifica se o usuário já participa dessa liga.
        $sqlVerificarMembro = "SELECT id_usuario
                               FROM usuarios_ligas
                               WHERE id_usuario = :id_usuario
                               AND id_liga = :id_liga";

        $stmtVerificarMembro = $pdo->prepare($sqlVerificarMembro);
        $stmtVerificarMembro->bindValue(":id_usuario", $idUsuario, PDO::PARAM_INT);
        $stmtVerificarMembro->bindValue(":id_liga", $liga["id_liga"], PDO::PARAM_INT);
        $stmtVerificarMembro->execute();

        if ($stmtVerificarMembro->fetch()) {
            header("Location: ligas.php?erro=" . urlencode("Você já participa dessa liga."));
            exit;
        }

        $sqlEntrar = "INSERT INTO usuarios_ligas (id_usuario, id_liga)
                      VALUES (:id_usuario, :id_liga)";

        $stmtEntrar = $pdo->prepare($sqlEntrar);
        $stmtEntrar->bindValue(":id_usuario", $idUsuario, PDO::PARAM_INT);
        $stmtEntrar->bindValue(":id_liga", $liga["id_liga"], PDO::PARAM_INT);
        $stmtEntrar->execute();

        header("Location: ligas.php?sucesso=" . urlencode("Você entrou na liga com sucesso."));
        exit;
    }
}

$minhasLigas = [];

// Busca todas as ligas em que o usuário logado participa.
$sqlMinhasLigas = "SELECT 
                    ligas.id_liga,
                    ligas.nome,
                    ligas.criada_em,
                    usuarios.nome AS nome_criador
                  FROM usuarios_ligas
                  INNER JOIN ligas 
                    ON ligas.id_liga = usuarios_ligas.id_liga
                  INNER JOIN usuarios 
                    ON usuarios.id_usuario = ligas.id_criador
                  WHERE usuarios_ligas.id_usuario = :id_usuario
                  ORDER BY ligas.criada_em DESC";

$stmtMinhasLigas = $pdo->prepare($sqlMinhasLigas);
$stmtMinhasLigas->bindValue(":id_usuario", $_SESSION["usuario_id"], PDO::PARAM_INT);
$stmtMinhasLigas->execute();

$minhasLigas = $stmtMinhasLigas->fetchAll(PDO::FETCH_ASSOC);

$ligaAtual = null;
$rankingLigaGeral = [];
$rankingLigaSemanal = [];

// Tela de detalhes da liga, exibida apenas se o usuário participa dela.
if ($step === "detalhes") {
    $idLiga = filter_input(INPUT_GET, "id_liga", FILTER_VALIDATE_INT);

    if (!$idLiga) {
        header("Location: ligas.php?erro=" . urlencode("Liga não encontrada."));
        exit;
    }

    $sqlLigaAtual = "SELECT 
                        ligas.id_liga,
                        ligas.nome,
                        ligas.criada_em
                     FROM ligas
                     INNER JOIN usuarios_ligas
                        ON usuarios_ligas.id_liga = ligas.id_liga
                     WHERE ligas.id_liga = :id_liga
                     AND usuarios_ligas.id_usuario = :id_usuario
                     LIMIT 1";

    $stmtLigaAtual = $pdo->prepare($sqlLigaAtual);
    $stmtLigaAtual->bindValue(":id_liga", $idLiga, PDO::PARAM_INT);
    $stmtLigaAtual->bindValue(":id_usuario", $_SESSION["usuario_id"], PDO::PARAM_INT);
    $stmtLigaAtual->execute();

    $ligaAtual = $stmtLigaAtual->fetch(PDO::FETCH_ASSOC);

    if (!$ligaAtual) {
        header("Location: ligas.php?erro=" . urlencode("Você não participa dessa liga."));
        exit;
    }

    // Calcula o ranking geral da liga somando todas as partidas dos membros.
    $sqlRankingLigaGeral = "SELECT 
                                usuarios.nome,
                                COALESCE(SUM(partidas.pontos), 0) AS total_pontos
                            FROM usuarios_ligas
                            INNER JOIN usuarios
                                ON usuarios.id_usuario = usuarios_ligas.id_usuario
                            LEFT JOIN partidas
                                ON partidas.id_usuario = usuarios.id_usuario
                            WHERE usuarios_ligas.id_liga = :id_liga
                            GROUP BY usuarios.id_usuario, usuarios.nome
                            ORDER BY total_pontos DESC";

    $stmtRankingLigaGeral = $pdo->prepare($sqlRankingLigaGeral);
    $stmtRankingLigaGeral->bindValue(":id_liga", $idLiga, PDO::PARAM_INT);
    $stmtRankingLigaGeral->execute();

    $rankingLigaGeral = $stmtRankingLigaGeral->fetchAll(PDO::FETCH_ASSOC);

    // Calcula o ranking semanal da liga considerando apenas partidas dos últimos 7 dias.
    $sqlRankingLigaSemanal = "SELECT 
                                  usuarios.nome,
                                  COALESCE(SUM(partidas.pontos), 0) AS total_pontos
                              FROM usuarios_ligas
                              INNER JOIN usuarios
                                  ON usuarios.id_usuario = usuarios_ligas.id_usuario
                              LEFT JOIN partidas
                                  ON partidas.id_usuario = usuarios.id_usuario
                                  AND partidas.jogada_em >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                              WHERE usuarios_ligas.id_liga = :id_liga
                              GROUP BY usuarios.id_usuario, usuarios.nome
                              ORDER BY total_pontos DESC";

    $stmtRankingLigaSemanal = $pdo->prepare($sqlRankingLigaSemanal);
    $stmtRankingLigaSemanal->bindValue(":id_liga", $idLiga, PDO::PARAM_INT);
    $stmtRankingLigaSemanal->execute();

    $rankingLigaSemanal = $stmtRankingLigaSemanal->fetchAll(PDO::FETCH_ASSOC);
}

// Calcula o ranking geral do sistema, considerando todos os usuários cadastrados.
$rankingGeralSistema = [];
$rankingSemanalSistema = [];

if ($step === "minhas") {
    $sqlRankingGeralSistema = "SELECT 
                                  usuarios.nome,
                                  COALESCE(SUM(partidas.pontos), 0) AS total_pontos
                               FROM usuarios
                               LEFT JOIN partidas
                                  ON partidas.id_usuario = usuarios.id_usuario
                               GROUP BY usuarios.id_usuario, usuarios.nome
                               ORDER BY total_pontos DESC
                               LIMIT 10";

    $stmtRankingGeralSistema = $pdo->prepare($sqlRankingGeralSistema);
    $stmtRankingGeralSistema->execute();

    $rankingGeralSistema = $stmtRankingGeralSistema->fetchAll(PDO::FETCH_ASSOC);

    // Calcula o ranking semanal do sistema, considerando partidas dos últimos 7 dias.
    $sqlRankingSemanalSistema = "SELECT 
                                    usuarios.nome,
                                    COALESCE(SUM(partidas.pontos), 0) AS total_pontos
                                 FROM usuarios
                                 LEFT JOIN partidas
                                    ON partidas.id_usuario = usuarios.id_usuario
                                    AND partidas.jogada_em >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                 GROUP BY usuarios.id_usuario, usuarios.nome
                                 ORDER BY total_pontos DESC
                                 LIMIT 10";

    $stmtRankingSemanalSistema = $pdo->prepare($sqlRankingSemanalSistema);
    $stmtRankingSemanalSistema->execute();

    $rankingSemanalSistema = $stmtRankingSemanalSistema->fetchAll(PDO::FETCH_ASSOC);
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

  <button type="button" class="btn back-button" onclick="history.back()" aria-label="Voltar">
    ← Voltar
  </button>

<?php if ($step == "criar") { ?>

  <main class="container" aria-labelledby="titulo-criar">
    <header>
      <h1 id="titulo-criar">Criar Liga</h1>
    </header>

    <form class="card" action="ligas.php?step=criar" method="post" aria-labelledby="titulo-criar">
      <div class="form-group">
        <label for="nome-liga">Nome da Liga</label>
        <input id="nome-liga" name="nome_liga" type="text" required />
        <p class="msg-erro"></p>
      </div>

      <div class="form-group">
        <label for="palavra-chave">Palavra-chave</label>
        <input id="palavra-chave" name="palavra_chave" type="text" required />
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

    <form class="card" action="ligas.php?step=entrar" method="post" aria-labelledby="titulo-entrar">
      <div class="form-group">
        <label for="nome-liga-entrada">Nome da Liga</label>
        <input id="nome-liga-entrada" name="nome_liga" type="text" required />
        <p class="msg-erro"></p>
      </div>

      <div class="form-group">
        <label for="senha-liga">Palavra-chave</label>
        <input id="senha-liga" name="palavra_chave" type="password" required />
        <p class="msg-erro"></p>
      </div>

      <button type="submit" class="btn">Entrar</button>
    </form>
  </main>

<?php } elseif ($step == "detalhes") { ?>

  <main class="container" aria-labelledby="titulo-liga">
    <header>
      <h1 id="titulo-liga">
        <?php echo htmlspecialchars($ligaAtual["nome"] ?? "Detalhes da Liga"); ?>
      </h1>
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
          <?php if (count($rankingLigaGeral) === 0) { ?>
            <tr>
              <td colspan="3">Nenhum jogador encontrado.</td>
            </tr>
          <?php } else { ?>
            <?php foreach ($rankingLigaGeral as $posicao => $jogador) { ?>
              <tr>
                <td><?php echo $posicao + 1; ?>º</td>
                <td><?php echo htmlspecialchars($jogador["nome"]); ?></td>
                <td><?php echo (int) $jogador["total_pontos"]; ?> pts</td>
              </tr>
            <?php } ?>
          <?php } ?>
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
          <?php if (count($rankingLigaSemanal) === 0) { ?>
            <tr>
              <td colspan="3">Nenhum jogador encontrado.</td>
            </tr>
          <?php } else { ?>
            <?php foreach ($rankingLigaSemanal as $posicao => $jogador) { ?>
              <tr>
                <td><?php echo $posicao + 1; ?>º</td>
                <td><?php echo htmlspecialchars($jogador["nome"]); ?></td>
                <td><?php echo (int) $jogador["total_pontos"]; ?> pts</td>
              </tr>
            <?php } ?>
          <?php } ?>
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

      <?php if (count($minhasLigas) === 0) { ?>
        <p>Você ainda não participa de nenhuma liga.</p>
      <?php } else { ?>
        <?php foreach ($minhasLigas as $liga) { ?>
          <div class="liga-item">
            <span><?php echo htmlspecialchars($liga["nome"]); ?></span>

            <a href="ligas.php?step=detalhes&id_liga=<?php echo (int) $liga["id_liga"]; ?>">
              Ver
            </a>
          </div>
        <?php } ?>
      <?php } ?>
    </section>

    <nav class="acoes" aria-label="Ações de ligas">
      <a href="ligas.php?step=criar" class="btn">Criar Liga</a>
      <a href="ligas.php?step=entrar" class="btn">Entrar em Liga</a>
    </nav>

    <section class="card" aria-labelledby="titulo-pontuacao">
      <h2 id="titulo-pontuacao">Pontuação Geral</h2>

      <h3>Ranking Geral</h3>

      <table>
        <thead>
          <tr>
            <th>Posição</th>
            <th>Jogador</th>
            <th>Total</th>
          </tr>
        </thead>

        <tbody>
          <?php if (count($rankingGeralSistema) === 0) { ?>
            <tr>
              <td colspan="3">Nenhuma pontuação registrada.</td>
            </tr>
          <?php } else { ?>
            <?php foreach ($rankingGeralSistema as $posicao => $jogador) { ?>
              <tr>
                <td><?php echo $posicao + 1; ?>º</td>
                <td><?php echo htmlspecialchars($jogador["nome"]); ?></td>
                <td><?php echo (int) $jogador["total_pontos"]; ?> pts</td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>

      <br>

      <h3>Ranking Semanal</h3>

      <table>
        <thead>
          <tr>
            <th>Posição</th>
            <th>Jogador</th>
            <th>Total Semanal</th>
          </tr>
        </thead>

        <tbody>
          <?php if (count($rankingSemanalSistema) === 0) { ?>
            <tr>
              <td colspan="3">Nenhuma pontuação registrada nesta semana.</td>
            </tr>
          <?php } else { ?>
            <?php foreach ($rankingSemanalSistema as $posicao => $jogador) { ?>
              <tr>
                <td><?php echo $posicao + 1; ?>º</td>
                <td><?php echo htmlspecialchars($jogador["nome"]); ?></td>
                <td><?php echo (int) $jogador["total_pontos"]; ?> pts</td>
              </tr>
            <?php } ?>
          <?php } ?>
        </tbody>
      </table>
    </section>
  </main>

<?php } ?>

</body>
</html>