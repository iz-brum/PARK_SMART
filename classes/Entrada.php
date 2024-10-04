<?php
class Entrada {
    private $veiculo;
    private $dataHoraEntrada;

    public function __construct(Veiculo $veiculo, $dataHoraEntrada) {
        $this->veiculo = $veiculo;
        $this->dataHoraEntrada = $dataHoraEntrada;
    }

    public function getVeiculo() {
        return $this->veiculo;
    }

    public function getDataHoraEntrada() {
        return $this->dataHoraEntrada;
    }
}
?>
