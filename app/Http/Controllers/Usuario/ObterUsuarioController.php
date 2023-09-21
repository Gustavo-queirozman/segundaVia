<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;

class ObterUsuarioController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {

        try {
            $cnp = preg_replace('/[^a-zA-Z0-9\s]/', '', $request->input('cnp'));
            $this->validate($request, [
                'cnp' => 'required|cpf_cnpj',
            ]);

            $eUsuario = new UsuarioService($cnp);
            $eUsuario = $eUsuario->buscarUsuarioDbCardio($cnp);
            
            if ($eUsuario) {
                return view('auth.login', ['cnp' => $cnp]);
            }

            $message = "Erro, não é contratante!";
            return redirect('/')->with('mensagem', $message);
        } catch (Exception $erro) {
            $message = $erro->getMessage();
            return redirect('/')->with('mensagem', $message);
        }
    }
}
