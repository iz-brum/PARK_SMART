// Estacionamento.php
<?php
class Estacionamento {
    private $nome;
    private $endereco;
    private $patios = [];

    public function __construct($nome, $endereco) {
        $this->nome = $nome;
        $this->endereco = $endereco;
    }

    public function adicionarPatio(Patio $patio) {
        $this->patios[] = $patio;
    }

    public function getPatios() {
        return $this->patios;
    }

    public function getNome() {
        return $this->nome;
    }
}
?>
