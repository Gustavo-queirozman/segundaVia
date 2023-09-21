<?php

namespace App\Http\Controllers\Documento;

use App\Http\Controllers\Controller;
use App\Models\Boleto;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostrarDocumentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Request $request)
    {
        $autoId= '';
        $documento = new Boleto;
        $documento = $documento->selectDocumento($autoId);
        return view('documento.documento', ['documento' => $documento]);
    }
}
