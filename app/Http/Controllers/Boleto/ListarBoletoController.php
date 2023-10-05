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
            $cnp = $request->input('cnp');
            $boletos = $this->listarBoletos($cnp);
            return view('boleto.index', ['boletos'=> $boletos]);
        }


        $cnp = Auth::user()->cnp;
        $boletos = new Boleto;
        $boletos = $boletos->selectBoletos($cnp);
        return view('boleto.index', ['boletos' => $boletos]);
    }
}
