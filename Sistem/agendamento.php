<?php
session_start();
$mensagem = isset($_SESSION['mensagem']) ? $_SESSION['mensagem'] : "";
unset($_SESSION['mensagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="agendamento.css">
    <title>Cronos</title>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="index.html" class="logo">
            <img src="imagens/WhatsApp_Image_2025-09-05_at_21.24.20-removebg-preview (1).png"
                 alt="Logo Cronos">
        </a>
        <div class="hamburger" onclick="toggleMenu()">☰</div>
        <ul>
            <br>
            <li><a href="index.html">Home</a></li>
            <li><a href="agendamento.php">Agendamento</a></li>
            <li><a href="sobre.html">Sobre</a></li>
            <li><a href="menu.html">Menu</a></li>
            <li><a href="curiosidades.html">Curiosidades</a></li>
            <li><a href="team.html">Team</a></li>
        </ul>
    </nav>

    <!-- Formulário de Login -->
    <div class="box">
        <form action="telogin.php" method="POST">
            <legend><b>Login</b></legend>
            <br><br>
            <div class="inputbox">
                <input type="text" name="email" id="email" class="inputUser" required>
                <label class="labelInput" for="email">Email</label>
            </div>
            <br><br>
            <div class="inputbox">
                <input type="password" name="senha" id="senha" class="inputUser" required>
                <label class="labelInput" for="senha">Senha</label>
            </div>
            <br><br>
            <input class="inputSubmit" type="submit" name="submit" value="Enviar">
            <br><br>
            <div class="mensagem">
                <?php echo $mensagem; ?>
            </div>
            <br>
            <div class="cadastro">
                <a href="tcadastro.php">Cadastre-se</a>
            </div>
        </form>
    </div>

    <script>
        function toggleMenu() {
            document.querySelector(".navbar ul").classList.toggle("show");
        }
        document.querySelectorAll(".navbar ul li a").forEach(link => {
            link.addEventListener("click", () => {
                document.querySelector(".navbar ul").classList.remove("show");
            });
        });
    </script>
</body>
</html>