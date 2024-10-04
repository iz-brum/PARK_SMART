<?php
class Veiculo {
    private $placa;
    private $modelo;
    private $cor;

    public function __construct($placa, $modelo, $cor) {
        $this->placa = $placa;
        $this->modelo = $modelo;
        $this->cor = $cor;
    }

    public function getPlaca() {
        return $this->placa;
    }

    public function getModelo() {
        return $this->modelo;
    }

    public function getCor() {
        return $this->cor;
    }
}
?>
