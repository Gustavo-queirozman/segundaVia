<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use Ramsey\Uuid\Type\Integer;

class UsuarioService
{

    public function buscarUsuarioDbCardio($cnp)
    {

        try {
            $contratante = DB::table('ContratoFinanceiro')
                ->leftJoin('Pessoa', 'Pessoa.autoid', '=', 'ContratoFinanceiro.pessoa')
                ->where('Pessoa.cnp', $cnp)
                ->select(DB::raw('CASE WHEN ContratoFinanceiro.Codigo IS NULL THEN 0 ELSE 1 END AS contratante'))
                ->first();


            if (intval($contratante->contratante) == 1) {
                return true;
            }
            if($contratante == null){
                return false;
            }
        } catch (Exception $erro) {
            //echo ($erro);
        }
    }
}
