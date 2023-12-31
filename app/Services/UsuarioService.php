<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UsuarioService
{

    public function buscarUsuarioDbCardio($cnp)
    {
        try {

            $contratante = DB::select("
            Select top 1
            Case
            When  ContratoFinanceiro.Codigo IS NULL THEN 0
            when ContratoFinanceiro.Codigo IS not NULL THEN 1
            END AS 'contratante'
            from ContratoFinanceiro
            full  join Pessoa on Pessoa.autoid = Contratofinanceiro.pessoa
            where Pessoa.cnp = '$cnp';");

            if (intval($contratante[0]->contratante)) {
                return true;
            }

            return false;
        } catch (Exception $erro) {
            //echo ($erro);
        }
    }
}
