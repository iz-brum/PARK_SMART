<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cpf = $_GET['cpf'] ?? ''; // Obtém o CPF passado na URL
$historico = $_SESSION['historico'][$cpf] ?? []; // Recupera o histórico de entradas e saídas do usuário

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico do Usuário - Estacionamento Inteligente</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/usuarios.css">

</head>
<body>
    <header>
        <h1>Histórico de <?= htmlspecialchars($cpf) ?></h1>
        <?php include 'navbar.php'; ?>
    </header>

    <main>
        <h2>Entradas e Saídas</h2>

        <?php if (empty($historico)): ?>
            <p>Este usuário não possui entradas ou saídas registradas.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Data e Hora de Entrada</th>
                        <th>Data e Hora de Saída</th>
                        <th>Veículo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico as $registro): ?>
                        <tr>
                            <td><?= $registro['entrada'] ?></td>
                            <td><?= $registro['saida'] ?? 'Ainda no estacionamento' ?></td>
                            <td><?= $registro['veiculo'] ?></td>
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
