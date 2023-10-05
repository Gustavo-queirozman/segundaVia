<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Models\Boleto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostrarDemonstrativoDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $autoId = '';
        $demonstrativo = Boleto::selectDemonstrativo($autoId);

        return view('documento.demonstrativo', ['demonstrativo' => $demonstrativo]);
    }
}
