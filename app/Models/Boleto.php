<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Boleto extends Model
{
    use HasFactory;

    public function selectBoleto($cnp, $autoId)
    {
        try {
            return DB::select("
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
        } catch (Exception $erro) {
            //echo ($erro);
        }
    }

    public function selectBoletos($cnp)
    {
        try {
            return DB::select("
            Select
            DocFinanceiro.AutoId AS 'autoId',
            CO.Codigo AS 'contrato',
            pessoa.Nome as 'nome',
            Pessoa.Cnp as 'cnp',
            DocFinanceiro.NumFatInterc AS 'fatura',
            Case
                when DocFinanceiro.ClassificacaoCobranca = 1 THEN '----'
                when DocFinanceiro.ClassificacaoCobranca = 2 THEN 'Carnes'
            EnD AS 'classe',
            CONVERT( varchar,DocFinanceiro.DataEmissao , 101) AS 'emissao',
            CONVERT( varchar,DocFinanceiro.DataVencimento , 101) AS 'vencimento',
            DocFinanceiro.ValorLiquido AS 'liquido',
            DocFinanceiro.ValorBruto AS 'saldo',
            Case
                when DocFinanceiro.SituacaoDocumento = 1 THEN 'ABERTO'
                when DocFinanceiro.SituacaoDocumento = 2 THEN 'QUITADO'
            EnD AS 'situacao',
            CASE
                WHEN DATEDIFF(day, DocFinanceiro.DataVencimento, GETDATE()) > 0 THEN
                CONCAT('Vencido a ',DATEDIFF(day, DocFinanceiro.DataVencimento, GETDATE()))
                END AS 'status'
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

    public function selectDemonstrativo($autoId)
    {
        try {
            return DB::select("
            Select distinct
            --DocFinanceiro.AutoId,
            CO.Codigo as 'contrato',
            Case
            when DocFinanceiro.ClassificacaoCobranca = 1 THEN 'ABERTO'
            when DocFinanceiro.ClassificacaoCobranca = 2 THEN 'Carnes'
            End AS 'tipoDeCobranca',
            CONVERT (varchar, DocFinanceiro.DataEmiFatInterc, 101) AS 'dataDeEmissao',
            DocFinanceiro.ValorLiquido AS 'valorLiquido',
            Pessoa.Nome as 'contratante',
            DocFinanceiro.CompFinanceira AS 'competencia',
            CONVERT ( varchar,DocFinanceiro.DataVencimento , 101) AS 'dataDeVencimento',
            --saldo aberto
            BO.NossoNumero as 'nossoNumero',
            DocFinanceiro.NumFatInterc AS 'fatura',
            CONCAT(EP.Logradouro, ' ',EP.Bairro,' - ', CP.Nome, ' CEP: ',EP.CEP) AS 'endereco',
            Case
            when DocFinanceiro.SituacaoDocumento = 1 THEN 'ABERTO'
            when DocFinanceiro.SituacaoDocumento = 2 THEN 'QUITADO'
            End AS 'situacao'


            from DocFinanceiro
                            inner join BoletoCobranca BO on BO.DocFinanceiro = DocFinanceiro.AutoId
                            inner join MovDocFinan MD on MD.DocFinanceiro = DocFinanceiro.AutoId
                            inner join ContratoFinanceiro CO on CO.AutoId = DocFinanceiro.ContratoFinanceiro
                            inner join Pessoa on CO.Pessoa = Pessoa.AutoId

                                    CROSS APPLY (
                                        SELECT TOP 1 *
                                        FROM EnderecoPessoa EP WHERE EP.Pessoa = Pessoa.AutoId AND EP.FimVigencia IS NULL) EP
                                        inner join CidadePais CP WITH (NOLOCK) ON CP.Codigo = EP.Cidade



            Where DocFinanceiro.AutoId = '$autoId' and DocFinanceiro.SituacaoDocumento = 1;");
        } catch (Exception $e) {
        }
    }

    public function selectDocumento($autoId)
    {
        try {

            return DB::select("
            Select distinct
            --DocFinanceiro.AutoId,
            CO.Codigo as 'contrato',
            Case
            when DocFinanceiro.ClassificacaoCobranca = 1 THEN 'ABERTO'
            when DocFinanceiro.ClassificacaoCobranca = 2 THEN 'Carnes'
            End AS 'tipoDeCobranca',
            CONVERT (varchar, DocFinanceiro.DataEmiFatInterc, 101) AS 'dataDeEmissao',
            DocFinanceiro.ValorLiquido AS 'valorLiquido',
            Pessoa.Nome as 'contratante',
            DocFinanceiro.CompFinanceira AS 'competencia',
            CONVERT ( varchar,DocFinanceiro.DataVencimento , 101) AS 'dataDeVencimento',
            --saldo aberto
            BO.NossoNumero as 'nossoNumero',
            DocFinanceiro.NumFatInterc AS 'fatura',
            CONCAT(EP.Logradouro, ' ',EP.Bairro,' - ', CP.Nome, ' CEP: ',EP.CEP) AS 'endereco',
            Case
            when DocFinanceiro.SituacaoDocumento = 1 THEN 'ABERTO'
            when DocFinanceiro.SituacaoDocumento = 2 THEN 'QUITADO'
            End AS 'situacao'


            from DocFinanceiro
                            inner join BoletoCobranca BO on BO.DocFinanceiro = DocFinanceiro.AutoId
                            inner join MovDocFinan MD on MD.DocFinanceiro = DocFinanceiro.AutoId
                            inner join ContratoFinanceiro CO on CO.AutoId = DocFinanceiro.ContratoFinanceiro
                            inner join Pessoa on CO.Pessoa = Pessoa.AutoId

                                    CROSS APPLY (
                                        SELECT TOP 1 *
                                        FROM EnderecoPessoa EP WHERE EP.Pessoa = Pessoa.AutoId AND EP.FimVigencia IS NULL) EP
                                        inner join CidadePais CP WITH (NOLOCK) ON CP.Codigo = EP.Cidade



            Where DocFinanceiro.AutoId = '$autoId' and DocFinanceiro.SituacaoDocumento = 1;");
        } catch (Exception $e) {
        }
    }

    public function selectDetalheDocumento($autoId)
    {

        try {
            return DB::select("Select DISTINCT DocFinanceiro.AutoId,
            Pessoa.Nome as 'contratante',
            DocFinanceiro.NumFatInterc AS 'numeroFatura',
            DocFinanceiro.CompFinanceira AS 'referencia',
            DocFinanceiro.ValorBruto AS 'valorBruto',
            Case
            when DocFinanceiro.SituacaoDocumento = 1 THEN 'ABERTO'
            when DocFinanceiro.SituacaoDocumento = 2 THEN 'QUITADO'
            End AS 'situacao',
            BO.NossoNumero as 'nossoNumero',
            CONVERT( varchar,DocFinanceiro.DataEmiFatInterc , 101) AS 'dataDeEmissao',
            DocFinanceiro.ValorLiquido AS 'valorLiquido',
            Case
            when DocFinanceiro.ClassificacaoCobranca = 1 THEN 'ABERTO'
            when DocFinanceiro.ClassificacaoCobranca = 2 THEN 'Carnes'
            End AS 'tipoDeCobranca',
            CONVERT( varchar,DocFinanceiro.DataVencimento , 101) AS 'dataDeVencimento',

            DocFinanceiro.ValorBruto AS 'saldoEmAberto'

            from DocFinanceiro
            inner join BoletoCobranca BO on BO.DocFinanceiro = DocFinanceiro.AutoId
            inner join MovDocFinan MD on MD.DocFinanceiro = DocFinanceiro.AutoId
            inner join ContratoFinanceiro CO on CO.AutoId = DocFinanceiro.ContratoFinanceiro
            inner join Pessoa on CO.Pessoa = Pessoa.AutoId
                    CROSS APPLY (
                        SELECT TOP 1 *
                        FROM EnderecoPessoa EP WHERE EP.Pessoa = Pessoa.AutoId AND EP.FimVigencia IS NULL) EP
                        inner join CidadePais CP WITH (NOLOCK) ON CP.Codigo = EP.Cidade

            Where DocFinanceiro.AutoId = '$autoId' and DocFinanceiro.SituacaoDocumento = 1;");
        } catch (Exception $e) {
            //
        }
    }
}
