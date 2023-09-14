<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Services\UsuarioService;
use Exception;
use Illuminate\Http\Request;

class LoginUsuarioController extends Controller
{
    public function auth(Request $request)
    {
        try {
            $cnp = preg_replace('/[^a-zA-Z0-9\s]/', '', $request->input('cnp'));
            $this->validate($request, [
                'cnp' => 'required|cpf_cnpj',
            ]);
            $searchUserDbCardio = new UsuarioService($cnp);
            $eUsuario = $searchUserDbCardio->buscarUsuarioDbCardio($cnp);

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
