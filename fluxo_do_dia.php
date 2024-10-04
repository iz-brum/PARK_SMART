<?php
// Inclui as classes necessárias
require_once 'classes/Cliente.php';
require_once 'classes/Veiculo.php';
require_once 'classes/Entrada.php';
require_once 'classes/Saida.php'; // Inclui a classe de Saída
require_once 'classes/Patio.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Cuiaba'); // Define o fuso horário

$clientes = $_SESSION['clientes'] ?? []; // Recupera a lista de clientes armazenada na sessão
$hoje = date('Y-m-d'); // Data de hoje

function calcularTempoPermanencia($entrada, $saida) {
    if (!$saida) {
        return 'Ainda no estacionamento';
    }

    $dataHoraEntrada = new DateTime($entrada->getDataHoraEntrada());
    $dataHoraSaida = new DateTime($saida->getDataHoraSaida());
    $intervalo = $dataHoraEntrada->diff($dataHoraSaida);

    // Captura as horas e minutos
    $horas = $intervalo->h;
    $minutos = $intervalo->i;

    // Formatação para singular ou plural
    $resultado = '';

    // Verifica se as horas são maiores que 0
    if ($horas > 0) {
        $horasTexto = $horas === 1 ? 'hora' : 'horas';
        $resultado .= "$horas $horasTexto";
    }

    // Verifica se os minutos são maiores que 0
    if ($minutos > 0) {
        $minutosTexto = $minutos === 1 ? 'minuto' : 'minutos';

        // Se já houver horas, adiciona " e " antes dos minutos
        if ($resultado !== '') {
            $resultado .= " e ";
        }

        $resultado .= "$minutos $minutosTexto";
    }

    // Caso tanto horas quanto minutos sejam 0, significa que ficou menos de 1 minuto
    if ($resultado === '') {
        $resultado = "menos de 1 minuto";
    }

    return $resultado;
}


function formatarDataHora($dataHora) {
    $dataHoraFormatada = new DateTime($dataHora);
    return $dataHoraFormatada->format('d/m/Y - H:i:s');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entradas do Dia - Estacionamento Inteligente</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/entradas_do_dia.css">
</head>
<body>
    <header>
        <h1>Entradas do Dia</h1>
        <?php include 'navbar.php'; ?> <!-- Inclui o menu de navegação -->
    </header>

    <main>
        <h2>Entradas registradas em <?= date('d/m/Y') ?></h2>

        <?php 
        // Filtrar as entradas que ocorreram hoje
        $entradasHoje = array_filter($clientes, function($data) use ($hoje) {
            // Verifica se há uma entrada registrada e se a data corresponde ao dia de hoje
            return isset($data['entrada']) && strpos($data['entrada']->getDataHoraEntrada(), $hoje) === 0;
        });
        ?>

        <?php if (empty($entradasHoje)): ?>
            <p>Nenhuma entrada registrada hoje.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Veículo</th>
                        <th>Data e Hora da Entrada</th>
                        <th>Data e Hora da Saída</th>
                        <th>Tempo de Permanência</th> <!-- Nova coluna para o tempo de permanência -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entradasHoje as $dadosCliente): 
                        $cliente = $dadosCliente['cliente'];
                        $veiculo = $dadosCliente['veiculo'];
                        $entrada = $dadosCliente['entrada'];
                        $saida = $dadosCliente['saida'] ?? null; // Verifica se há uma saída registrada
                    ?>
                        <tr>
                            <td><?= $cliente->getNome() ?></td>
                            <td><?= $cliente->getCpf() ?></td>
                            <td><?= $veiculo->getModelo() . ' (' . $veiculo->getPlaca() . ')' ?></td>
                            <td><?= formatarDataHora($entrada->getDataHoraEntrada()) ?></td>
                            <td><?= $saida ? formatarDataHora($saida->getDataHoraSaida()) : 'Ainda no estacionamento' ?></td>
                            <td><?= calcularTempoPermanencia($entrada, $saida) ?></td> <!-- Exibe o tempo de permanência -->
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
