<?php

namespace App\Http\Controllers\Boleto;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ListarBoletoController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        //PASSAR CNP
        //$cnp = Auth::user()->cnp;
        $cnp = '11954444605';
        try {
            $boletosEmAberto = DB::select("
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
            return view('boleto.index', ['boletos' => $boletosEmAberto]);
        } catch (Exception $erro) {
            //echo ($erro);
        }
    }

    /*controle de recurso que o usupario pode ver
    public function index()
    {
        if (Gate::allows('manage-tasks')) {
            return view('passou');
        }
        return view('home');
    }*/
}
