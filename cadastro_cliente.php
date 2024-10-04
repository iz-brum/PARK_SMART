<?php
require_once 'classes/Cliente.php';
require_once 'classes/Veiculo.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Garante que a variável 'clientes' sempre seja um array
if (!isset($_SESSION['clientes'])) {
    $_SESSION['clientes'] = [];
}

$sucesso = false;
$mensagemErro = '';
$cpfErro = $telefoneErro = $placaErro = false; // Flags para destacar os campos com erro

// Inicializa as variáveis com valores vazios
$nome = $cpf = $telefone = $placa = $modelo = $cor = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $placa = $_POST['placa'];
    $modelo = $_POST['modelo'];
    $cor = $_POST['cor'];

    // Verifica se já existe um cliente com o mesmo CPF, telefone ou placa
    $cpfDuplicado = isset($_SESSION['clientes'][$cpf]);
    $telefoneDuplicado = $placaDuplicada = false;

    foreach ($_SESSION['clientes'] as $clienteExistente) {
        if ($clienteExistente['cliente']->getTelefone() === $telefone) {
            $telefoneDuplicado = true;
        }
        if ($clienteExistente['veiculo']->getPlaca() === $placa) {
            $placaDuplicada = true;
        }
    }

    if ($cpfDuplicado) {
        $mensagemErro = "Já existe um cliente com o CPF informado.";
        $cpfErro = true;
    } elseif ($telefoneDuplicado) {
        $mensagemErro = "Já existe um cliente com o telefone informado.";
        $telefoneErro = true;
    } elseif ($placaDuplicada) {
        $mensagemErro = "Já existe um veículo cadastrado com a placa informada.";
        $placaErro = true;
    } else {
        // Cria o objeto Cliente e Veiculo
        $cliente = new Cliente($nome, $cpf, $telefone);
        $veiculo = new Veiculo($placa, $modelo, $cor);

        // Armazena o cliente e o veículo na sessão
        $_SESSION['clientes'][$cpf] = ['cliente' => $cliente, 'veiculo' => $veiculo];

        $sucesso = true;

        // Limpa os dados do formulário após o sucesso
        $nome = $cpf = $telefone = $placa = $modelo = $cor = '';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - Estacionamento Inteligente</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Incluindo o SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .erro {
            border: 2px solid red;
        }
    </style>
</head>
<body>
    <header>
        <h1>Cadastro de Cliente</h1>
        <?php include 'navbar.php'; ?>
    </header>

    <main>
        <form action="cadastro_cliente.php" method="POST">
            <label for="nome">Nome do Cliente:</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome) ?>" required>

            <label for="cpf">CPF:</label>
            <input type="text" name="cpf" id="cpf" class="<?= $cpfErro ? 'erro' : '' ?>" value="<?= htmlspecialchars($cpf) ?>" required>

            <label for="telefone">Telefone:</label>
            <input type="text" name="telefone" id="telefone" class="<?= $telefoneErro ? 'erro' : '' ?>" value="<?= htmlspecialchars($telefone) ?>" required>

            <label for="placa">Placa do Veículo:</label>
            <input type="text" name="placa" id="placa" class="<?= $placaErro ? 'erro' : '' ?>" value="<?= htmlspecialchars($placa) ?>" required>

            <label for="modelo">Modelo do Veículo:</label>
            <input type="text" name="modelo" id="modelo" value="<?= htmlspecialchars($modelo) ?>" required>

            <label for="cor">Cor do Veículo:</label>
            <input type="text" name="cor" id="cor" value="<?= htmlspecialchars($cor) ?>" required>

            <button type="submit">Cadastrar Cliente</button>
        </form>

        <!-- Mensagens de Sucesso ou Erro -->
        <?php if ($sucesso): ?>
            <script>
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Cliente cadastrado com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            </script>
        <?php elseif ($mensagemErro): ?>
            <script>
                Swal.fire({
                    title: 'Erro!',
                    text: '<?= $mensagemErro ?>',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            </script>
        <?php endif; ?>
    </main>

    <footer>
        &copy; 2024 Estacionamento Inteligente
    </footer>
</body>
</html>
