<?php
include_once("config.php");

// Deletar agendamento
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM agendamentos WHERE id=$id";
    $conexao->query($sql);
    header("Location: gerenciar.php");
    exit;
}

// Editar agendamento
if (isset($_POST['editar'])) {
    $id        = intval($_POST['id']);
    $dia       = $_POST['dia_semana'];
    $local     = $_POST['local'];
    $horario   = $_POST['horario'];
    $atividade = $_POST['atividade'];

    // Verifica conflito (exceto o próprio registro)
    $check_sql = "SELECT * FROM agendamentos WHERE dia_semana='$dia' AND local='$local' AND horario='$horario' AND id<>$id";
    $check_result = $conexao->query($check_sql);

    if ($check_result->num_rows > 0) {
        $mensagem_texto = "Erro: já existe um agendamento nesse dia, local e horário!";
        $mensagem_tipo  = "error";
    } else {
        $sql = "UPDATE agendamentos SET dia_semana='$dia', local='$local', horario='$horario', atividade='$atividade' WHERE id=$id";
        if ($conexao->query($sql) === TRUE) {
            $mensagem_texto = "Agendamento atualizado com sucesso!";
            $mensagem_tipo  = "success";
        } else {
            $mensagem_texto = "Erro ao atualizar: " . $conexao->error;
            $mensagem_tipo  = "error";
        }
    }
}

// Buscar agendamentos
$agendamentos = [];
$sql = "SELECT * FROM agendamentos ORDER BY dia_semana, local, horario";
$result = $conexao->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $agendamentos[] = $row;
    }
}

