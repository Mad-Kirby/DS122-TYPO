<?php

// Inicia a sessão apenas se ela ainda não estiver ativa.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se existe um usuário autenticado na sessão.
// Caso não exista, redireciona para a tela de login.
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}