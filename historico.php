<?php
// Protege o histórico para que apenas usuários logados possam acessar.
require_once "includes/auth.php";

// Importa a conexão com o banco de dados.
require_once "includes/conexao.php";

// Recupera os dados do usuário logado a partir da sessão.
$idUsuario = $_SESSION["usuario_id"];
$nomeUsuario = $_SESSION["usuario_nome"] ?? "Jogador";

// Busca todas as partidas jogadas pelo usuário logado,
// ordenando da mais recente para a mais antiga.
$sqlPartidas = "SELECT id_partida, pontos, jogada_em
                FROM partidas
                WHERE id_usuario = :id_usuario
                ORDER BY jogada_em DESC";

$stmtPartidas = $pdo->prepare($sqlPartidas);
$stmtPartidas->bindValue(":id_usuario", $idUsuario, PDO::PARAM_INT);
$stmtPartidas->execute();

// Armazena todas as partidas encontradas em um array para exibir na tabela.
$partidas = $stmtPartidas->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Histórico</title>
  <link rel="stylesheet" href="css/ligas.css" />
</head>
<body>
  <main class="container">
    <button id="voltar-historico"class="btn back-button" onclick="history.back()">← Voltar</button>

    <section class="historico-card">
      <h1 class="historico-title">Histórico</h1>

      <div class="historico-bloco">
        <div id="nome-jogador" class="historico-jogador">
          <?php echo htmlspecialchars($nomeUsuario); ?>
        </div>

        <table class="historico-tabela">
          <thead>
            <tr>
              <th>Data</th>
              <th>Pontuação</th>
            </tr>
          </thead>

          <tbody>
            <?php if (count($partidas) === 0) { ?>
              <tr>
                <td colspan="2">Nenhuma partida jogada ainda.</td>
              </tr>
            <?php } else { ?>
            <!-- // Percorre as partidas encontradas e exibe a data e a pontuação de cada uma. -->
              <?php foreach ($partidas as $partida) { ?>
                <tr>
                  <td>
                    <?php echo date("d/m/Y H:i", strtotime($partida["jogada_em"])); ?>
                  </td>
                  <td>
                    <?php echo (int) $partida["pontos"]; ?> pts
                  </td>
                </tr>
              <?php } ?>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</body>
</html>