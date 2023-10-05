<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ObterUsuarioController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {

        try {
            Log::info('Removendo caracteres especiais do cnp...');
            $cnp = preg_replace('/[^a-zA-Z0-9\s]/', '', $request->input('cnp'));

            Log::info('Validando cnp...');
            $this->validate($request, [
                'cnp' => 'required|cpf_cnpj',
            ]);

            Log::info('Iniciando serviço de usuário...');
            $eUsuario = new UsuarioService($cnp);
            Log::info('Procurando cnp no DB Cardio...');
            $eUsuario = $eUsuario->buscarUsuarioDbCardio($cnp);

            dd($eUsuario);

            Log::info('Usuário encontrado no DB Cardio...');
            Log::info('Redirecionando para tela de login com cnp...');
            if ($eUsuario) {
                return view('auth.login', ['cnp' => $cnp]);
            }

            $message = "Erro, não é contratante!";
            log::error("Esse cnp não é de contratante!");
            return redirect('/')->with('mensagem', $message);
        } catch (Exception $erro) {
            $message = $erro->getMessage();
            return redirect('/')->with('mensagem', $message);
        }
    }
}
