<?php
class Vaga {
    private $numero;
    private $ocupada = false;

    public function __construct($numero) {
        $this->numero = $numero;
    }

    public function ocupar() {
        $this->ocupada = true;
    }

    public function desocupar() {
        $this->ocupada = false;
    }

    public function isOcupada() {
        return $this->ocupada;
    }

    public function getNumero() {
        return $this->numero;
    }
}
?>
