<?php

class Patio {
    private $totalVagas;
    private $vagasOcupadas;

    public function __construct($totalVagas) {
        $this->totalVagas = $totalVagas;
        $this->vagasOcupadas = 0;
    }

    public function __wakeup() {
        // Esse método pode ser vazio, mas ele garante que a sessão restaure corretamente a classe
    }

    public function ocuparVaga() {
        if ($this->vagasOcupadas < $this->totalVagas) {
            $this->vagasOcupadas++;
            return true;
        } else {
            return false; // Não há vagas disponíveis
        }
    }

    public function liberarVaga() {
        if ($this->vagasOcupadas > 0) {
            $this->vagasOcupadas--;
        }
    }

    public function vagasDisponiveis() {
        return $this->totalVagas - $this->vagasOcupadas;
    }
}
?>
