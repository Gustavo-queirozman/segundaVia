<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;

class ListaBoleto {
    public function __construct(string $cnp){
        $this->index($cnp);
    }

    public function index($cnp){
        try {
            $boletosEmAberto = DB::select("
            Select
            BO.NossoNumero,
            DocFinanceiro.ValorLiquido AS 'Valor do documento',
            DocFinanceiro.Numero AS 'Numero do Documento',
            CONVERT( varchar,DocFinanceiro.DataVencimento , 101) AS 'Data de Vencimento',
            pessoa.Nome as 'Nome Pessoa',
            Pessoa.Cnp as 'Cnp',
            EP.Logradouro as 'Rua',
            EP.NumLogradouro As 'Numero',
            EP.Bairro AS 'Bairro',
            EP.CEP AS 'Cep',
            CP.Nome AS 'Cidade',
            CP.UF AS 'UF',

            Case
            when DocFinanceiro.SituacaoDocumento = 1 THEN 'ABERTO'
            when DocFinanceiro.SituacaoDocumento = 2 THEN 'QUITADO'
            EnD AS 'Situação do Boleto'

            from DocFinanceiro
            inner join BoletoCobranca BO on BO.DocFinanceiro = DocFinanceiro.AutoId
            inner join ContratoFinanceiro CO on CO.AutoId = DocFinanceiro.ContratoFinanceiro
            inner join Pessoa on CO.Pessoa = Pessoa.AutoId
            CROSS APPLY (
                    SELECT TOP 1 *
                    FROM EnderecoPessoa EP
                    WHERE EP.Pessoa = Pessoa.AutoId AND EP.FimVigencia IS NULL
                        ) EP
                    inner join CidadePais CP WITH (NOLOCK) ON CP.Codigo = EP.Cidade

            Where Pessoa.Cnp = '$cnp' and DocFinanceiro.SituacaoDocumento = 1;");

        } catch (Exception $erro) {
            //echo ($erro);
        }
    }
}
