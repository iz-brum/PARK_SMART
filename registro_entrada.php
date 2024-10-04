<?php
require_once 'classes/Patio.php';
require_once 'classes/Entrada.php';
require_once 'classes/Veiculo.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['patio']) || !($_SESSION['patio'] instanceof Patio)) {
    $_SESSION['patio'] = new Patio(5);
}

date_default_timezone_set('America/Cuiaba');
$patio = $_SESSION['patio'];
$sucesso = false;
$vagasDisponiveis = $patio->vagasDisponiveis();
$mensagemErro = '';
$coordenadasUsadas = ''; // Variável para armazenar as coordenadas usadas

// Definindo o polígono do estacionamento com as coordenadas fornecidas
$poligonoEstacionamento = [          // COORDENADAS FATEC
    ['latitude' => -15.608967, 'longitude' => -56.101467],
    ['latitude' => -15.608203, 'longitude' => -56.100698],
    ['latitude' => -15.608759, 'longitude' => -56.099712],
    ['latitude' => -15.609976, 'longitude' => -56.100361]
];

// Função para verificar se um ponto está dentro do polígono
function pontoDentroPoligono($ponto, $poligono) {
    $quantidadePontos = count($poligono);
    $j = $quantidadePontos - 1;
    $dentro = false;

    for ($i = 0; $i < $quantidadePontos; $i++) {
        if (($poligono[$i]['longitude'] < $ponto['longitude'] && $poligono[$j]['longitude'] >= $ponto['longitude']) || 
            ($poligono[$j]['longitude'] < $ponto['longitude'] && $poligono[$i]['longitude'] >= $ponto['longitude'])) {
            if ($poligono[$i]['latitude'] + ($ponto['longitude'] - $poligono[$i]['longitude']) / ($poligono[$j]['longitude'] - $poligono[$i]['longitude']) * 
                ($poligono[$j]['latitude'] - $poligono[$i]['latitude']) < $ponto['latitude']) {
                $dentro = !$dentro;
            }
        }
        $j = $i;
    }
    
    return $dentro;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cpf = $_POST['cpf'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $pontoCliente = ['latitude' => $latitude, 'longitude' => $longitude];
    $coordenadasUsadas = "Latitude: $latitude, Longitude: $longitude"; // Armazena as coordenadas usadas

    // Verifica se o cliente está dentro do perímetro do estacionamento
    if (!pontoDentroPoligono($pontoCliente, $poligonoEstacionamento)) {
        $mensagemErro = "O cliente está fora do perímetro do estacionamento. Coordenadas usadas: $coordenadasUsadas";
    } else {
        // Verifica se o cliente está cadastrado
        if (isset($_SESSION['clientes'][$cpf])) {
            $clienteInfo = $_SESSION['clientes'][$cpf];

            // Verifica se o cliente já está no estacionamento (sem saída registrada)
            if (isset($clienteInfo['entrada']) && !isset($clienteInfo['saida'])) {
                $mensagemErro = 'O cliente já está no estacionamento e ainda não saiu.';
            } else {
                // Verifica se há vagas disponíveis
                if ($patio->vagasDisponiveis() > 0) {
                    $veiculo = $clienteInfo['veiculo'];
                    $entrada = new Entrada($veiculo, date('Y-m-d H:i:s'));

                    // Atualiza a sessão com a entrada
                    $_SESSION['clientes'][$cpf]['entrada'] = $entrada;
                    unset($_SESSION['clientes'][$cpf]['saida']); // Reseta a saída, se existir

                    $patio->ocuparVaga();
                    $sucesso = true;
                    $vagasDisponiveis = $patio->vagasDisponiveis();
                    $mensagemSucesso = "Entrada registrada com sucesso! Coordenadas usadas: $coordenadasUsadas";
                } else {
                    $mensagemErro = 'Estacionamento lotado. Não há vagas disponíveis.';
                }
            }
        } else {
            $mensagemErro = 'Cliente não cadastrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Entrada - Estacionamento Inteligente</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="js/geolocation.js"></script>
</head>
<body>
    <header>
        <h1>Registrar Entrada</h1>
        <?php include 'navbar.php'; ?>
    </header>

    <main>
        <form action="registro_entrada.php" method="POST">
            <label for="cpf">CPF do Cliente:</label>
            <input type="text" name="cpf" id="cpf" required>
            
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <button type="submit">Registrar Entrada</button>
        </form>

        <!-- Exibindo mensagem de sucesso ou erro -->
        <?php if ($sucesso): ?>
            <script>
                Swal.fire({
                    title: 'Sucesso!',
                    html: '<?= $mensagemSucesso ?><br>Vagas disponíveis: <?= $vagasDisponiveis ?>',
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

        <section class="vagas-disponiveis">
            <h3>Vagas Disponíveis: <?= $vagasDisponiveis ?></h3>
        </section>
    </main>

    <footer>
        &copy; 2024 Estacionamento Inteligente
    </footer>

    <script>
        document.querySelector('form').addEventListener('submit', function (event) {
            event.preventDefault(); // Previne o envio imediato do formulário
            obterLocalizacao(function (posicao) {
                document.getElementById('latitude').value = posicao.coords.latitude;
                document.getElementById('longitude').value = posicao.coords.longitude;
                event.target.submit(); // Envia o formulário após preencher as coordenadas
            }, function (erro) {
                alert("Erro ao obter localização: " + erro.message);
            });
        });
    </script>

</body>
</html>
