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
    <div id="nome-jogador" class="historico-jogador">Jogador</div>

    <table class="historico-tabela">
      <thead>
        <tr>
          <th>Data</th>
          <th>Pontuação</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td id="data-historico">data</td>
          <td id="pts-historico">pts</td>
        </tr>
      </tbody>
    </table>
  </div>
</section>
  </main>
</body>
</html>