<?php

namespace App\Http\Controllers\Boleto;

use App\Http\Controllers\Controller;
use App\Services\BoletoService;
use DateTime;
use Illuminate\Http\Request;


class CriarBoletoController extends Controller
{
    /**
     * Handle the incoming request.
    **/
    public function __invoke(Request $request)
    {
        //PASSAR CNP e AUTOID DO BOLETO
        new BoletoService('11954444605', '759913');
    }
}
