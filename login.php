<?php
// Inicia a sessão para permitir salvar os dados do usuário logado.
session_start();

// Importa a conexão com o banco de dados.
require_once "includes/conexao.php";

$erro = "";
$sucesso = "";

// Exibe mensagem de sucesso quando o usuário acabou de se cadastrar.
if (isset($_GET["cadastro"]) && $_GET["cadastro"] === "ok") {
    $sucesso = "Cadastro realizado com sucesso! Faça login para continuar.";
}

// Processa o formulário de login quando ele é enviado.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identificador = trim($_POST["nome"] ?? "");
    $senha = $_POST["senha"] ?? "";

    if ($identificador === "" || $senha === "") {
        $erro = "Preencha nome/e-mail e senha.";
    } else {
        // Busca o usuário pelo nome ou pelo e-mail informado no formulário.
        $sql = "SELECT id_usuario, nome, email, senha_hash 
                FROM usuarios 
                WHERE nome = :identificador OR email = :identificador 
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":identificador", $identificador);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha digitada corresponde ao hash salvo no banco.
        if (!$usuario || !password_verify($senha, $usuario["senha_hash"])) {
            $erro = "Nome/e-mail ou senha inválidos.";
        } else {
            // Renova o ID da sessão após o login para aumentar a segurança.
            session_regenerate_id(true);

            $_SESSION["usuario_id"] = $usuario["id_usuario"];
            $_SESSION["usuario_nome"] = $usuario["nome"];
            $_SESSION["usuario_email"] = $usuario["email"];

            header("Location: jogo.php?step=como-jogar");
            exit;
        }
    }
}
?>
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

      <?php if ($sucesso !== "") { ?>
        <p style="text-align:center; color:#3aff34; margin-bottom:16px;">
          <?php echo htmlspecialchars($sucesso); ?>
        </p>
      <?php } ?>

      <?php if ($erro !== "") { ?>
        <p style="text-align:center; color:#ff3434; margin-bottom:16px;">
          <?php echo htmlspecialchars($erro); ?>
        </p>
      <?php } ?>

      <form class="auth-form" action="login.php" method="post">
        <div class="field">
          <input 
            type="text" 
            id="nome" 
            name="nome" 
            placeholder="Nome ou e-mail:" 
            value="<?php echo htmlspecialchars($_POST["nome"] ?? ""); ?>"
          />
          <p class="msg-erro" id="erro-nome"></p>
        </div>

        <div class="field">
          <input type="password" id="senha" name="senha" placeholder="Senha:" />
          <p class="msg-erro" id="erro-senha"></p>
        </div>
<p class="auth-subtitle">
        Esqueceu sua senha?
        <a href="recuperar-senha.php" class="auth-link">Recupere aqui.</a>
      </p>
        <button type="submit" class="btn-submit">Continuar</button>
      </form>
    </section>
  </main>
</body>
</html>