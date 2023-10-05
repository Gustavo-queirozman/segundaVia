<?php

namespace App\Class;

class CalcularJuros
{

    public $multa;

    public function __construct(
        public int $valorDoDocumento,
        public int $diasUteis
    ) {
        $this->multa = $this->calcularJurosTotal();
        return($this->multa);
    }

    private function calcularJurosTotal()
    {
        $juros = $this->calcularJurosMora() + $this->calcularMultaMora();
 
        return ($juros);
    }

    private function calcularJurosMora()
    {
        $jurosMora = (ceil(0.03 * $this->valorDoDocumento/100) / 100 * $this->diasUteis);
        return ($jurosMora);
    }

    private function calcularMultaMora()
    {
        $multaMora = ($this->valorDoDocumento/100 * 0.02);
        $multaMora = strval($multaMora);
        return(floatval("$multaMora[0]$multaMora[1]$multaMora[2]$multaMora[3]"));
    }
}