// Se estiver editando, carregar os dados
$edit_item = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $res = $conexao->query("SELECT * FROM agendamentos WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $edit_item = $res->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerenciar Agendamentos</title>
<style>
    body {
        font-family: 'ClashDisplay', sans-serif;
        margin: 0;
        padding: 0;
    }
    .box {
        max-width: 800px;
        margin: 50px auto;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #3f0663;
        margin-bottom: 20px;
    }
    form {
        margin-bottom: 20px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 10px;
    }
    label {
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }
    select,
    textarea {
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }
    textarea {
        resize: vertical;
        min-height: 60px;
    }
    button {
        padding: 10px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: bold;
        background: linear-gradient(to right,rgb(63,6,99),rgb(157,78,221));
        color: white;
        margin-top: 5px;
    }
    button:hover {
        opacity: 0.9;
    }
    .mensagem.sucesso {
        background: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-align: center;
    }
    .mensagem.erro {
        background: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
        text-align: center;
    }
    .agenda-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .agenda-item:last-child {
        border-bottom: none;
    }
    .agenda-info span {
        font-weight: bold;
        margin-right: 5px;
    }
    a.delete {
        color: red;
        text-decoration: none;
        margin-left: 10px;
    }
    a.delete:hover {
        text-decoration: underline;
    }
    .btn-sair {
        padding: 8px 15px;
        background: linear-gradient(to right, rgb(63,6,99), rgb(157,78,221));
        color: white;
        text-decoration: none;
        border-radius: 8px;
        font-weight: bold;
        display: inline-block;
        transition: 0.3s;
    }
    .btn-sair:hover {
        opacity: 0.9;
    }
</style>
</head>
<body>
    <div style="text-align:right; margin-bottom:15px;">
        <a href="sair.php" class="btn-sair">Sair</a>
    </div>

    <div class="box">
        <h2>Gerenciar Agendamentos</h2>

        <?php if(!empty($mensagem_texto)): ?>
            <div class="mensagem <?php echo $mensagem_tipo==='success'?'sucesso':'erro'; ?>">
                <?php echo $mensagem_texto; ?>
            </div>
        <?php endif; ?>

        <?php if($edit_item): ?>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
                <div class="form-group">
                    <label for="dia_semana">Dia da Semana:</label>
                    <select name="dia_semana" required>
                        <option value="Segunda" <?php if($edit_item['dia_semana']=='Segunda') echo 'selected'; ?>>Segunda-feira</option>
                        <option value="Terça" <?php if($edit_item['dia_semana']=='Terça') echo 'selected'; ?>>Terça-feira</option>
                        <option value="Quarta" <?php if($edit_item['dia_semana']=='Quarta') echo 'selected'; ?>>Quarta-feira</option>
                        <option value="Quinta" <?php if($edit_item['dia_semana']=='Quinta') echo 'selected'; ?>>Quinta-feira</option>
                        <option value="Sexta" <?php if($edit_item['dia_semana']=='Sexta') echo 'selected'; ?>>Sexta-feira</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="local">Local:</label>
                    <select name="local" required>
                        <?php 
                        $locais = ['Laboratório 21','Laboratório 25','Laboratório 28','Biblioteca','Auditório'];
                        foreach($locais as $l) {
                            $sel = $edit_item['local']==$l?'selected':'';
                            echo "<option value='$l' $sel>$l</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="horario">Horário:</label>
                    <select name="horario" required>
                        <?php
                        $horarios = ['07:00 - 07:50','07:50 - 08:40','09:00 - 09:50','09:50 - 10:40','10:40 - 11:30','13:00 - 13:50','13:50 - 14:40','14:40 - 15:30','15:45 - 16:35'];
                        foreach($horarios as $h) {
                            $sel = $edit_item['horario']==$h?'selected':'';
                            echo "<option value='$h' $sel>$h</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="atividade">Atividade:</label>
                    <textarea name="atividade" required><?php echo $edit_item['atividade']; ?></textarea>
                </div>
                <button type="submit" name="editar">Atualizar Agendamento</button>
            </form>
            <hr>
        <?php endif; ?>

        <?php if(!empty($agendamentos)): ?>

    <style>
@import url('https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;600;700&display=swap');

/* ===== Reset e Geral ===== */
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Clash Display', sans-serif;
    background: #f0e7ff;
    color: #333;
    min-height: 100vh;
}

/* ===== Container ===== */
.container {
    width: 95%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 0;
}

/* ===== Cabeçalho ===== */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header h1 {
    color: #3f0663;
    font-size: 32px;
}

.btn-sair {
    padding: 10px 20px;
    background: red;
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: bold;
    transition: 0.3s;
}

.btn-sair:hover {
    opacity: 0.5;
}

/* ===== Mensagens ===== */
.mensagem {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 10px;
    font-weight: bold;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    font-size: 16px;
}

.mensagem.sucesso {
    background: #d4edda;
    color: #155724;
}

.mensagem.erro {
    background: #f8d7da;
    color: #721c24;
}

/* ===== Formulário ===== */
form {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

form p {
    text-align: center;
    margin-bottom: 15px;
    font-size: 18px;
    color: #555;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 5px;
    font-weight: 600;
    color: #3f0663;
}

select, textarea {
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 10px;
    font-size: 15px;
    transition: 0.3s;
}

select:focus, textarea:focus {
    outline: none;
    border-color: #9d4edd;
    box-shadow: 0 0 5px rgba(157,78,221,0.3);
}

textarea {
    resize: none;
    min-height: 40px;
    max-height: 60px;
}

/* Botão Salvar */
.btn-salvar {
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

.btn-salvar:hover {
    opacity: 0.9;
}

/* ===== Tabela ===== */
table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    background: white;
    margin-top: 20px;
}

table th, table td {
    padding: 12px 10px;
    text-align: center;
    border: 1px solid #adadad;
}

table th {
    background: #653086;
    color: white;
}

table tr:nth-child(even) {
    background: #f9f9f9;
}

/* Cabeçalho do Dia */
.day-header {
    color: #3f0663;
    font-weight: bold;
    text-align: center;
    background: #e5d8f7;
}

/* ===== Botões de ação na tabela ===== */
.btn-acao {
    padding: 5px 8px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    margin: 2px;
}

.btn-editar {
    background: #3f0663;
    color: white;
}

.btn-editar:hover {
    opacity: 0.9;
}

.btn-excluir {
    background: red;
    color: white;
}

.btn-excluir:hover {
    opacity: 0.9;
}

/* ===== Responsivo ===== */
@media(max-width:768px) {
    .form-row {
        flex-direction: column;
    }
}

</style>

<div class="cardapio-atual">
    <h2>Agendamentos Semanais</h2>

    <table>
        <thead>
            <tr>
                <th>Dia</th>
                <th>Local</th>
                <th>Horário</th>
                <th>Atividade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $dias_semana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'];
            $locais = ['Laboratório 21','Laboratório 25','Laboratório 28','Biblioteca','Auditório'];
            $horarios = ['07:00 - 07:50','07:50 - 08:40','09:00 - 09:50','09:50 - 10:40','10:40 - 11:30','13:00 - 13:50','13:50 - 14:40','14:40 - 15:30','15:45 - 16:35'];

            foreach ($dias_semana as $dia):
                $dia_agendamentos = array_filter($agendamentos, fn($a) => $a['dia_semana'] === $dia);
            ?>
            <tr>
                <th class="periodo-header" colspan="5"><?php echo $dia; ?></th>
            </tr>
            <?php
            if (!empty($dia_agendamentos)):
                foreach ($dia_agendamentos as $item): ?>
                    <tr>
                        <td><?php echo $item['dia_semana']; ?></td>
                        <td><?php echo $item['local']; ?></td>
                        <td><?php echo $item['horario']; ?></td>
                        <td><?php echo $item['professorr']; ?></td>
                        <td>
                            <a href="gerenciar.php?edit=<?php echo $item['id']; ?>" class="btn-acao btn-editar">Editar</a>
                            <a href="gerenciar.php?delete=<?php echo $item['id']; ?>" class="btn-acao btn-excluir"
                               onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach;
            else: ?>
                <tr>
                    <td colspan="5" style="text-align:center; color:#022c8b;">
                        Nenhum agendamento cadastrado para <?php echo $dia; ?>
                    </td>
                </tr>
            <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php else: ?>
    <p style="text-align:center;">Nenhum agendamento cadastrado.</p>
<?php endif; ?>

    </div>

</body>
</html>
