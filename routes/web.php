<?php

use App\Http\Controllers\Boleto\CriarBoletoController;
use App\Http\Controllers\Boleto\ListarBoletoController;
use App\Http\Controllers\Documento\MostrarDemonstrativoDocumentoController;
use App\Http\Controllers\Documento\MostrarDetalheDocumentoController;
use App\Http\Controllers\Documento\MostrarDocumentoController;
use App\Http\Controllers\Usuario\ObterUsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth');
});


Route::get('/boleto', CriarBoletoController::class);
Route::get('/home', ListarBoletoController::class);
Route::get('/detalhe', MostrarDetalheDocumentoController::class);
Route::get('/documento', MostrarDocumentoController::class);
Route::get('/demonstrativo', MostrarDemonstrativoDocumentoController::class);

Route::middleware('web', 'throttle:20,1')->group(function () {
    Route::post('/', [ObterUsuarioController::class, '__invoke']);
    Auth::routes();
});









