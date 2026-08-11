<?php
include_once("config.php");

// salvar agendamento
if (isset($_POST['salvar'])) {
    $dia       = $_POST['dia_semana'];
    $local     = $_POST['periodo'];
    $horario   = $_POST['horario'];
    $professorr = $_POST['professor'];

    $check_sql = "SELECT * FROM agendamentos WHERE dia_semana='$dia' AND local='$local' AND horario='$horario'";
    $check_result = $conexao->query($check_sql);

    if ($check_result->num_rows > 0) {
        $mensagem_texto = "Erro: já existe um agendamento nesse dia, local e horário!";
        $mensagem_tipo  = "error";
    } else {
        $sql = "INSERT INTO agendamentos (dia_semana, local, horario, professorr) 
                VALUES ('$dia','$local','$horario','$professorr')";
        if ($conexao->query($sql) === TRUE) {
            $mensagem_texto = "Agendamento salvo com sucesso!";
            $mensagem_tipo  = "success";
        } else {
            $mensagem_texto = "Erro ao salvar: " . $conexao->error;
            $mensagem_tipo  = "error";
        }
    }
}

// buscar dados
$agendamentos = [];
$sql = "SELECT * FROM agendamentos ORDER BY dia_semana, local, horario";
$result = $conexao->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Agendamentos</title>
    <style>
        

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body{
            font-family: 'Clash Display', sans-serif;
            background-image: url('Imagens/background.jpg');
            min-height: 100vh;
            color: #333;
        }
        @font-face {
            font-family: 'Clash Display';
            src: url('Ff/ClashDisplay-Variable.ttf') format('truetype');
        }

        .container{
            width: 95%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 0;
        }

        .header{
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header h1{
            color: #3f0663;
            font-size: 32px;
        }


        form{
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .form-row{
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group{
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        label{
            margin-bottom: 5px;
            font-weight: 600;
            color: #3f0663;

        }

        select, textarea{
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 30px;
            font-size: 15px;
            transition: 0.3s;
        }
        select:focus, textarea:focus{
            outline: none;
            border-color: #9d4edd;
            box-shadow: 0 0 5px rgba(157,78,221,0.3);
        }

        textarea{
            resize: none; /* desativa o redimensionamento */
            min-height: 40px; /* altura menor */
            max-height: 60px; /* limite de altura */
            padding: 10px;
            font-size: 15px;
        }

        .btn-salvar{
            background-color: #3f0663;
            width: 100%;
            border: none;
            padding: 15px;
            border-radius: 12px;
            font-size: 17px;
            cursor: pointer;
            color: white;
            font-weight: 700;
            transition: 0.3s;
            margin-top: 15px; /* espaço entre textarea e botão */
        }
        .btn-salvar:hover{ opacity: 0.9; }

        h2{
            text-align: center;
            color: #3f0663;
            margin-bottom: 15px;
            font-size: 32px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background: white;
            
        }
        table th, table td{
            padding: 12px 10px;
            text-align: center;
            border: 1px solid #adadadff;
        }
        table th{
            background: linear-gradient(to right, #653086ff, #9d4edd);
            color: white;
        }
        table tr:nth-child(even){ background: #f9f9f9; }

        .day-header{
            color: black;
            font-weight: bold;
            text-align: center;

        }
        .barra{
            display: block;
            justify-content:space-between ; 
            align-items: center;
            background-color: white;
            padding: 35px 30px;
            position:fixed;
            width:100%;
            top:0;
            left: 0;
        }


        @media(max-width: 768px){
            .form-row { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="btn-voltar">
                <a href="index.html">&#8592;</a>
            </div>
            <div class="barra">

            </div>
    <div class="container">
        <div class="header">
            <h1>Painel de Agendamento</h1>
        </div>

        <?php if (!empty($mensagem_texto)): ?>
            <div class="mensagem <?php echo $mensagem_tipo === 'success' ? 'sucesso' : 'erro'; ?>">
                <?php echo $mensagem_texto; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="dia_semana">Dia da Semana:</label>
                    <select name="dia_semana" id="dia_semana" required>
                        <option value="">Selecione o dia</option>
                        <option value="Segunda">Segunda-feira</option>
                        <option value="Terça">Terça-feira</option>
                        <option value="Quarta">Quarta-feira</option>
                        <option value="Quinta">Quinta-feira</option>
                        <option value="Sexta">Sexta-feira</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="periodo">Local:</label>
                    <select name="periodo" id="periodo" required>
                        <option value="">Selecione o local</option>
                        <option value="Laboratório 21">Laboratório 21</option>
                        <option value="Laboratório 25">Laboratório 25</option>
                        <option value="Laboratório 28">Laboratório 28</option>
                        <option value="Biblioteca">Biblioteca</option>
                        <option value="Auditório">Auditório</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="horario">Horário:</label>
                    <select name="horario" id="horario" required>
                        <option value="">Selecione o horário</option>
                        <option value="07:00 - 07:50">07:00 - 07:50</option>
                        <option value="07:50 - 08:40">07:50 - 08:40</option>
                        <option value="09:00 - 09:50">09:00 - 09:50</option>
                        <option value="09:50 - 10:40">09:50 - 10:40</option>
                        <option value="10:40 - 11:30">10:40 - 11:30</option>
                        <option value="13:00 - 13:50">13:00 - 13:50</option>
                        <option value="13:50 - 14:40">13:50 - 14:40</option>
                        <option value="14:40 - 15:30">14:40 - 15:30</option>
                        <option value="15:45 - 16:35">15:45 - 16:35</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="professor">Professor:</label>
                <textarea name="professor" id="professor" placeholder="Digite seu nome" required></textarea>
            </div>
            <button type="submit" name="salvar" class="btn-salvar">Salvar Agendamento</button>
        </form>

        <h2>Agendamentos Semanais</h2>
        <table>
            <thead>
                <tr>
                    <th>Dia</th>
                    <th>Local</th>
                    <th>Horário</th>
                    <th>Professor</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $dias_semana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'];
                foreach($dias_semana as $dia):
                    $dia_agendamentos = array_filter($agendamentos, fn($a) => $a['dia_semana'] === $dia);
                ?>
                <tr class="day-header">
                    <td colspan="4"><?php echo $dia; ?></td>
                </tr>

                <?php if(!empty($dia_agendamentos)): ?>
                    <?php foreach($dia_agendamentos as $item): ?>
                        <tr>
                            <td><?php echo $item['dia_semana']; ?></td>
                            <td><?php echo $item['local']; ?></td>
                            <td><?php echo $item['horario']; ?></td>
                            <td><?php echo $item['professorr']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="color:#555;">Nenhum agendamento cadastrado</td>
                    </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
