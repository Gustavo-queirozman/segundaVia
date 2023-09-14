<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostrarDemonstrativoDocumentoController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $autoId = '';
        try {
            $demonstrativo = DB::select("
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
            dd($demonstrativo);
            return view('documento.demonstrativo', ['demonstrativo' => $demonstrativo]);
        } catch (Exception $e) {
        }
    }
}
