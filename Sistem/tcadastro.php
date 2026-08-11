<?php   
    
    if(isset($_POST['submit']))
    {
        // print_r('Nome: ' . $_POST['nome']);
        // print_r('<br>');
        // print_r('Email: ' . $_POST['email']);
        // print_r('<br>');
        // print_r('Materia: ' . $_POST['materia']);
        // print_r('<br>');
        // print_r('Senha: ' . $_POST['senha']);

        include_once('config.php');

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $materia = $_POST['materia'];
        $senha = $_POST['senha'];

        $resul = mysqli_query($conexao, "INSERT INTO tlogin(nome,email,materia,senha) VALUES('$nome','$email','$materia','$senha')");
        header('location: agendamento.php');

    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cronos</title>
    <style>
        body{
            font-family: 'ClashDisplay', sans-serif;
        }
        .box{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.10);
            padding: 50px;
            border-radius: 15px;
            width: 30%;
        }
        legend{
            color: white;
            font-size: 35px;
            text-align: center;
        }
        .inputbox{
            position: relative;
            color: white;
        }
        .inputUser{
            background: none;
            border: none;
            border-bottom: 1px solid white;
            outline: none;
            color: black;
            font-size: 15px;
            width: 100%;
            letter-spacing: 1px;
        }
        .labelInput{
            position: absolute;
            top: 0px;
            left: 0px;
            pointer-events: none;
            transition: .5s;
        }
        .inputUser:focus ~ .labelInput,
        .inputUser:valid ~ .labelInput{
            top: -15px;
            font-size: 12px;
            color: gray;
        }
        #submit{
            background-image: linear-gradient(to right, rgb(63, 6, 99));
            width: 100%;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 15px;
            cursor: pointer;
        }
        #submit:hover{
            background-image: linear-gradient(to right, rgb(157, 78, 221));
        }
    </style>
</head>
<body>
    <div class="btn-voltar">
                <a href="agendamento.php">&#8592;</a>
            </div>
    <div>
        <div class="box">
            <form action="tcadastro.php" method="POST">
                    <legend><b>Cadastro</b></legend>
                    <br>
                    <br>
                    <br>
                    <div class="inputbox">
                        <input type="text" name="nome" id="nome" class="inputUser" required> 
                        <label class="labelInput" for="nome">Nome Completo</label>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="inputbox">
                        <input type="text" name="email" id="email" class="inputUser" required> 
                        <label class="labelInput" for="email">Email</label>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="inputbox">
                        <input type="text" name="materia" id="materia" class="inputUser" required> 
                        <label class="labelInput" for="materia">Materia</label>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="inputbox">
                        <input type="password" name="senha" id="senha" class="inputUser" required> 
                        <label class="labelInput" for="senha">Senha</label>
                    </div>
                    <br>
                    <br>
                    <br>
                    <input type="submit" name="submit" id="submit">
            </form>
        </div>
    </div>
</body>
</html>