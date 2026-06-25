<?php
session_start();
require_once "includes/conexao.php";

$step = $_GET["step"] ?? "dados";
$erro = "";

function validarNome($nome) {
    if ($nome === "") {
        return "Nome não deve estar vazio.";
    }

    if (strlen($nome) < 3 || strlen($nome) > 16) {
        return "Nome deve ter entre 3 e 16 caracteres.";
    }

    if (!preg_match("/^[a-zA-Z0-9_. -]+$/", $nome)) {
        return "Nome possui caracteres inválidos.";
    }

    if (preg_match("/^[_. -]+$/", $nome)) {
        return "Nome não pode conter somente ponto, hífen, underline e espaços.";
    }

    return "";
}

function validarSenha($senha) {
    if ($senha === "") {
        return "Senha não deve estar vazia.";
    }

    if (!preg_match("/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\dA-Za-z ])[\dA-Za-z@$!%*?&_-]{8,}$/", $senha)) {
        return "Senha fraca. Ela deve ter ao menos 8 caracteres, letra maiúscula, letra minúscula, número e caractere especial.";
    }

    return "";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["nome"], $_POST["email"])) {
        $nome = trim($_POST["nome"]);
        $email = trim($_POST["email"]);

        $erro = validarNome($nome);

        if ($erro === "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "E-mail inválido.";
        }

        if ($erro === "") {
            $_SESSION["cadastro_nome"] = $nome;
            $_SESSION["cadastro_email"] = $email;

            header("Location: cadastro.php?step=senha");
            exit;
        }
    }

    if (isset($_POST["senha"], $_POST["confirmar_senha"])) {
        $senha = $_POST["senha"];
        $confirmarSenha = $_POST["confirmar_senha"];

        $nome = $_SESSION["cadastro_nome"] ?? "";
        $email = $_SESSION["cadastro_email"] ?? "";

        if ($nome === "" || $email === "") {
            $erro = "Dados iniciais do cadastro não encontrados. Preencha nome e e-mail novamente.";
            $step = "dados";
        } else {
            $erro = validarSenha($senha);

            if ($erro === "" && $senha !== $confirmarSenha) {
                $erro = "Confirmação de senha não confere.";
            }

            if ($erro === "") {
                $sqlVerificar = "SELECT id_usuario FROM usuarios WHERE nome = :nome OR email = :email";
                $stmtVerificar = $pdo->prepare($sqlVerificar);
                $stmtVerificar->bindValue(":nome", $nome);
                $stmtVerificar->bindValue(":email", $email);
                $stmtVerificar->execute();

                if ($stmtVerificar->rowCount() > 0) {
                    $erro = "Nome ou e-mail já cadastrado.";
                    $step = "dados";
                } else {
                    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                    $sqlInserir = "INSERT INTO usuarios (nome, email, senha_hash) 
                                   VALUES (:nome, :email, :senha_hash)";

                    $stmtInserir = $pdo->prepare($sqlInserir);
                    $stmtInserir->bindValue(":nome", $nome);
                    $stmtInserir->bindValue(":email", $email);
                    $stmtInserir->bindValue(":senha_hash", $senhaHash);
                    $stmtInserir->execute();

                    unset($_SESSION["cadastro_nome"]);
                    unset($_SESSION["cadastro_email"]);

                    header("Location: login.php?cadastro=ok");
                    exit;
                }
            }
        }
    }
}
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
  <script src="js/cadastro.js" defer></script>
</head>
<body>
  <main class="auth-page">
    <section class="auth-card" aria-labelledby="titulo-cadastro">
      <h1 id="titulo-cadastro" class="auth-title">Cadastre-se</h1>

      <p class="auth-subtitle">
        Já possui uma conta?
        <a href="login.php" class="auth-link">Faça login.</a>
      </p>

      <?php if ($erro !== "") { ?>
        <p class="msg-erro" style="text-align:center; color:#ff3434; margin-bottom: 16px;">
          <?php echo htmlspecialchars($erro); ?>
        </p>
      <?php } ?>

      <?php if ($step == "senha") { ?>

        <form class="auth-form" action="cadastro.php?step=senha" method="post">
          <div class="field">
              <input type="password" id="senha" name="senha" placeholder="Senha:"/>
              <ul class="msg-erro req-senha">
                <span>Senha deve possuir:</span>
                <li>ao menos 8 caracteres;</li>
                <li>letras maiúsculas;</li>
                <li>letras minúsculas;</li>
                <li>números;</li>
                <li>ao menos um caractere especial (@$!%*?&_-).</li>
              </ul>
          </div>

          <div class="field">
              <input type="password" id="confirmar-senha" name="confirmar_senha" placeholder="Confirmar Senha:"/>
              <p class="msg-erro"></p>
          </div>

          <button type="submit" class="btn-submit" id="btn-cadastro">Criar Conta</button>
        </form>

      <?php } else { ?>

        <form class="auth-form" action="cadastro.php" method="post">
          <div class="field">
            <input 
              type="text" 
              id="nome" 
              name="nome" 
              placeholder="Nome:" 
              value="<?php echo htmlspecialchars($_SESSION["cadastro_nome"] ?? ""); ?>"
            />
            <p class="msg-erro"></p>
          </div>

          <div class="field">
            <input 
              type="email" 
              id="email" 
              name="email" 
              placeholder="E-mail:" 
              value="<?php echo htmlspecialchars($_SESSION["cadastro_email"] ?? ""); ?>"
            />
            <p class="msg-erro"></p>
          </div>
<p class="auth-subtitle">
        Esqueceu sua senha?
        <a href="recuperar-senha.php" class="auth-link">Recupere aqui.</a>
      </p>
          <button type="submit" class="btn-submit" id="btn-continuar">Continuar</button>
        </form>

      <?php } ?>
    </section>
  </main>
</body>
</html>