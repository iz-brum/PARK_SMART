<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$usuarios = $_SESSION['usuarios'] ?? []; // Recupera a lista de usuários da sessão
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários - Estacionamento Inteligente</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/usuarios.css">

</head>
<body>
    <header>
        <h1>Usuários do Estacionamento</h1>
        <?php include 'navbar.php'; ?>
    </header>

    <main>
        <h2>Todos os Usuários</h2>

        <?php if (empty($usuarios)): ?>
            <p>Nenhum usuário registrado até o momento.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Telefone</th>
                        <th>Placa</th>
                        <th>Modelo</th>
                        <th>Cor</th>
                        <th>Última Entrada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr onclick="window.location.href='historico_usuario.php?cpf=<?= $usuario['cpf'] ?>'">
                            <td><?= $usuario['nome'] ?></td>
                            <td><?= $usuario['cpf'] ?></td>
                            <td><?= $usuario['telefone'] ?></td>
                            <td><?= isset($usuario['veiculo']['placa']) ? $usuario['veiculo']['placa'] : 'Não registrado' ?></td>
                            <td><?= isset($usuario['veiculo']['modelo']) ? $usuario['veiculo']['modelo'] : 'Não registrado' ?></td>
                            <td><?= isset($usuario['veiculo']['cor']) ? $usuario['veiculo']['cor'] : 'Não registrado' ?></td>
                            <td><?= isset($usuario['dataHoraEntrada']) ? $usuario['dataHoraEntrada'] : 'Sem histórico' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <footer>
        &copy; 2024 Estacionamento Inteligente
    </footer>
</body>
</html>
