<?php
require_once 'classes/Cliente.php'; // Inclui a classe Cliente
require_once 'classes/Veiculo.php'; // Inclui a classe Veiculo

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão, se não estiver iniciada
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas - Estacionamento Inteligente</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Estilos aplicados -->
</head>
<body>
    <header>
        <h1>Consulta de Entradas</h1>
        <?php include 'navbar.php'; ?>
    </header>

    <main>
        <h2>Buscar Cliente por CPF</h2>
        <form method="GET" action="buscar_cliente.php">
            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" required>
            <button type="submit">Buscar</button>
        </form>

        <?php
        if (isset($_GET['cpf'])) {
            $cpf = $_GET['cpf'];

            if (isset($_SESSION['clientes'][$cpf])) {
                // Obtendo o cliente, veículo e entrada
                $clienteObj = $_SESSION['clientes'][$cpf]['cliente'];
                $veiculoObj = $_SESSION['clientes'][$cpf]['veiculo'];

                // Exibindo as informações do cliente e veículo
                echo "<h3>Informações do Cliente</h3>";
                echo "<div class='cliente-info'>";
                echo "<p><strong>Nome:</strong> " . $clienteObj->getNome() . "</p>";
                echo "<p><strong>CPF:</strong> " . $clienteObj->getCpf() . "</p>";
                echo "<p><strong>Telefone:</strong> " . $clienteObj->getTelefone() . "</p>";
                echo "<p><strong>Veículo:</strong> " . $veiculoObj->getModelo() . " (Placa: " . $veiculoObj->getPlaca() . ", Cor: " . $veiculoObj->getCor() . ")</p>";
                echo "</div>";
            } else {
                echo "<p>Cliente não encontrado!</p>";
            }
        }
        ?>
    </main>

    <footer>
        &copy; 2024 Estacionamento Inteligente
    </footer>
</body>
</html>
