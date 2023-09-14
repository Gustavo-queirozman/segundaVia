<?php

use App\Http\Controllers\Boleto\CriarBoletoController;
use App\Http\Controllers\Boleto\ListarBoletoController;
use App\Http\Controllers\Documento\MostrarDemonstrativoDocumentoController;
use App\Http\Controllers\Documento\MostrarDetalheDocumentoController;
use App\Http\Controllers\Documento\MostrarDocumentoController;
use App\Http\Controllers\Usuario\LoginUsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth');
});
Route::post('/', [LoginUsuarioController::class, 'auth']);
Route::get('/boleto', CriarBoletoController::class);
Route::get('/home', ListarBoletoController::class);
Route::get('/detalhe', MostrarDetalheDocumentoController::class);
Route::get('/documento', MostrarDocumentoController::class);
Route::get('/demonstrativo', MostrarDemonstrativoDocumentoController::class);

Auth::routes();





