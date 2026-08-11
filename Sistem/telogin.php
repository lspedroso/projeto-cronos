<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include_once('config.php');

$mensagem = "";

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se é admin
    if ($email === "admin" && $senha === "admin") {
        $_SESSION['email'] = $email;
        header("Location: gerenciar.php");
        exit();
    }

    // Consulta o usuário no banco
    $sql = "SELECT * FROM tlogin WHERE email = '$email' LIMIT 1";
    $result = $conexao->query($sql);

    if (!$result) {
        die("Erro na query: " . $conexao->error); // se der erro de SQL, mostra aqui
    }

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        // Se as senhas NÃO estão criptografadas
        if ($senha === $usuario['senha']) {
            $_SESSION['email'] = $email;
            header("Location: calendarios.php");
            exit();
        } else {
            $_SESSION['mensagem'] = "Senha incorreta!";
        }
    } else {
        $_SESSION['mensagem'] = "Usuário não encontrado!";
    }

    // redireciona de volta pro agendamento.php pra exibir a mensagem
    header("Location: agendamento.php");
    exit;
} else {
    $_SESSION['mensagem'] = "Preencha todos os campos!";
    header("Location: agendamento.php");
    exit;
}

