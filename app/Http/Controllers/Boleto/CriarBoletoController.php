<?php

namespace App\Http\Controllers\Boleto;

use App\Http\Controllers\Controller;
use App\Services\BoletoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CriarBoletoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Handle the incoming request.
    **/
    public function __invoke(Request $request)
    {
        Log::info('Iniciando serviço de criar boleto...');
        new BoletoService('11954444605', '759913');
    }
}
