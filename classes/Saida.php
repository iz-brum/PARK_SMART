<?php
class Saida {
    private $veiculo;
    private $dataHoraSaida;

    public function __construct(Veiculo $veiculo, $dataHoraSaida) {
        $this->veiculo = $veiculo;
        $this->dataHoraSaida = $dataHoraSaida;
    }

    public function getVeiculo() {
        return $this->veiculo;
    }

    public function getDataHoraSaida() {
        return $this->dataHoraSaida;
    }
}
?>
