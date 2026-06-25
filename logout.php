<?php

// Inicia a sessão para permitir limpar os dados do usuário logado.
session_start();

// Remove todos os dados armazenados na sessão.
$_SESSION = [];

// Encerra a sessão atual.
session_destroy();

// Redireciona o usuário para a tela de login.
header("Location: login.php");
exit;