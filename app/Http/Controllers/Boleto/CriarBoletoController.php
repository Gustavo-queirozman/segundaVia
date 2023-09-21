<?php

namespace App\Http\Controllers\Boleto;

use App\Http\Controllers\Controller;
use App\Services\BoletoService;
use DateTime;
use Illuminate\Http\Request;


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
        new BoletoService('11954444605', '759913');
    }
}
