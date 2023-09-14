<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Boleto extends Model
{
    use HasFactory;

    public function select($cnp, $autoId){
        try {
            $boletosEmAberto = DB::select("
            Select
            BO.nossoNumero AS 'nossoNumero',
            DocFinanceiro.ValorLiquido AS 'valorDoDocumento',
            DocFinanceiro.Numero AS 'numeroDoDocumento',
            CONVERT( varchar,DocFinanceiro.DataVencimento , 101) AS 'dataDeVencimento',
            pessoa.Nome as 'nomePessoa',
            Pessoa.Cnp as 'cnp',
            EP.Logradouro as 'rua',
            EP.NumLogradouro As 'numero',
            EP.Bairro AS 'bairro',
            EP.CEP AS 'cep',
            CP.Nome AS 'cidade',
            CP.UF AS 'uf',
            CO.Codigo AS 'contrato',
            RIGHT(CONVERT(VARCHAR, DocFinanceiro.CompGeracao, 112), 2)  + LEFT(CONVERT(VARCHAR, DocFinanceiro.CompGeracao, 112), 4) AS 'competenciaDeGeracao',


            Case
            when DocFinanceiro.SituacaoDocumento = 1 THEN 'ABERTO'
            when DocFinanceiro.SituacaoDocumento = 2 THEN 'QUITADO'
            EnD AS 'situacaoDoBoleto'

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

            Where Pessoa.Cnp = '$cnp' and DocFinanceiro.SituacaoDocumento = 1 and DocFinanceiro.AutoId='$autoId'
            -- situação 1 e ABERTO
            -- situação 2 e QUITADO;");
            return  $boletosEmAberto;
        } catch (Exception $erro) {
            //echo ($erro);
        }
    }
}
