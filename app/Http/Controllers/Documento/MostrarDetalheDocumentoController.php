<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostrarDetalheDocumentoController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function __invoke(Request $request)
    {

        $autoId = '624928';
        try{
            $detalhes = DB::select("Select DISTINCT DocFinanceiro.AutoId,
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
            return view('documento.detalhes', ['detalhes' => $detalhes]);
        }catch(Exception $e){
            //
        }
    }
}
