<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Models\Boleto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostrarDetalheDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $autoId = '624928';
        $detalhes = Boleto::selectDetalheDocumento($autoId);
        return view('documento.detalhes', ['detalhes' => $detalhes]);
    }
}
