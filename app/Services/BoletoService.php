<?php

namespace App\Services;

use App\Class\CalcularDiasUteis;
use App\Class\CalcularJuros;
use App\Models\Boleto;
use DateTime;
use OpenBoleto\Banco\Unicred;
use OpenBoleto\Agente;

class BoletoService
{
    private int $diasUteis;
    private $boleto;
    private $multa;

    public function __construct(public string $cnp, public string $autoId) {
        $this->carregarBoleto($cnp, $autoId);
        $this->calcularMulta();
        echo $this->criarBoleto();
    }

    private function carregarBoleto($cnp, $autoId){
        $boleto = new Boleto();
        $this->boleto = $boleto->selectBoleto($cnp, $autoId)[0];
    }

    private function calcularMulta(){

        if ($this->boleto->dataDeVencimento < now()->format('m/d/Y')) {
            $calculo = new CalcularDiasUteis(new DateTime($this->boleto->dataDeVencimento));


            $juros = new CalcularJuros($this->boleto->valorDoDocumento*100, $calculo->diasUteis);
            $this->multa = $juros->multa;
        }
    }

    private function criarBoleto()
    {
        $sacado = new Agente($this->boleto->nomePessoa, $this->boleto->cnp, $this->boleto->rua. ', '. $this->boleto->numero .' '. $this->boleto->bairro , $this->boleto->cep, $this->boleto->cidade, $this->boleto->uf);
        $cedente = new Agente('Unimed Noroeste de Minas', '41.905.498/0001-19', 'RUA JOSINO VALARES 33 CENTRO', '38600-000', 'Paracatu', 'MG');

        $boleto = new Unicred(array(
            // Parâmetros obrigatórios
            'dataVencimento' => new DateTime($this->boleto->dataDeVencimento),

            'sequencial' => substr($this->boleto->nossoNumero, 0, -1), // Para gerar o nosso número /*6 numeros
            'especieDoc' => 'DM',
            'sacado' => $sacado,
            'cedente' => $cedente,
            'agencia' => 5841, // Até 4 dígitos *4digitos
            'carteira' => 21,
            'conta' => 502348, // Até 8 dígitos *6digitos
            'convenio' => 4, // 4, 6 ou 7 dígitos
            'numeroDocumento' => $this->boleto->numeroDoDocumento,
            'quantidade' => 1,
            'valor' => $this->boleto->valorDoDocumento,
            'moraMulta' => $this->multa,
            'valorCobrado' => $this->boleto->valorDoDocumento + $this->multa,

            'descricaoDemonstrativo' => array( // Até 5
                'Plano de Saúde'
            ),
            'instrucoes' => array( // Até 8
                'APÓS O VENCIMENTO: JUROS DE 0,033% AO DIA E MULTA DE 2%',
                "NÃO RECEBER APÓS 60 DIAS DE VENCIENTO <br>
     Contrato: " . $this->boleto->contrato . " Competência: " . $this->boleto->competenciaDeGeracao,
            ),
        ));

        return $boleto->getOutput();
    }
}
