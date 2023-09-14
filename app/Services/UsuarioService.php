<?php

namespace App\Services;

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
            $usuarioCardio = DB::select("
            Select
            Case
            When ContratoFinanceiro.Codigo IS NULL THEN 0
            when ContratoFinanceiro.Codigo IS not NULL THEN 1
            END AS 'contratante'
            from ContratoFinanceiro
            full join Pessoa on Pessoa.autoid = Contratofinanceiro.pessoa
            where Pessoa.cnp = '$cnp';");

            if ($usuarioCardio[0]->contratante == "1") {
                return true;
            }
            return false;
        } catch (Exception $erro) {
            //echo ($erro);
        }
    }
}
