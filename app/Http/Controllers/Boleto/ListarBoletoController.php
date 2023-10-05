<?php

namespace App\Http\Controllers\Boleto;

use App\Http\Controllers\Controller;
use App\Models\Boleto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ListarBoletoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        if (Gate::allows('permission-adm')) {
            return $this->listarBoletos($request->input('cnp'));
        }

        return $this->listarBoletos(Auth::user()->cnp);
    }

    protected function listarBoletos($cnp)
    {
        $boletos = new Boleto;

        if (Gate::allows('permission-adm')) {
            return view('boleto.index', ['boletos' => $boletos->selectBoletos($cnp)]);
        }

        return view('boleto.index', ['boletos' => $boletos->selectBoletos($cnp)]);
    }
}
